<?php


namespace Quartetcom\SlackToGithubIssue\Action;


use GuzzleHttp\Client;
use Quartetcom\SlackToGithubIssue\Payload\Payload;
use Quartetcom\SlackToGithubIssue\Payload\PayloadTypes;
use Quartetcom\SlackToGithubIssue\Slack\MessageFetcherResolver;
use Quartetcom\SlackToGithubIssue\Slack\MessageUrlFactory;

class OpenModal implements ActionInterface
{
    /**
     * @var MessageFetcherResolver
     */
    private $messageFetcherResolver;

    /**
     * @var MessageUrlFactory
     */
    private $messageUrlFactory;

    /**
     * @var Client
     */
    private $httpClient;

    /**
     * @var string
     */
    private $slackOAuthToken;

    /**
     * @param MessageFetcherResolver $messageFetcherResolver
     * @param MessageUrlFactory $messageUrlFactory
     * @param Client $httpClient
     * @param string $slackOAuthToken
     */
    public function __construct(MessageFetcherResolver $messageFetcherResolver, MessageUrlFactory $messageUrlFactory, Client $httpClient, string $slackOAuthToken)
    {
        $this->messageFetcherResolver = $messageFetcherResolver;
        $this->messageUrlFactory = $messageUrlFactory;
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
                        'value' => $this->messageFetcherResolver->resolve($payload)->fetch($payload),
                    ],
                    [
                        'type' => 'text',
                        'label' => 'Slack上のURL',
                        'name' => 'slack_url',
                        'value' => $this->messageUrlFactory->create($payload),
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
