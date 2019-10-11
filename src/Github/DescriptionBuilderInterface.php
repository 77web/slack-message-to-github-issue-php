<?php


namespace Quartetcom\SlackToGithubIssue\Github;


use Quartetcom\SlackToGithubIssue\Payload\Payload;

interface DescriptionBuilderInterface
{
    public function build(Payload $payload): string;
}
