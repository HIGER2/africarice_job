<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

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
            'offre.position_title' => 'required|string|max:255',
            'offre.country_duty_station' => 'required|string|max:255',
            'offre.published_at' => 'required|date',
            'offre.expires_at' => 'required|date|after_or_equal:offre.published_at',
            'offre.is_published' => 'required|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'offre.position_title.required' => 'Le titre du poste est obligatoire.',
            'offre.position_title.string' => 'Le titre du poste doit être une chaîne de caractères.',
            'offre.position_title.max' => 'Le titre du poste ne peut pas dépasser 255 caractères.',

            'offre.country_duty_station.required' => 'Le pays du poste est obligatoire.',
            'offre.country_duty_station.string' => 'Le pays du poste doit être une chaîne de caractères.',
            'offre.country_duty_station.max' => 'Le pays du poste ne peut pas dépasser 255 caractères.',

            'offre.published_at.required' => 'La date de publication est obligatoire.',
            'offre.published_at.date' => 'La date de publication doit être une date valide.',

            'offre.expires_at.required' => 'La date d’expiration est obligatoire.',
            'offre.expires_at.date' => 'La date d’expiration doit être une date valide.',
            'offre.expires_at.after_or_equal' => 'La date d’expiration doit être postérieure ou égale à la date de publication.',

            'offre.is_published.required' => 'Le statut de publication est obligatoire.',
            'offre.is_published.boolean' => 'Le statut de publication doit être vrai ou faux.',
        ];
    }
}
