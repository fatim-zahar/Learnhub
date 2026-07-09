<?php

use App\Models\User;

test('guests are redirected to login from root', function () {
    $this->get('/')
        ->assertRedirect(route('login'));
});

test('tutors are redirected to tutor dashboard from root', function () {
    $user = User::factory()->create(['role' => 'tutor']);

    $this->actingAs($user)
        ->get('/')
        ->assertRedirect(route('tutor.dashboard'));
});

test('students are redirected to student dashboard from root', function () {
    $user = User::factory()->create(['role' => 'student']);

    $this->actingAs($user)
        ->get('/')
        ->assertRedirect(route('student.dashboard'));
});
