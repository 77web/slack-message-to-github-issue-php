<?php


namespace Quartetcom\SlackToGithubIssue\Slack;


use Quartetcom\SlackToGithubIssue\Payload\Payload;

class MessageUrlFactory
{
    private const URL_FORMAT = 'https://%s.slack.com/archives/%s/p%s%s';

    /**
     * @var string
     */
    private $workspace;

    /**
     * @param string $workspace
     */
    public function __construct(string $workspace)
    {
        $this->workspace = $workspace;
    }

    public function create(Payload $payload): string
    {
        list($ts1, $ts2) = explode('.', $payload->getMessageTimestamp());

        return sprintf(self::URL_FORMAT, $this->workspace, $payload->getChannel()['id'], $ts1, $ts2);
    }
}
