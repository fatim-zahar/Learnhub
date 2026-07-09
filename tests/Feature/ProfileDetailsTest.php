<?php

use App\Models\Country;
use App\Models\Language;
use App\Models\Speciality;
use App\Models\Tag;
use App\Models\Tutor;
use App\Models\User;

it('cannot update name and email from profile details', function () {
    $user = User::factory()->create([
        'name' => 'Original Name',
        'email' => 'original@example.com',
        'role' => 'tutor',
    ]);

    $country = Country::factory()->create(['name' => 'USA']);

    $tutor = Tutor::factory()->create([
        'user_id' => $user->id,
        'country_id' => $country->id,
        'bio' => 'Original bio',
    ]);

    $language = Language::create(['language' => 'English']);
    $speciality = Speciality::create(['title' => 'Math']);
    $tag = Tag::create([
        'title' => 'Algebra',
        'speciality_id' => $speciality->id,
    ]);

    $this->actingAs($user)
        ->put(route('profile.details.update'), [
            'country' => 'USA',
            'bio' => 'New bio',
            'languages' => ['English'],
            'specialities' => ['Math'],
            'tags' => ['Algebra'],
        ])
        ->assertRedirect();

    $user->refresh();
    $tutor->refresh();

    expect($user->name)->toBe('Original Name');
    expect($user->email)->toBe('original@example.com');
    expect($tutor->bio)->toBe('New bio');
});
