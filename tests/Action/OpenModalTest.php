<?php


namespace Quartetcom\SlackToGithubIssue\Action;


use GuzzleHttp\Client;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Quartetcom\SlackToGithubIssue\Payload\Payload;
use Quartetcom\SlackToGithubIssue\Slack\DialogFactoryInterface;
use Quartetcom\SlackToGithubIssue\Slack\MessageFetcherInterface;
use Quartetcom\SlackToGithubIssue\Slack\MessageFetcherResolver;
use Quartetcom\SlackToGithubIssue\Slack\MessageUrlFactory;

class OpenModalTest extends TestCase
{
    public function test_invoke()
    {
        $slackToken = 'dummy-token';
        $payload = new Payload('dummy', 'dummy-trigger', ['id' => 'dummy-channel'], '12345.678', [], []);

        $dialogFactoryP = $this->prophesize(DialogFactoryInterface::class);
        $httpClientP = $this->prophesize(Client::class);

        $dialogFactoryP->create($payload)->willReturn($dummyDialog = ['dummy-dialog-data' =>'dummy-dialog-data'])->shouldBeCalled();
        $httpClientP->post(Argument::containingString('views.open'), Argument::that(function($option) use ($dummyDialog){
            $this->assertEquals('application/json; charset=utf-8', $option['headers']['content-type']);
            $this->assertEquals('Bearer dummy-token', $option['headers']['authorization']);
            $this->assertTrue(is_string($option['body']));
            $this->assertNotEquals(false, $bodyData = json_decode($option['body'], true));
            $this->assertEquals($dummyDialog, $bodyData);

            return true;
        }))->shouldBeCalled();


        $SUT = new OpenModal($dialogFactoryP->reveal(), $httpClientP->reveal(), $slackToken);
        $SUT->invoke($payload);
    }
}
