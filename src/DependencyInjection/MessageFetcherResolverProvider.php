<?php


namespace Quartetcom\SlackToGithubIssue\DependencyInjection;


use Quartetcom\SlackToGithubIssue\Slack\MessageFetcherResolver;
use Quartetcom\SlackToGithubIssue\Slack\PlainTextMessageFetcher;
use Ray\Di\ProviderInterface;

class MessageFetcherResolverProvider implements ProviderInterface
{
    public function get()
    {
        return new MessageFetcherResolver([
            new PlainTextMessageFetcher(),
        ]);
    }
}
