<?php


namespace Quartetcom\SlackToGithubIssue\Action;


use Github\Api\Issue;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Quartetcom\SlackToGithubIssue\Payload\Payload;

class CreateGithubIssueTest extends TestCase
{
    public function test_invoke()
    {
        $org = 'dummy';
        $repo = 'dummy-repo';
        $payload = new Payload('dummy', 'dummy-trigger', ['id' => 'dummy-channel'], '12345.678', [], ['issue_title' => 'xxx', 'issue_body' => 'yyy', 'slack_url' => 'http://dummy.slack/url']);

        $issueApiClientP = $this->prophesize(Issue::class);
        $issueApiClientP->create($org, $repo, Argument::that(function($issueInfo){
            $this->assertEquals('xxx', $issueInfo['title']);
            $this->assertNotEquals('', $issueInfo['body']);

            return true;
        }))->shouldBeCalled();

        $SUT = new CreateGithubIssue($issueApiClientP->reveal(), $org, $repo);
        $SUT->invoke($payload);
    }
}
