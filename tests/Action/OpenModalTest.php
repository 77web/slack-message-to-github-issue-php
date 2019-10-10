<?php


namespace Quartetcom\SlackToGithubIssue\Action;


use GuzzleHttp\Client;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Quartetcom\SlackToGithubIssue\Payload\Payload;

class OpenModalTest extends TestCase
{
    public function test_invoke()
    {
        $slackToken = 'dummy-token';
        $payload = new Payload('dummy', 'dummy-trigger', ['id' => 'dummy-channel'], '12345.678', ['text' => 'dummy-message'], []);

        $httpClientP = $this->prophesize(Client::class);
        $httpClientP->post(Argument::type('string'), Argument::that(function($option){
            $this->assertEquals('application/json; charset=utf-8', $option['headers']['content-type']);
            $this->assertEquals('Bearer dummy-token', $option['headers']['authorization']);
            $this->assertTrue(is_string($option['body']));
            $this->assertNotEquals(false, $bodyData = json_decode($option['body'], true));
            $this->assertStringContainsString('dummy-trigger', $option['body']);

            return true;
        }))->shouldBeCalled();

        $SUT = new OpenModal($httpClientP->reveal(), $slackToken);
        $SUT->invoke($payload);
    }
}
