<?php

namespace App\Http\Controllers\Techlab;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\SchoolYear;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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
     * Update a department's name, email, or signature.
     */
    public function update(Request $request, Department $department)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'officer_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:departments,email,' . $department->id],
            'signature' => ['nullable', 'image', 'max:2048'],
            'is_registrar' => ['sometimes', 'boolean'],
        ]);

        if ($request->hasFile('signature')) {
            if ($department->signature_path) {
                Storage::disk('public')->delete($department->signature_path);
            }

            $validated['signature_path'] = $request->file('signature')->store('signatures', 'public');
        }

        $department->update([
            'name' => $validated['name'],
            'officer_name' => $validated['officer_name'],
            'email' => $validated['email'],
            'signature_path' => $validated['signature_path'] ?? $department->signature_path,
            'is_registrar' => $request->boolean('is_registrar'),
        ]);

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

        return back()->with('success', "Password for {$department->name} has been reset to default.");
    }

    /**
     * Delete a department.
     */
    public function destroy(Department $department)
    {
        if ($department->signature_path) {
            Storage::disk('public')->delete($department->signature_path);
        }

        User::where('department_id', $department->id)->delete();

        $department->delete();

        return redirect()->route('techlab.departments.index')->with('success', 'Department deleted.');
    }
}