<?php


namespace Quartetcom\SlackToGithubIssue\Slack;


use Quartetcom\SlackToGithubIssue\Payload\Payload;

interface MessageFetcherInterface
{
    public function supports(Payload $payload): bool;

    public function fetch(Payload $payload): string;
}
