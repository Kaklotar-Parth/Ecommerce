<?php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SerialNumberEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $orderedSerials;

    public function __construct($orderedSerials)
    {
        $this->orderedSerials = $orderedSerials;
    }

    public function build()
    {
        $email = $this->subject('Your Product Serial Numbers & Downloads')
            ->view('emails.serial_numbers')
            ->with(['orderedSerials' => $this->orderedSerials]);

        // Attach files from the assets/virtual_products directory
        foreach ($this->orderedSerials as $item) {
            foreach ($item['file_paths'] as $filePath) {
                $fullPath = public_path("assets/virtual_products/{$filePath}");
                if ($filePath && file_exists($fullPath)) {
                    $email->attach($fullPath);
                }
            }
        }

        return $email;
    }
}
