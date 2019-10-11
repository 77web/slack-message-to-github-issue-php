<?php


namespace Quartetcom\SlackToGithubIssue\Slack;


use Quartetcom\SlackToGithubIssue\Payload\Payload;

class PlainTextMessageFetcher implements MessageFetcherInterface
{
    public function supports(Payload $payload): bool
    {
        // this MessageFetcher should work as fallback
        return true;
    }

    public function fetch(Payload $payload): string
    {
        return $payload->getMessage()['text'];
    }
}
