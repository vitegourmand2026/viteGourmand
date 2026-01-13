<?php
function sendEmailBrevo(string $to, string $subject, string $htmlContent): bool
{
    $config = require __DIR__ . '/../process/config_brevo.php';

    $data = [
        "to" => [["email" => $to]],
        "sender" => [
            "email" => $config['sender_email'],
            "name" => $config['sender_name']
        ],
        "subject" => $subject,
        "htmlContent" => $htmlContent
    ];

    $ch = curl_init("https://api.brevo.com/v3/smtp/email");
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "accept: application/json",
        "api-key: {$config['api_key']}",
        "content-type: application/json"
        ]);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($ch);
    $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    return $httpcode === 201;
}
