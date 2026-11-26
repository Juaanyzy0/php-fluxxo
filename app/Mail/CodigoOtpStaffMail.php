<?php

namespace App\Mail;

use App\Models\UsuarioStaff;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CodigoOtpStaffMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public UsuarioStaff $staff,
        public string $codigo,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "Código de verificación FLUXXO: {$this->codigo}",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.otp-staff',
            with: [
                'staff' => $this->staff,
                'codigo' => $this->codigo,
                'vigenciaMinutos' => 5,
            ],
        );
    }

    /**
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
