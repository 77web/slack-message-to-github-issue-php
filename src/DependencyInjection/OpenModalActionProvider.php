<?php


namespace Quartetcom\SlackToGithubIssue\DependencyInjection;


use GuzzleHttp\Client;
use Quartetcom\SlackToGithubIssue\Action\OpenModal;
use Quartetcom\SlackToGithubIssue\Slack\DialogFactory;
use Quartetcom\SlackToGithubIssue\Slack\MessageFetcherResolver;
use Quartetcom\SlackToGithubIssue\Slack\MessageUrlFactory;
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
     * @var string
     */
    private $slackWorkspace;

    /**
     * @Named("messageFetcherResolver=messageFetcherResolver, httpClient=httpClient, slackToken=slackToken, slackWorkspace=slackWorkspace")
     * @param MessageFetcherResolver $messageFetcherResolver
     * @param Client $httpClient
     * @param string $slackToken
     * @param string $slackWorkspace
     */
    public function __construct(MessageFetcherResolver $messageFetcherResolver, Client $httpClient, string $slackToken, string $slackWorkspace)
    {
        $this->messageFetcherResolver = $messageFetcherResolver;
        $this->httpClient = $httpClient;
        $this->slackToken = $slackToken;
        $this->slackWorkspace = $slackWorkspace;
    }


    public function get()
    {
        return new OpenModal(new DialogFactory($this->messageFetcherResolver, new MessageUrlFactory($this->slackWorkspace)), $this->httpClient, $this->slackToken);
    }

}
