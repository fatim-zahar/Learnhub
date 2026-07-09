<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\SessionDocument;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class SessionDocumentController extends Controller
{
    use AuthorizesRequests;

    public function store(Request $request, Booking $booking)
    {
        $this->authorize('update', $booking);

        $request->validate([
            'document' => 'required|file|max:10240', // 10MB limit
        ]);

        $file = $request->file('document');
        $path = $file->store('session-documents');

        $booking->documents()->create([
            'filename' => $file->getClientOriginalName(),
            'path' => $path,
            'mime_type' => $file->getMimeType(),
            'size' => $file->getSize(),
        ]);

        return back();
    }

    public function download(SessionDocument $document): StreamedResponse
    {
        $this->authorize('view', $document->booking);

        return Storage::download($document->path, $document->filename);
    }

    public function destroy(SessionDocument $document)
    {
        $this->authorize('update', $document->booking);

        Storage::delete($document->path);
        $document->delete();

        return back();
    }
}
