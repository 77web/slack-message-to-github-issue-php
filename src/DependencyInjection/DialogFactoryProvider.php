<?php


namespace Quartetcom\SlackToGithubIssue\DependencyInjection;


use Quartetcom\SlackToGithubIssue\Slack\DialogFactory;
use Quartetcom\SlackToGithubIssue\Slack\MessageFetcherResolver;
use Quartetcom\SlackToGithubIssue\Slack\MessageUrlFactory;
use Ray\Di\Di\Named;
use Ray\Di\ProviderInterface;

class DialogFactoryProvider implements ProviderInterface
{
    /**
     * @var MessageFetcherResolver
     */
    private $messageFetcherResolver;

    /**
     * @var string
     */
    private $slackWorkspace;

    /**
     * @Named("messageFetcherResolver=messageFetcherResolver, slackWorkspace=slackWorkspace")
     * @param MessageFetcherResolver $messageFetcherResolver
     * @param string $slackWorkspace
     */
    public function __construct(MessageFetcherResolver $messageFetcherResolver, string $slackWorkspace)
    {
        $this->messageFetcherResolver = $messageFetcherResolver;
        $this->slackWorkspace = $slackWorkspace;
    }

    public function get()
    {
        return new DialogFactory($this->messageFetcherResolver, new MessageUrlFactory($this->slackWorkspace));
    }
}
