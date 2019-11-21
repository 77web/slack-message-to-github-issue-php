<?php


namespace Quartetcom\SlackToGithubIssue\DependencyInjection;


use GuzzleHttp\Client;
use Quartetcom\SlackToGithubIssue\Action\OpenModal;
use Quartetcom\SlackToGithubIssue\Slack\DialogFactoryInterface;
use Ray\Di\Di\Named;
use Ray\Di\ProviderInterface;

class OpenModalActionProvider implements ProviderInterface
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
    private $slackToken;

    /**
     * @Named("dialogFactory=dialogFactory, httpClient=httpClient, slackToken=slackToken")
     * @param DialogFactoryInterface $dialogFactory
     * @param Client $httpClient
     * @param string $slackToken
     */
    public function __construct(DialogFactoryInterface $dialogFactory, Client $httpClient, string $slackToken)
    {
        $this->dialogFactory = $dialogFactory;
        $this->httpClient = $httpClient;
        $this->slackToken = $slackToken;
    }


    public function get()
    {
        return new OpenModal($this->dialogFactory, $this->httpClient, $this->slackToken);
    }

}
