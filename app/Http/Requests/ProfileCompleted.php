<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProfileCompleted extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $user = auth()->user(); // ou récupère le user selon ton cas
        $role = [
            // Diplômes
            'diplomas' => 'required|array|min:1',
            'diplomas.*.diploma' => 'required|string|max:255',
            'diplomas.*.option' => 'nullable|string|max:255',

            // CGIAR Information
            // 'cgiar_information.current' => 'nullable|boolean',
            'cgiar_information.cgiar_center' => 'nullable|string|max:255',
            'cgiar_information.cgiar_email' => 'nullable|email|max:255',

            // Expériences
            'experiences' => 'required|array|min:1',
            'experiences.*.company_name' => 'required|string|max:255',
            'experiences.*.position' => 'required|string|max:255',
            'experiences.*.start_date' => 'required|date',
            'experiences.*.end_date' => 'nullable|date|after_or_equal:experience.*.start_date',
            // 'experience.*.current' => 'required|boolean',

            // Identification
            // 'identification.birth_date' => 'required|date',
            // 'identification.address' => 'required|string|max:255',
            // 'identification.gender' => 'required|in:male,female,other',

            // Origin
            'origin.nationality' => 'required|string|max:255',
            'origin.country' => 'required|string|max:255',
            'origin.city' => 'required|string|max:255',
            'origin.experience_years' => 'required|integer|min:0',
            'origin.french_level' => 'required|string|max:50',
            'origin.english_level' => 'required|string|max:50',

            // References
            'references' => 'required|array|min:1',
            'references.*.full_name' => 'required|string|max:255',
            'references.*.function' => 'required|string|max:255',
            'references.*.phone' => 'required|string|max:20',
            'references.*.email' => 'nullable|email|max:255',

        ];

        if (!$user->application || $user->application->documents()->count() === 0) {
            $rules['documents'] = 'required|array|min:1';
            $rules['documents.*.file'] = 'required|file|mimes:pdf,doc,docx,jpg,png|max:10240';
        } else {
            $rules['documents'] = 'nullable|array';
            $rules['documents.*.file'] = 'nullable|file|mimes:pdf,doc,docx,jpg,png|max:10240';
        }
        return $role;
    }
}
