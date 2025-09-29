<?php

namespace App\Mail;

use App\Models\Candidature;
use App\Models\PublicationApplication;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CandidateSubmittedToUser extends Mailable
{
    use Queueable, SerializesModels;

    public $candidature;

    public function __construct(PublicationApplication $candidature)
    {
        $this->candidature = $candidature;
    }

    public function build()
    {
        return $this->subject("Votre candidature a été soumise")
            ->view('emails.to_user')
            ->with([
                'candidature' => $this->candidature,
            ]);
    }
}
