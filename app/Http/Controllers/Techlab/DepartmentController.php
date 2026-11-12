<?php

namespace App\Http\Controllers\Techlab;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Department;
use App\Models\DepartmentSignature;
use App\Models\SchoolYear;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class DepartmentController extends Controller
{
    /**
     * List all departments.
     */
    public function index()
    {
        $departments = Department::orderBy('name')->get();
        $activeSchoolYear = SchoolYear::where('is_active', true)->first();

        return view('techlab.departments.index', [
            'departments' => $departments,
            'activeSchoolYear' => $activeSchoolYear,
        ]);
    }

    /**
     * Show the form to create a new department.
     */
    public function create()
    {
        return view('techlab.departments.create');
    }

    /**
     * Store a new department and its staff login.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'officer_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:departments,email', 'unique:users,email'],
            'default_password' => ['required', 'string', 'min:6'],
            'requirements' => ['nullable', 'string', 'max:2000'],
            'is_techlab' => ['sometimes', 'boolean'],
            'is_registrar' => ['sometimes', 'boolean'],
        ]);

        $validated['is_techlab'] = $request->boolean('is_techlab');
        $validated['is_registrar'] = $request->boolean('is_registrar');

        $department = Department::create($validated);

        User::create([
            'name' => $validated['officer_name'],
            'email' => $validated['email'],
            'password' => bcrypt($validated['default_password']),
            'department_id' => $department->id,
            'must_change_password' => true,
        ]);

        AuditLog::record(
            'department.created',
            $department->name,
            "Officer: {$validated['officer_name']}; login email: {$validated['email']}"
        );

        return redirect()->route('techlab.departments.index')->with('success', 'Department created successfully.');
    }

    /**
     * Show the form to edit a department.
     */
    public function edit(Department $department)
    {
        return view('techlab.departments.edit', [
            'department' => $department,
        ]);
    }

    /**
     * Update a department's details, requirements, or signature.
     * The office's login account is kept in step with its name and email.
     */
    public function update(Request $request, Department $department)
    {
        $user = User::where('department_id', $department->id)->first();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'officer_name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'email',
                'unique:departments,email,' . $department->id,
                Rule::unique('users', 'email')->ignore($user?->id),
            ],
            'requirements' => ['nullable', 'string', 'max:2000'],
            'signature' => ['nullable', 'image', 'mimes:png,jpg,jpeg', 'max:1024'],
            'is_registrar' => ['sometimes', 'boolean'],
        ]);

        // Work out what is changing, for the audit log
        $changes = [];

        foreach (['name' => 'office name', 'officer_name' => 'officer', 'email' => 'email'] as $field => $label) {
            if ($department->{$field} !== $validated[$field]) {
                $changes[] = "$label: {$department->{$field}} to {$validated[$field]}";
            }
        }

        if (($department->requirements ?? '') !== ($validated['requirements'] ?? '')) {
            $changes[] = 'requirements edited';
        }

        if ($request->hasFile('signature')) {
            $changes[] = 'signature replaced';
        }

        if ((bool) $department->is_registrar !== $request->boolean('is_registrar')) {
            $changes[] = 'Registrar role: ' . ($request->boolean('is_registrar') ? 'on' : 'off');
        }

        if ($request->hasFile('signature')) {
            $file = $request->file('signature');

            // The image is kept in the database, and the office record points to it
            DepartmentSignature::updateOrCreate(
                ['department_id' => $department->id],
                [
                    'mime' => $file->getMimeType() ?: 'image/png',
                    'data' => base64_encode(file_get_contents($file->getRealPath())),
                ]
            );

            $validated['signature_path'] = 'signatures/' . $department->id;
        }

        $department->update([
            'name' => $validated['name'],
            'officer_name' => $validated['officer_name'],
            'email' => $validated['email'],
            'requirements' => $validated['requirements'] ?? null,
            'signature_path' => $validated['signature_path'] ?? $department->signature_path,
            'is_registrar' => $request->boolean('is_registrar'),
        ]);

        // Keep the office's login account in step with its record
        $user?->update([
            'name' => $validated['officer_name'],
            'email' => $validated['email'],
        ]);

        if ($changes) {
            AuditLog::record('department.updated', $department->name, implode('; ', $changes));
        }

        return redirect()->route('techlab.departments.index')->with('success', 'Department updated successfully.');
    }

    /**
     * Reset a department's staff login password back to its default.
     */
    public function resetPassword(Department $department)
    {
        $user = User::where('department_id', $department->id)->first();

        if (! $user) {
            return back()->withErrors(['department' => 'No staff account found for this department.']);
        }

        $user->update([
            'password' => bcrypt($department->default_password),
            'must_change_password' => true,
        ]);

        AuditLog::record('department.password_reset', $department->name, 'Password set back to the default');

        return back()->with('success', "Password for {$department->name} has been reset to default.");
    }

    /**
     * Delete a department.
     */
    public function destroy(Department $department)
    {
        DepartmentSignature::where('department_id', $department->id)->delete();

        $name = $department->name;

        User::where('department_id', $department->id)->delete();

        $department->delete();

        AuditLog::record('department.deleted', $name, 'The office and its login account were deleted');

        return redirect()->route('techlab.departments.index')->with('success', 'Department deleted.');
    }
}
