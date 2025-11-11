<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class OffreRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Autorise toutes les requêtes (ajuste si besoin avec policies)
        return true;
    }

    public function rules(): array
    {
        return [
            'offre.uuid' => 'required|string|max:255',
            'offre.reference' => [
                'required',
                'string',
                'max:255',
                Rule::unique('publications', 'reference')
                    ->ignore($this->offre['uuid'], 'uuid'), // <-- ignore l'offre actuelle
            ],
            'offre.position_title' => 'required|string|max:255',
            'offre.country_duty_station' => 'required|string|max:255',
            'offre.published_at' => 'required|date',
            'offre.expires_at' => 'required|date|after_or_equal:offre.published_at',
            'offre.reason' => 'required|string|max:255',
            'offre.manager' => 'required|string|max:255',
            'offre.center' => 'required|string|max:255',
            'offre.status' => 'required|string|max:255',
            'offre.reason_replacement' => 'nullable|string|max:255',
            'offre.assign_by' => 'nullable|string|max:255',
            'offre.program' => 'nullable|string|max:255',
            'offre.city_duty_station' => 'nullable|string|max:255',
            'offre.grade' => 'nullable|string|max:255',
            'offre.type' => 'nullable|string|max:255',
        ];
    }


    public function messages(): array
    {
        return [
            'offre.uuid.required' => 'The offer ID is required.',
            'offre.uuid.string' => 'The offer ID must be a string.',
            'offre.uuid.max' => 'The offer ID may not exceed 255 characters.',

            'offre.reference.required' => 'The offer reference is required.',
            'offre.reference.string' => 'The offer reference must be a string.',
            'offre.reference.max' => 'The offer reference may not exceed 255 characters.',

            'offre.position_title.required' => 'The position title is required.',
            'offre.position_title.string' => 'The position title must be a string.',
            'offre.position_title.max' => 'The position title may not exceed 255 characters.',

            'offre.country_duty_station.required' => 'The country of duty station is required.',
            'offre.country_duty_station.string' => 'The country of duty station must be a string.',
            'offre.country_duty_station.max' => 'The country of duty station may not exceed 255 characters.',

            'offre.published_at.required' => 'The published date is required.',
            'offre.published_at.date' => 'The published date must be a valid date.',

            'offre.expires_at.required' => 'The expiration date is required.',
            'offre.expires_at.date' => 'The expiration date must be a valid date.',
            'offre.expires_at.after_or_equal' => 'The expiration date must be after or equal to the published date.',

            'offre.reason.required' => 'The reason is required.',
            'offre.reason.string' => 'The reason must be a string.',
            'offre.reason.max' => 'The reason may not exceed 255 characters.',

            'offre.manager.required' => 'The manager is required.',
            'offre.manager.string' => 'The manager must be a string.',
            'offre.manager.max' => 'The manager may not exceed 255 characters.',

            'offre.center.required' => 'The center is required.',
            'offre.center.string' => 'The center must be a string.',
            'offre.center.max' => 'The center may not exceed 255 characters.',

            'offre.status.required' => 'The status of the offer is required.',
            'offre.status.string' => 'The status of the offer must be a string.',
            'offre.status.max' => 'The status of the offer may not exceed 255 characters.',

            'offre.reason_replacement.string' => 'The reason for replacement must be a string.',
            'offre.reason_replacement.max' => 'The reason for replacement may not exceed 255 characters.',

            'offre.assign_by.string' => 'The assign by field must be a string.',
            'offre.assign_by.max' => 'The assign by field may not exceed 255 characters.',

            'offre.type.string' => 'The type must be a string.',
            'offre.type.max' => 'The type may not exceed 255 characters.',
        ];
    }
}
