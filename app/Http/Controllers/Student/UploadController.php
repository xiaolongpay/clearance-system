<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\ClearanceItem;
use App\Models\ClearanceUpload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UploadController extends Controller
{
    private const MAX_PER_OFFICE = 5;

    private const MIME_BY_EXTENSION = [
        'jpg' => 'image/jpeg',
        'jpeg' => 'image/jpeg',
        'png' => 'image/png',
        'pdf' => 'application/pdf',
        'doc' => 'application/msword',
        'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
    ];

    /**
     * A student attaches a file or a link to one of their own clearance items.
     */
    public function store(Request $request, ClearanceItem $clearanceItem)
    {
        $student = Auth::guard('student')->user();

        abort_if($clearanceItem->student_id !== $student->id, 403);

        $clearanceItem->load('department');

        if (! ($clearanceItem->department->accepts_uploads ?? true)) {
            return $this->fail('This office does not take attachments.');
        }

        if ($clearanceItem->status === 'cleared') {
            return $this->fail('This office has already cleared you.');
        }

        if ($clearanceItem->uploads()->count() >= self::MAX_PER_OFFICE) {
            return $this->fail('You can attach up to ' . self::MAX_PER_OFFICE . ' items for one office. Remove one first.');
        }

        $request->validate([
            'file' => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf,doc,docx', 'max:2048'],
            'link' => ['nullable', 'url:http,https', 'max:500'],
        ], [
            'file.max' => 'The file is too large. The limit is 2 MB.',
            'file.mimes' => 'Only JPG, PNG, PDF, DOC and DOCX files are allowed.',
            'file.uploaded' => 'The file could not be uploaded. Keep it under 2 MB.',
            'link.url' => 'Paste a full link that starts with https://',
        ]);

        $file = $request->file('file');
        $link = trim((string) $request->input('link'));

        if (! $file && $link === '') {
            return $this->fail('Choose a file or paste a link first.');
        }

        if ($file) {
            $extension = strtolower($file->getClientOriginalExtension());
            $name = preg_replace('/[^\w\s.\-()]/u', '', basename($file->getClientOriginalName()));
            $name = mb_substr($name !== '' ? $name : "file.$extension", 0, 120);

            $upload = ClearanceUpload::create([
                'clearance_item_id' => $clearanceItem->id,
                'kind' => 'file',
                'original_name' => $name,
                'mime' => self::MIME_BY_EXTENSION[$extension] ?? 'application/octet-stream',
                'size' => $file->getSize(),
                'data' => base64_encode(file_get_contents($file->getRealPath())),
            ]);
        } else {
            $upload = ClearanceUpload::create([
                'clearance_item_id' => $clearanceItem->id,
                'kind' => 'link',
                'url' => $link,
            ]);
        }

        // A rejected student who sends something new goes back to the office's pending list
        if ($clearanceItem->status === 'rejected') {
            $clearanceItem->update([
                'status' => 'pending',
                'signed_by' => null,
                'signed_at' => null,
            ]);
        }

        $this->audit('upload.added', $clearanceItem, $upload->label());

        return response()->json(['ok' => true]);
    }

    /**
     * A student removes one of their own attachments, until the office clears them.
     */
    public function destroy(ClearanceUpload $upload)
    {
        $student = Auth::guard('student')->user();
        $item = $upload->clearanceItem;

        abort_if(! $item || $item->student_id !== $student->id, 403);

        if ($item->status === 'cleared') {
            return $this->fail('This office has already cleared you.');
        }

        $label = $upload->label();
        $upload->delete();

        $this->audit('upload.removed', $item, $label);

        return response()->json(['ok' => true]);
    }

    private function fail(string $message)
    {
        return response()->json(['message' => $message, 'errors' => ['file' => [$message]]], 422);
    }

    /**
     * The audit log must never stop an upload, so any problem here is ignored.
     */
    private function audit(string $action, ClearanceItem $item, string $detail): void
    {
        try {
            $item->loadMissing(['student', 'department']);
            $student = $item->student;

            AuditLog::record(
                $action,
                "{$student->last_name}, {$student->first_name} ({$student->student_number}) - {$item->department->name}",
                $detail
            );
        } catch (\Throwable $e) {
            report($e);
        }
    }
}
