<?php

use GuzzleHttp\Client as HttpClient;
use Github\Client as GithubClient;
use Github\Api\Issue as GithubIssueClient;
use Dotenv\Dotenv;
use Symfony\Component\HttpFoundation\Request;

require __DIR__.'/../vendor/autoload.php';

if (file_exists(__DIR__.'/../.env')) {
    Dotenv::create(__DIR__ . '/../')->load();
} else {
    file_put_contents('log.txt', 'no .env found');
}

$httpClient = new HttpClient();
$githubClient = new GithubClient();
$githubClient->authenticate('http_token', getenv('GITHUB_OAUTH_TOKEN'));

$request = Request::createFromGlobals();
$payloadString = $request->getContent();

$parsed = [];
parse_str($payloadString, $parsed);
$payload = json_decode($parsed['payload'], true);


file_put_contents('receive_parsed.txt', var_export($payload, true));


if ($payload['type'] === 'message_action') {
    list($ts1, $ts2) = explode('.', $payload['message_ts']);
    $url = sprintf('https://quartetcom.slack.com/archives/%s/p%s%s', $payload['channel']['id'], $ts1, $ts2);

    $dialogData = [
        'trigger_id' => $payload['trigger_id'],
        'dialog' => json_encode([
            'callback_id' => 'div_development_create_lisket_support_issue',
            'title' => 'サポートissue化',
            'submit_label' => 'issue作成',
            'elements' => [
                [
                    'type' => 'text',
                    'label' => 'issueタイトル',
                    'name' => 'issue_title',
                ],
                [
                    'type' => 'textarea',
                    'label' => 'issue内容',
                    'name' => 'issue_body',
                    'value' => $payload['message']['text'],
                ],
                [
                    'type' => 'text',
                    'label' => 'Slack上のURL',
                    'name' => 'slack_url',
                    'value' => $url,
                ],
            ],
        ]),
    ];

    // open dialog
    $response = $httpClient->post('https://slack.com/api/dialog.open', [
        'body' => json_encode($dialogData),
        'headers' => [
            'content-type' => 'application/json; charset=utf-8',
            'authorization' => 'Bearer '.getenv('SLACK_OAUTH_TOKEN'),
        ],
    ]);
} elseif ($payload['type'] === 'dialog_submission') {
   // gather info to create issue
    $submission = $payload['submission'];
    // generate title & desc
    $title = $submission['issue_title'];
    $description = implode("\n", [$submission['issue_body'], $submission['slack_url']]);
    // post to github
    $issueClient = new GithubIssueClient($githubClient);
    $issueClient->create(getenv('GITHUB_ORG'), getenv('GITHUB_REPOSITORY'), [
        'title' => $title,
        'body' => $description,
    ]);
}

