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
        $viewData = [
            'trigger_id' => $payload->getTriggerId(),
            'view' => [
                'type' => 'modal',
                'callback_id' => 'div_development_create_lisket_support_issue',
                'title' => [
                    'type' => 'plain_text',
                    'text' => 'サポートissue化',
                ],
                'submit' => [
                    'type' => 'plain_text',
                    'text' => 'issue作成',
                ],
                'close' => [
                    'type' => 'plain_text',
                    'text' => 'キャンセル',
                ],
                'blocks' => [
                    [
                        'type' => 'input',
                        'label' => [
                            'type' => 'plain_text',
                            'text' => 'issueタイトル',
                        ],
                        'element' => [
                            'type' => 'plain_text_input',
                            'action_id' => 'issue_title',
                        ],
                    ],
                    [
                        'type' => 'input',
                        'label' => [
                            'type' => 'plain_text',
                            'text' => 'issue内容',
                        ],
                        'element' => [
                            'type' => 'plain_text_input',
                            'action_id' => 'issue_body',
                            'multiline' => true,
                            'initial_value' => $this->messageFetcherResolver->resolve($payload)->fetch($payload),
                        ],
                    ],
                    [
                        'type' => 'input',
                        'label' => [
                            'type' => 'plain_text',
                            'text' => 'Slack上のURL',
                        ],
                        'element' => [
                            'type' => 'plain_text_input',
                            'action_id' => 'slack_url',
                            'initial_value' => $this->messageUrlFactory->create($payload),
                        ],
                    ],
                ],
            ],
        ];

        // open interactive view
        $this->httpClient->post('https://slack.com/api/views.open', [
            'body' => json_encode($viewData),
            'headers' => [
                'content-type' => 'application/json; charset=utf-8',
                'authorization' => 'Bearer '.$this->slackOAuthToken,
            ],
        ]);
    }

}
