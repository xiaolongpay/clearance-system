<?php

namespace App\Http\Controllers;

use App\Models\DepartmentSignature;

class SignatureImageController extends Controller
{
    /**
     * Send an office's signature image, which is kept in the database.
     */
    public function show(int $id)
    {
        $signature = DepartmentSignature::where('department_id', $id)->first();

        abort_if(! $signature, 404);

        return response(base64_decode($signature->data), 200, [
            'Content-Type' => $signature->mime,
            'Cache-Control' => 'public, max-age=300',
        ]);
    }
}
