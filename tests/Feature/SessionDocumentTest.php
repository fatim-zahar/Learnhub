<?php

use App\Models\Booking;
use App\Models\Tutor;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

it('allows a tutor to upload a document to their session', function () {
    Storage::fake('local');

    $user = User::factory()->create(['role' => 'tutor']);
    $tutor = Tutor::factory()->create(['user_id' => $user->id]);
    $booking = Booking::factory()->create(['tutor_id' => $tutor->id]);

    $response = $this->actingAs($user)
        ->post(route('bookings.documents.store', $booking), [
            'document' => UploadedFile::fake()->create('test.pdf', 100),
        ]);

    $response->assertRedirect();
    $this->assertDatabaseHas('session_documents', [
        'booking_id' => $booking->id,
        'filename' => 'test.pdf',
    ]);
});

it('denies a tutor from uploading a document to another tutor session', function () {
    Storage::fake('local');

    $user = User::factory()->create(['role' => 'tutor']);
    Tutor::factory()->create(['user_id' => $user->id]);

    $otherTutor = Tutor::factory()->create();
    $booking = Booking::factory()->create(['tutor_id' => $otherTutor->id]);

    $response = $this->actingAs($user)
        ->post(route('bookings.documents.store', $booking), [
            'document' => UploadedFile::fake()->create('test.pdf', 100),
        ]);

    $response->assertForbidden();
});

it('denies a student from uploading a document to their session', function () {
    Storage::fake('local');

    $user = User::factory()->create(['role' => 'student']);
    $student = \App\Models\Student::factory()->create(['user_id' => $user->id]);
    $booking = Booking::factory()->create(['student_id' => $student->id]);

    $response = $this->actingAs($user)
        ->post(route('bookings.documents.store', $booking), [
            'document' => UploadedFile::fake()->create('test.pdf', 100),
        ]);

    $response->assertForbidden();
});

it('allows a tutor to delete their own document', function () {
    Storage::fake('local');

    $user = User::factory()->create(['role' => 'tutor']);
    $tutor = Tutor::factory()->create(['user_id' => $user->id]);
    $booking = Booking::factory()->create(['tutor_id' => $tutor->id]);
    $document = \App\Models\SessionDocument::create([
        'booking_id' => $booking->id,
        'filename' => 'test.pdf',
        'path' => 'path/to/test.pdf',
        'mime_type' => 'application/pdf',
        'size' => 100,
    ]);

    $response = $this->actingAs($user)
        ->delete(route('documents.destroy', $document));

    $response->assertRedirect();
    $this->assertDatabaseMissing('session_documents', ['id' => $document->id]);
});
