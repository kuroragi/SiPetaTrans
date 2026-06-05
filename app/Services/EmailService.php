<?php

namespace App\Services;

use App\Mail\AssetReportMail;
use Illuminate\Support\Facades\Mail;

class EmailService
{
    public function send(
        string $email,
        string $title,
        string $message
    ): void
    {
        Mail::to($email)
            ->send(
                new AssetReportMail(
                    $title,
                    $message
                )
            );
    }
}