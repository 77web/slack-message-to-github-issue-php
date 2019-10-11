<?php


namespace Quartetcom\SlackToGithubIssue\Github;


use Quartetcom\SlackToGithubIssue\Payload\Payload;

class PlainDescriptionBuilder implements DescriptionBuilderInterface
{
    public function build(Payload $payload): string
    {
        $submission = $payload->getSubmission();

        return implode("\n", [$submission['issue_body'], $submission['slack_url']]);
    }

}
