<?php


namespace Quartetcom\SlackToGithubIssue\Action;


use GuzzleHttp\Client;
use Quartetcom\SlackToGithubIssue\Payload\Payload;
use Quartetcom\SlackToGithubIssue\Payload\PayloadTypes;

class OpenModal implements ActionInterface
{
    /**
     * @var Client
     */
    private $httpClient;

    /**
     * @var string
     */
    private $slackOAuthToken;

    /**
     * @param Client $httpClient
     * @param string $slackOAuthToken
     */
    public function __construct(Client $httpClient, string $slackOAuthToken)
    {
        $this->httpClient = $httpClient;
        $this->slackOAuthToken = $slackOAuthToken;
    }

    public function supports(Payload $payload): bool
    {
        return $payload->getType() === PayloadTypes::TYPE_MESSAGE;
    }

    public function invoke(Payload $payload): void
    {
        $dialogData = [
            'trigger_id' => $payload->getTriggerId(),
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
                        'value' => $payload->getMessage()['text'],
                    ],
                    [
                        'type' => 'text',
                        'label' => 'Slack上のURL',
                        'name' => 'slack_url',
                        'value' => $payload->getMessageUrl(),
                    ],
                ],
            ]),
        ];

        // open dialog
        $this->httpClient->post('https://slack.com/api/dialog.open', [
            'body' => json_encode($dialogData),
            'headers' => [
                'content-type' => 'application/json; charset=utf-8',
                'authorization' => 'Bearer '.$this->slackOAuthToken,
            ],
        ]);
    }

}
