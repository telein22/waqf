<?php

namespace Application\ThirdParties\Whatsapp;

use Application\Modules\SendibleAsAttachment;
use System\Core\Config;

class Whatsapp
{
private string $token;
private string $instance;

    public function __construct()
    {
        $whatsappConfig = Config::get('Application')->Whatsapp;
        $this->token = $whatsappConfig['token'];
        $this->instance = $whatsappConfig['instance'];
    }

    public static function sendChat(?string $mobile, string $message): void
    {
        $obj = new static();

        if (!$mobile) {
            return;
        }

        $params = [
            'token' => $obj->token,
            'to' => $mobile,
            'body' => $message,
        ];

        $obj->callWhatsAppApi('chat', $params);
    }

    public static function sendImageMessage(?string $mobile, string $base64Image, ?string $caption = null): void
    {
        $obj = new static();

        if (!$mobile) {
            return;
        }

        $params = [
            'token' => $obj->token,
            'to' => $mobile,
            'image' => $base64Image,
        ];

        if ($caption) {
            $params['caption'] = $caption;
        }

        $obj->callWhatsAppApi('image', $params);
    }

    public static function sendDocument(?string $mobile, SendibleAsAttachment $attachment): void
    {
        if (!$mobile) {
            return;
        }

        $obj = new static();
        $documentData = file_get_contents($attachment->getFullPath());
        $docBase64 = base64_encode($documentData);

        $params = [
            'token' => $obj->token,
            'to' => $mobile,
            'document' => $docBase64,
            'filename' => $attachment->getFileName(),
            'caption' => $attachment->getFileCaption()
        ];

        $obj->callWhatsAppApi('document', $params);
    }

    private function callWhatsAppApi($uri, $params): void
    {
        $instance = $this->instance;
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => "https://api.ultramsg.com/{$instance}/messages/{$uri}",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => http_build_query($params),
            CURLOPT_HTTPHEADER => [
                "content-type: application/x-www-form-urlencoded"
            ]
        ]);

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            echo "cURL Error #:" . $err;
        }
    }
}