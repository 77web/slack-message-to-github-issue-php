<?php


namespace Quartetcom\SlackToGithubIssue\Action;


use Quartetcom\SlackToGithubIssue\Payload\Payload;

interface ActionInterface
{
    public function supports(Payload $payload): bool;

    public function invoke(Payload $payload): void;
}
