<?php


namespace Quartetcom\SlackToGithubIssue\DependencyInjection;


use GuzzleHttp\Client;
use Quartetcom\SlackToGithubIssue\Action\OpenModal;
use Ray\Di\Di\Named;
use Ray\Di\ProviderInterface;

class OpenModalActionProvider implements ProviderInterface
{
    /**
     * @var Client
     */
    private $httpClient;

    /**
     * @var string
     */
    private $slackToken;

    /**
     * @Named("httpClient=httpClient, slackToken=slackToken")
     * @param Client $httpClient
     * @param string $slackToken
     */
    public function __construct(Client $httpClient, string $slackToken)
    {
        $this->httpClient = $httpClient;
        $this->slackToken = $slackToken;
    }


    public function get()
    {
        return new OpenModal($this->httpClient, $this->slackToken);
    }

}
