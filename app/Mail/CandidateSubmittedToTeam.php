<?php

namespace App\Mail;

use App\Models\Candidature;
use App\Models\PublicationApplication;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CandidateSubmittedToTeam extends Mailable
{
    use Queueable, SerializesModels;

    public $candidature;

    public function __construct(PublicationApplication $candidature)
    {
        $this->candidature = $candidature;
    }

    public function build()
    {
        return $this->subject("Nouvelle candidature soumise")
            ->view('emails.to_team')
            ->with([
                'candidature' => $this->candidature,
            ]);
    }
}
