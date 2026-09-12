<?php
namespace App\Core;

use Database;
use PDO;

class WhatsappService
{
    public static function sendMessage($recipientMobile, $messageText)
    {
        $db = (new Database())->getConnection();
        $settings = $db->query("SELECT * FROM whatsapp_settings WHERE id = 1")->fetch(PDO::FETCH_ASSOC);

        if (!$settings || $settings['status'] !== 'Active' || empty($settings['access_token'])) {
            return false; // WhatsApp gateway inactive or unconfigured
        }

        // Format recipient mobile (ensure international format without '+' if required by provider)
        $mobile = preg_replace('/[^0-9]/', '', $recipientMobile);

        $url = $settings['api_url'];
        $token = $settings['access_token'];

        // Standard Meta Cloud API Payload structure
        $data = [
            'messaging_product' => 'whatsapp',
            'recipient_type' => 'individual',
            'to' => $mobile,
            'type' => 'text',
            'text' => [
                'preview_url' => false,
                'body' => $messageText
            ]
        ];

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $token,
            'Content-Type: application/json'
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        return $httpCode === 200;
    }
}