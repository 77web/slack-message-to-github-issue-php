<?php

use GuzzleHttp\Client;
use Dotenv\Dotenv;
use Symfony\Component\HttpFoundation\Request;

require __DIR__.'/../vendor/autoload.php';

if (file_exists(__DIR__.'/../.env')) {
    $dotenv = Dotenv::create(__DIR__ . '/../')->load;
}

$httpClient = new Client();

$request = Request::createFromGlobals();
$payloadString = $request->getContent();

file_put_contents('receive.txt', $payloadString);

$parsed = [];
parse_str($payloadString, $parsed);
$payload = json_decode($parsed['payload'], true);

file_put_contents('receive_parse.txt', var_export($payload, true));


if ($payload['type'] === 'message_action') {
    $dialogData = [
        'token' => getenv('SLACK_OAUTH_TOKEN'),
        'trigger_id' => $payload['trigger_id'],
        'dialog' => json_encode([
            'callback_id' => 'div_development_create_lisket_support_issue',
            'title' => 'operations-supportにissueを作る',
            'submit_label' => 'issue作成',
            'state' => 'Limo',
            'elements' => [
                [
                    'type' => 'text',
                    'label' => 'issueタイトル',
                    'name' => 'issue_title',
                ],
            ],
        ]),
    ];

    // open dialog
    $response = $httpClient->post('https://slack.com/api/dialog.open', [
        'body' => json_encode($dialogData),
        'headers' => [
            'content-type' => 'application/json',
        ],
    ]);
    file_put_contents('response.txt', $response->getBody()->getContents());
} elseif ($payload['type'] === 'dialog_submission') {
   // create an issue on operations-support
}

