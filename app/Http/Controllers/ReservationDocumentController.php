<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\ReservationDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ReservationDocumentController extends Controller
{
    /**
     * Authorize user access to this reservation's documents.
     */
    protected function authorizeAccess(Reservation $reservation): void
    {
        $user = Auth::user();
        if (!$user) {
            abort(401, 'Unauthenticated.');
        }

        // Admin, Superadmin, Staff, Cashier can view all documents
        if (in_array($user->role, ['admin', 'superadmin', 'staff', 'cashier'], true)) {
            return;
        }

        // The reservation owner can view their own documents
        if ($reservation->user_id === $user->id) {
            return;
        }

        abort(403, 'Unauthorized to view this document.');
    }

    /**
     * Locate the absolute filesystem path for the document.
     */
    protected function resolveFilePath(ReservationDocument $document): string
    {
        $fullPath = public_path($document->file_path);
        if (file_exists($fullPath)) {
            return $fullPath;
        }

        if (Storage::disk('public')->exists($document->file_path)) {
            return Storage::disk('public')->path($document->file_path);
        }

        abort(404, 'Document file not found on server.');
    }

    /**
     * Stream the document inline for browser previewing (PDF, images, etc.).
     */
    public function view(Request $request, Reservation $reservation, ReservationDocument $document)
    {
        if ($document->reservation_id !== $reservation->id) {
            abort(404, 'Document does not belong to this reservation.');
        }

        $this->authorizeAccess($reservation);
        $fullPath = $this->resolveFilePath($document);

        $mime = $document->file_type ?: @mime_content_type($fullPath) ?: 'application/octet-stream';

        return response()->file($fullPath, [
            'Content-Type'           => $mime,
            'Content-Disposition'    => 'inline; filename="' . addslashes($document->original_name) . '"',
            'X-Content-Type-Options' => 'nosniff',
            'Cache-Control'          => 'private, max-age=3600',
        ]);
    }

    /**
     * Force download the document with its original filename.
     */
    public function download(Request $request, Reservation $reservation, ReservationDocument $document)
    {
        if ($document->reservation_id !== $reservation->id) {
            abort(404, 'Document does not belong to this reservation.');
        }

        $this->authorizeAccess($reservation);
        $fullPath = $this->resolveFilePath($document);

        return response()->download($fullPath, $document->original_name, [
            'X-Content-Type-Options' => 'nosniff',
        ]);
    }
}
