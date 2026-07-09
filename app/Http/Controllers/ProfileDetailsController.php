<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileDetailsUpdateRequest;
use App\Models\Country;
use App\Models\Language;
use App\Models\Speciality;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;
use Throwable;

class ProfileDetailsController extends Controller
{
    public function edit(): Response
    {
        $specialities = Speciality::query()->with('tags')->get();

        return Inertia::render('profile/details', [
            'tutor' => Auth::user()->tutor->load('country', 'languages', 'specialities', 'tags'),
            'countries' => Country::all(),
            'languages' => Language::all(),
            'specialities' => $specialities,
        ]);
    }

    /**
     * @throws Throwable
     */
    public function update(ProfileDetailsUpdateRequest $request, User $user): RedirectResponse
    {
        $languagesIds = Language::query()->whereIn('language', $request->validated('languages'))->pluck('id');
        $specialitiesIds = Speciality::query()->whereIn('title', $request->validated('specialities'))->pluck('id');
        $tagsIds = Tag::query()->whereIn('title', $request->validated('tags'))->pluck('id');

        DB::transaction(function () use ($tagsIds, $specialitiesIds, $languagesIds, $request) {
            $user = $request->user();

            $tutor = $user->tutor;
            $tutor->fill([
                'bio' => $request->validated('bio'),
                'country_id' => Country::where('name', $request->validated('country'))->firstOrFail()->id,
            ]);
            $tutor->save();

            $tutor->languages()->sync($languagesIds);
            $tutor->specialities()->sync($specialitiesIds);
            $tutor->tags()->sync($tagsIds);
        });

        return back();
    }
}
