<?php

namespace App\Http\Controllers;

use App\Models\ClearanceUpload;
use Illuminate\Support\Facades\Auth;

class UploadFileController extends Controller
{
    /**
     * Open an attachment. Allowed for the student who sent it, the office
     * it was sent to, and Techlab. Everyone else gets a 403.
     */
    public function show(ClearanceUpload $upload)
    {
        $item = $upload->clearanceItem;
        $student = Auth::guard('student')->user();
        $staff = Auth::guard('web')->user();

        $isOwner = $student && $item && $item->student_id === $student->id;
        $isOffice = $staff && $item && $staff->department_id === $item->department_id;
        $isTechlab = $staff && $staff->department?->is_techlab;

        abort_unless($isOwner || $isOffice || $isTechlab, 403);

        // The office has now looked at it, so it no longer counts as new
        if (($isOffice || $isTechlab) && $upload->viewed_at === null) {
            $upload->forceFill(['viewed_at' => now()])->save();
        }

        if ($upload->kind === 'link') {
            return redirect()->away($upload->url);
        }

        $content = base64_decode((string) $upload->data, true);
        abort_if($content === false, 404);

        $inline = in_array($upload->mime, ['image/jpeg', 'image/png', 'application/pdf'], true);
        $filename = str_replace('"', '', $upload->original_name ?: 'attachment');

        return response($content, 200, [
            'Content-Type' => $upload->mime,
            'Content-Length' => strlen($content),
            'Content-Disposition' => ($inline ? 'inline' : 'attachment') . '; filename="' . $filename . '"',
            'X-Content-Type-Options' => 'nosniff',
            'Cache-Control' => 'private, no-store',
        ]);
    }
}
