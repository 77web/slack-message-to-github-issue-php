<?php

namespace Quartetcom\SlackToGithubIssue\Slack;

use PHPUnit\Framework\TestCase;
use Quartetcom\SlackToGithubIssue\Payload\Payload;

class MessageUrlFactoryTest extends TestCase
{
    public function test_create()
    {
        $SUT = new MessageUrlFactory('dummy-ws');
        $payload = new Payload('dummy', 'dummy', ['id' => 'channel1'], '12345.67', [], []);

        $actual = $SUT->create($payload);
        $this->assertStringContainsString('dummy-ws.slack.com', $actual);
        $this->assertStringContainsString('channel1/p1234567', $actual);
    }
}
