<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTripRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check(); // User must be authenticated
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            // Required fields
            'title' => ['required', 'string', 'max:255'],
            'destination' => ['required', 'string', 'max:255'],
            'hotel_name' => ['required', 'string', 'max:255'],
            'type' => ['required', 'string', 'in:Beach,Safari,Adventure,Cultural,Luxury,Budget,Other'],
            'description' => ['required', 'string'],
            'duration_days' => ['required', 'integer', 'min:1'],
            'base_price_per_person' => ['required', 'numeric', 'min:0'],
            'child_discount_percent' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'cover_image_url' => ['nullable', 'url'], // Optional when editing
            'inclusions' => ['required', 'string', function ($attribute, $value, $fail) {
                $decoded = json_decode($value, true);
                if (empty($decoded) || !is_array($decoded)) {
                    $fail('At least one inclusion is required.');
                }
            }],
            'highlights' => ['required', 'string', function ($attribute, $value, $fail) {
                $decoded = json_decode($value, true);
                if (empty($decoded) || !is_array($decoded)) {
                    $fail('At least one highlight is required.');
                }
            }],
            'itinerary' => ['required', 'string', function ($attribute, $value, $fail) {
                $decoded = json_decode($value, true);
                if (empty($decoded) || !is_array($decoded)) {
                    $fail('At least one day in the itinerary is required.');
                }
            }],

            // Optional fields
            'notes' => ['nullable', 'string'],
            'gallery' => ['nullable', 'string'], // JSON string
            'exclusions' => ['nullable', 'string'], // JSON string
            'transport_options' => ['nullable', 'string'], // JSON string

            // Edit-specific fields
            'old_cover_image_url' => ['nullable', 'url'], // Old cover to delete
            'removed_gallery_images' => ['nullable', 'string'], // JSON array of removed images
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'title.required' => 'Trip title is required',
            'destination.required' => 'Destination is required',
            'hotel_name.required' => 'Hotel name is required',
            'type.required' => 'Trip type is required',
            'type.in' => 'Invalid trip type selected',
            'description.required' => 'Description is required',
            'duration_days.required' => 'Duration is required',
            'duration_days.min' => 'Duration must be at least 1 day',
            'base_price_per_person.required' => 'Base price is required',
            'base_price_per_person.min' => 'Price must be greater than 0',
            'cover_image_url.url' => 'Cover image must be a valid URL',
            'inclusions.required' => 'At least one inclusion is required',
            'highlights.required' => 'At least one highlight is required',
            'itinerary.required' => 'At least one day in the itinerary is required',
        ];
    }
}
