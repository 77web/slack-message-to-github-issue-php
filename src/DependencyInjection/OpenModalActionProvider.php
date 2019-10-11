<?php


namespace Quartetcom\SlackToGithubIssue\DependencyInjection;


use GuzzleHttp\Client;
use Quartetcom\SlackToGithubIssue\Action\OpenModal;
use Quartetcom\SlackToGithubIssue\Slack\MessageFetcherResolver;
use Ray\Di\Di\Named;
use Ray\Di\ProviderInterface;

class OpenModalActionProvider implements ProviderInterface
{
    /**
     * @var MessageFetcherResolver
     */
    private $messageFetcherResolver;

    /**
     * @var Client
     */
    private $httpClient;

    /**
     * @var string
     */
    private $slackToken;

    /**
     * @Named("messageFetcherResolver=messageFetcherResolver, httpClient=httpClient, slackToken=slackToken")
     * @param MessageFetcherResolver $messageFetcherResolver
     * @param Client $httpClient
     * @param string $slackToken
     */
    public function __construct(MessageFetcherResolver $messageFetcherResolver, Client $httpClient, string $slackToken)
    {
        $this->messageFetcherResolver = $messageFetcherResolver;
        $this->httpClient = $httpClient;
        $this->slackToken = $slackToken;
    }


    public function get()
    {
        return new OpenModal($this->messageFetcherResolver, $this->httpClient, $this->slackToken);
    }

}
