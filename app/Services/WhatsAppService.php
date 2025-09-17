<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class WhatsAppService
{
    protected $token;
    protected $phoneNumberId;

    public function __construct()
    {
        $this->token = config('services.whatsapp.token');
        $this->phoneNumberId = config('services.whatsapp.phone_number_id');
    }

    public function sendTemplate($to, $template = 'hello_world')
    {
        $url = "https://graph.facebook.com/v22.0/{$this->phoneNumberId}/messages";

        $response = Http::withToken($this->token)
            ->post($url, [
                'messaging_product' => 'whatsapp',
                'to' => $to,
                'type' => 'template',
                'template' => [
                    'name' => $template,
                    'language' => ['code' => 'en_US']
                ]
            ]);

        return $response->json();
    }

    public function sendText($to, $message)
    {
        $url = "https://graph.facebook.com/v22.0/{$this->phoneNumberId}/messages";

        $response = Http::withToken($this->token)
            ->post($url, [
                'messaging_product' => 'whatsapp',
                'to' => $to,
                'type' => 'text',
                'text' => ['body' => $message]
            ]);

        return $response->json();
    }
}
