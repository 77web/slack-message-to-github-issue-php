<?php


namespace Quartetcom\SlackToGithubIssue\Payload;


use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

class PayloadFactoryTest extends TestCase
{
    public function test_create()
    {
        $req = $this->prophesize(Request::class);
        $req->getContent()->willReturn(http_build_query(['payload' => json_encode([
            'type' => 'dummy-type',
            'channel' => ['id' => 'dummy-channel'],
            'message' => ['text' => 'dummy-message'],
            'trigger_id' => 'dummy-trigger-id',
            'message_ts' => '12345.66',
        ])]))->shouldBeCalled();

        $SUT = new PayloadFactory();
        $actual = $SUT->create($req->reveal());

        $this->assertEquals('dummy-type', $actual->getType());
        $this->assertEquals(['id' => 'dummy-channel'], $actual->getChannel());
    }
}
