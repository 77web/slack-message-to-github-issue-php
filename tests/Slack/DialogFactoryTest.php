<?php

namespace Quartetcom\SlackToGithubIssue\Slack;

use PHPUnit\Framework\TestCase;
use Quartetcom\SlackToGithubIssue\Payload\Payload;

class DialogFactoryTest extends TestCase
{
    public function test()
    {
        $payload = new Payload('dummy', 'dummy-trigger', ['id' => 'dummy-channel'], '12345.678', [], []);

        $messageFetcherP = $this->prophesize(MessageFetcherInterface::class);
        $messageFetcherResolverP = $this->prophesize(MessageFetcherResolver::class);
        $messageUrlFactoryP = $this->prophesize(MessageUrlFactory::class);

        $messageFetcherP->fetch($payload)->willReturn('dummy-message')->shouldBeCalled();
        $messageFetcherResolverP->resolve($payload)->willReturn($messageFetcherP->reveal())->shouldBeCalled();
        $messageUrlFactoryP->create($payload)->willReturn('dummy-message-url')->shouldBeCalled();

        $SUT = new DialogFactory($messageFetcherResolverP->reveal(), $messageUrlFactoryP->reveal());
        $dialogData = $SUT->create($payload);

        $this->assertEquals('dummy-trigger', $dialogData['trigger_id']);
        $this->assertNotEmpty($dialogData['view']);
    }
}
