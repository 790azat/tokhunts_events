<?php

namespace App\Http\Controllers;

use App\Support\VercelBlob;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/** Implements the server half of `upload()` from @vercel/blob/client for admins. */
class BlobUploadController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        abort_unless(VercelBlob::enabled(), 404);
        abort_unless($request->input('type') === 'blob.generate-client-token', 400);

        $pathname = (string) $request->input('payload.pathname');
        abort_unless(preg_match('~^(works|site)/[\w.\-/]+$~', $pathname) && ! str_contains($pathname, '..'), 422);

        return response()->json([
            'type' => 'blob.generate-client-token',
            'clientToken' => VercelBlob::clientToken(
                $pathname,
                ['image/*', 'video/*'],
                500 * 1024 * 1024,
            ),
        ]);
    }
}
