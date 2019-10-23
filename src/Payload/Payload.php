<?php


namespace Quartetcom\SlackToGithubIssue\Payload;


class Payload
{
    private const MESSAGE_URL_FORMAT = 'https://quartetcom.slack.com/archives/%s/p%s%s';

    /**
     * @var string
     */
    private $type;

    /**
     * @var string
     */
    private $triggerId;

    /**
     * @var array
     */
    private $channel;

    /**
     * @var string
     */
    private $messageTimestamp;

    /**
     * @var array
     */
    private $message;

    /**
     * @var array
     */
    private $submission;

    /**
     * @param string $type
     * @param string $triggerId
     * @param array $channel
     * @param string $messageTimestamp
     * @param array $message
     * @param array $submission
     */
    public function __construct(
        string $type,
        string $triggerId,
        array $channel,
        string $messageTimestamp,
        array $message,
        array $submission
    ) {
        $this->type = $type;
        $this->triggerId = $triggerId;
        $this->channel = $channel;
        $this->messageTimestamp = $messageTimestamp;
        $this->message = $message;
        $this->submission = $submission;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function getTriggerId()
    {
        return $this->triggerId;
    }

    /**
     * @return array
     */
    public function getChannel()
    {
        return $this->channel;
    }

    /**
     * @return string
     */
    public function getMessageTimestamp()
    {
        return $this->messageTimestamp;
    }

    /**
     * @return array
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @return array
     */
    public function getSubmission()
    {
        return $this->submission;
    }
}
