<?php

use GuzzleHttp\Client;
use Dotenv\Dotenv;
use Symfony\Component\HttpFoundation\Request;

require __DIR__.'../vendor/autoload.php';

if (file_exists(__DIR__.'/../.env')) {
    $dotenv = Dotenv::create(__DIR__ . '/../')->load;
}

$httpClient = new Client();

$request = Request::createFromGlobals();
$payload = $request->getContent();

file_put_contents('receive.txt', $payload);

$payload = json_decode($payload, true);

if ($payload['type'] === 'message_action') {
    $dialogData = [
        'token' => getenv('SLACK_OAUTH_TOKEN'),
        'trigger_id' => $payload['trigger_id'],
        'dialog' => [
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
        ],
    ];

    // open dialog
    $httpClient->post('https://slack.com/api/dialog.open', [
        'body' => json_encode($dialogData),
        'headers' => [
            'content-type' => 'application/json',
        ],
    ]);
} elseif ($payload['type'] === 'dialog_submission') {
   // create an issue on operations-support
}

