<?php


namespace Quartetcom\SlackToGithubIssue\Action;


use GuzzleHttp\Client;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Quartetcom\SlackToGithubIssue\Payload\Payload;
use Quartetcom\SlackToGithubIssue\Slack\MessageFetcherInterface;
use Quartetcom\SlackToGithubIssue\Slack\MessageFetcherResolver;
use Quartetcom\SlackToGithubIssue\Slack\MessageUrlFactory;

class OpenModalTest extends TestCase
{
    public function test_invoke()
    {
        $slackToken = 'dummy-token';
        $payload = new Payload('dummy', 'dummy-trigger', ['id' => 'dummy-channel'], '12345.678', [], []);

        $httpClientP = $this->prophesize(Client::class);
        $messageFetcherP = $this->prophesize(MessageFetcherInterface::class);
        $messageFetcherResolverP = $this->prophesize(MessageFetcherResolver::class);
        $messageUrlFactoryP = $this->prophesize(MessageUrlFactory::class);

        $httpClientP->post(Argument::type('string'), Argument::that(function($option){
            $this->assertEquals('application/json; charset=utf-8', $option['headers']['content-type']);
            $this->assertEquals('Bearer dummy-token', $option['headers']['authorization']);
            $this->assertTrue(is_string($option['body']));
            $this->assertNotEquals(false, $bodyData = json_decode($option['body'], true));
            $this->assertStringContainsString('dummy-trigger', $option['body']);
            $this->assertStringContainsString('dummy-message-url', $option['body']);

            return true;
        }))->shouldBeCalled();
        $messageFetcherP->fetch($payload)->willReturn('dummy-message')->shouldBeCalled();
        $messageFetcherResolverP->resolve($payload)->willReturn($messageFetcherP->reveal())->shouldBeCalled();
        $messageUrlFactoryP->create($payload)->willReturn('dummy-message-url')->shouldBeCalled();

        $SUT = new OpenModal($messageFetcherResolverP->reveal(), $messageUrlFactoryP->reveal(), $httpClientP->reveal(), $slackToken);
        $SUT->invoke($payload);
    }
}
