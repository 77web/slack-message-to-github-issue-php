<?php


namespace Quartetcom\SlackToGithubIssue\Functional\DependencyInjection;


use PHPUnit\Framework\TestCase;
use Quartetcom\SlackToGithubIssue\App;
use Quartetcom\SlackToGithubIssue\DependencyInjection\SlackToGithubIssueModule;
use Ray\Di\Injector;

class SlackToGithubIssueModuleTest extends TestCase
{
    public function test()
    {
        $injector = new Injector(new SlackToGithubIssueModule('dummy-slack-token', 'dummy-slack-key', 'dummy-workspace', 'dummy-github-token', 'github-org', 'github-repo'));
        $this->assertInstanceof(App::class, $injector->getInstance(App::class));
    }
}
