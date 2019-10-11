<?php


namespace Quartetcom\SlackToGithubIssue\Action;


use Github\Api\Issue;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Quartetcom\SlackToGithubIssue\Github\DescriptionBuilderInterface;
use Quartetcom\SlackToGithubIssue\Payload\Payload;

class CreateGithubIssueTest extends TestCase
{
    public function test_invoke()
    {
        $org = 'dummy';
        $repo = 'dummy-repo';
        $payload = new Payload('dummy', 'dummy-trigger', ['id' => 'dummy-channel'], '12345.678', [], ['issue_title' => 'xxx']);

        $issueApiClientP = $this->prophesize(Issue::class);
        $descBuilderP = $this->prophesize(DescriptionBuilderInterface::class);

        $descBuilderP->build($payload)->willReturn('dummy-body')->shouldBeCalled();
        $issueApiClientP->create($org, $repo, Argument::that(function($issueInfo){
            $this->assertEquals('xxx', $issueInfo['title']);
            $this->assertEquals('dummy-body', $issueInfo['body']);

            return true;
        }))->shouldBeCalled();

        $SUT = new CreateGithubIssue($descBuilderP->reveal(), $issueApiClientP->reveal(), $org, $repo);
        $SUT->invoke($payload);
    }
}
