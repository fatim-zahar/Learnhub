<?php

namespace App\Http\Requests;

use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Http\FormRequest;

class ProfileDetailsUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'country' => 'required|string|exists:countries,name',
            'bio' => 'nullable|string',
            'languages' => 'required|array',
            'specialities' => 'required|array',
            'tags' => [
                'required',
                'array',
                'min:1',
                function ($attribute, $tags, $fail) {
                    $allowedTags = Tag::query()->whereHas('speciality', function (Builder $query) {
                        $query->whereIn('title', $this->input('specialities'));
                    })->pluck('title')->toArray();

                    if (count(array_intersect($tags, $allowedTags)) !== count($tags)) {
                        $fail('tags are not allowed');
                    }
                },
            ],
        ];
    }
}
