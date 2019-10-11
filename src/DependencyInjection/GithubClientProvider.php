<?php


namespace Quartetcom\SlackToGithubIssue\DependencyInjection;


use Github\Client;
use Ray\Di\Di\Named;
use Ray\Di\ProviderInterface;

class GithubClientProvider implements ProviderInterface
{
    /**
     * @var string
     */
    private $githubToken;

    /**
     * @Named("githubToken=githubToken")
     * @param string $githubToken
     */
    public function __construct(string $githubToken)
    {
        $this->githubToken = $githubToken;
    }


    public function get()
    {
        $client = new Client();
        $client->authenticate(Client::AUTH_HTTP_TOKEN, $this->githubToken);

        return $client;
    }
}
