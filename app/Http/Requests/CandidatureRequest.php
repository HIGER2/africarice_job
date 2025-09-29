<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class CandidatureRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */

    //     protected function prepareForValidation()
    //         {
    //             // if ($this->has('identification') && is_string($this->identification)) {
    //             //     $this->merge([
    //             //         'identification' => json_decode($this->identification, true),
    //             //     ]);
    //             // }

    //             // if ($this->has('origin') && is_string($this->origin)) {
    //             //     $this->merge([
    //             //         'origin' => json_decode($this->origin, true),
    //             //     ]);
    //             // }

    //             // if ($this->has('cgiar_information') && is_string($this->cgiar_information)) {
    //             //     $this->merge([
    //             //         'cgiar_information' => json_decode($this->cgiar_information, true),
    //             //     ]);
    //             // }
    //     $this->merge([
    //         // 'identification' => $this->identification ?$this->identification : [],
    //         'diplomas' => $this->diplomas ? json_decode($this->diplomas, true) : [],
    //         'experience' => $this->experience ? json_decode($this->experience, true) : [],
    //         'reference' => $this->reference ? json_decode($this->reference, true) : [],
    //         // 'cgiar_information' => $this->cgiar_information ? json_decode($this->cgiar_information, true) : [],
    //         // 'origin' => $this->origin ? json_decode($this->origin, true) : [],
    //         'identification' => $this->identification ?? [],
    //         'origin' => $this->origin ? json_decode($this->origin, true): [],
    //         'cgiar_information' => $this->cgiar_information ?? [],
    //     ]);
    // }

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

    public function messages(): array
    {
        return [
            'diplomas.required' => 'Vous devez ajouter au moins un diplôme.',
            'diplomas.*.diploma.required' => 'Le nom du diplôme est obligatoire.',
            'diplomas.*.option.string' => 'L’option doit être une chaîne de caractères.',

            'cgiar_information.current.required' => 'Le statut CGIAR est obligatoire.',
            'cgiar_information.current.boolean' => 'Le statut CGIAR doit être vrai ou faux.',
            'cgiar_information.cgiar_email.email' => 'L’adresse e-mail CGIAR doit être valide.',

            'experience.required' => 'Vous devez ajouter au moins une expérience.',
            'experience.*.company_name.required' => 'Le nom de l’entreprise est obligatoire.',
            'experience.*.position.required' => 'Le poste est obligatoire.',
            'experience.*.start_date.required' => 'La date d\'experience de début est obligatoire.',
            'experience.*.end_date.after_or_equal' => 'La d\'experience date de fin doit être après la date de début.',
            'experience.*.current.required' => 'Le statut actuel est obligatoire.',

            'identification.birth_date.required' => 'La date de naissance est obligatoire.',
            'identification.address.required' => 'L’adresse est obligatoire.',
            'identification.gender.required' => 'Le genre est obligatoire.',
            'identification.gender.in' => 'Le genre doit être male, female ou other.',

            'origin.nationality.required' => 'La nationalité est obligatoire.',
            'origin.country.required' => 'Le pays est obligatoire.',
            'origin.city.required' => 'La ville est obligatoire.',
            'origin.experience_years.required' => 'Le nombre d’années d’expérience est obligatoire.',

            'reference.required' => 'Vous devez ajouter au moins une référence.',
            'reference.*.full_name.required' => 'Le nom complet de la référence est obligatoire.',
            'reference.*.function.required' => 'La fonction de la référence est obligatoire.',
            'reference.*.email.email' => 'L’email de la référence doit être valide.',

            'documents.required' => 'Vous devez ajouter au moins un document.',
            'documents.*.file.required' => 'Chaque document doit être un fichier.',
            'documents.*.file.mimes' => 'Les documents doivent être au format pdf, doc, docx, jpg ou png.',
            'documents.*.file.max' => 'Chaque document ne peut dépasser 10MB.',
        ];
    }
}
