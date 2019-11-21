<?php


namespace Quartetcom\SlackToGithubIssue\Slack;


use Quartetcom\SlackToGithubIssue\Payload\Payload;

class DialogFactory implements DialogFactoryInterface
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
     * @param MessageFetcherResolver $messageFetcherResolver
     * @param MessageUrlFactory $messageUrlFactory
     */
    public function __construct(MessageFetcherResolver $messageFetcherResolver, MessageUrlFactory $messageUrlFactory)
    {
        $this->messageFetcherResolver = $messageFetcherResolver;
        $this->messageUrlFactory = $messageUrlFactory;
    }

    public function create(Payload $payload): array
    {
        return [
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
    }

}
