<?php


namespace Quartetcom\SlackToGithubIssue\Slack;


use Quartetcom\SlackToGithubIssue\Payload\Payload;

interface DialogFactoryInterface
{
    public function create(Payload $payload): array;
}
