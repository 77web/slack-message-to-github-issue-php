<?php


namespace Quartetcom\SlackToGithubIssue\Payload;


use PHPUnit\Framework\TestCase;

class PayloadTest extends TestCase
{
    public function test_getMessageUrl()
    {
        $payload = new Payload('dummy', 'dummy', ['id' => 'channel1'], '12345.67', [], []);
        $this->assertStringContainsString('channel1/p1234567', $payload->getMessageUrl());
    }
}
