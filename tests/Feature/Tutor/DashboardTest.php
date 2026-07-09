<?php

namespace Tests\Feature\Tutor;

use App\Models\Booking;
use App\Models\Tutor;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;

uses(RefreshDatabase::class);

it('tutor can see their dashboard with stats and bookings', function () {
    $tutor = Tutor::factory()->for(User::factory()->create(['role' => 'tutor']))->create();

    // Create some bookings
    Booking::factory()->confirmed()->create([
        'tutor_id' => $tutor->id,
        'start' => now()->addDay(),
        'end' => now()->addDay()->addHour(),
    ]);

    Booking::factory()->pending()->create([
        'tutor_id' => $tutor->id,
    ]);

    $this->actingAs($tutor->user)
        ->get(route('tutor.dashboard'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('tutor/dashboard')
            ->has('stats')
            ->where('stats.total_sessions', 1)
            ->where('stats.pending_requests', 1)
            ->where('stats.upcoming_sessions', 1)
            ->has('upcomingBookings', 1)
            ->has('pendingBookings', 1)
            ->has('counts')
            ->where('counts.pending', 1)
            ->where('counts.upcoming', 1)
        );
});

it('shows at most 5 pending and upcoming bookings and correct total counts', function () {
    $tutor = Tutor::factory()->for(User::factory()->create(['role' => 'tutor']))->create();

    // Create 7 pending bookings
    Booking::factory()->pending()->count(7)->create([
        'tutor_id' => $tutor->id,
    ]);

    // Create 6 upcoming confirmed bookings
    Booking::factory()->confirmed()->count(6)->create([
        'tutor_id' => $tutor->id,
        'start' => now()->addDay(),
        'end' => now()->addDay()->addHour(),
    ]);

    $this->actingAs($tutor->user)
        ->get(route('tutor.dashboard'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('tutor/dashboard')
            ->has('pendingBookings', 5)
            ->has('upcomingBookings', 5)
            ->where('counts.pending', 7)
            ->where('counts.upcoming', 6)
        );
});
