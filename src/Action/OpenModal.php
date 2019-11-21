<?php


namespace Quartetcom\SlackToGithubIssue\Action;


use GuzzleHttp\Client;
use Quartetcom\SlackToGithubIssue\Payload\Payload;
use Quartetcom\SlackToGithubIssue\Payload\PayloadTypes;
use Quartetcom\SlackToGithubIssue\Slack\DialogFactoryInterface;

class OpenModal implements ActionInterface
{
    /**
     * @var DialogFactoryInterface
     */
    private $dialogFactory;

    /**
     * @var Client
     */
    private $httpClient;

    /**
     * @var string
     */
    private $slackOAuthToken;

    /**
     * @param DialogFactoryInterface $dialogFactory
     * @param Client $httpClient
     * @param string $slackOAuthToken
     */
    public function __construct(DialogFactoryInterface $dialogFactory, Client $httpClient, string $slackOAuthToken)
    {
        $this->dialogFactory = $dialogFactory;
        $this->httpClient = $httpClient;
        $this->slackOAuthToken = $slackOAuthToken;
    }

    public function supports(Payload $payload): bool
    {
        return $payload->getType() === PayloadTypes::TYPE_MESSAGE;
    }

    public function invoke(Payload $payload): void
    {
        // open interactive view
        $this->httpClient->post('https://slack.com/api/views.open', [
            'body' => json_encode($this->dialogFactory->create($payload)),
            'headers' => [
                'content-type' => 'application/json; charset=utf-8',
                'authorization' => 'Bearer '.$this->slackOAuthToken,
            ],
        ]);
    }

}
