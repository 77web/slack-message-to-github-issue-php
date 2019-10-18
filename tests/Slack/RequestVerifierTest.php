<?php


namespace Quartetcom\SlackToGithubIssue\Slack;


use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

class RequestVerifierTest extends TestCase
{
    public function test()
    {
        $verifier = new RequestVerifier('test');
        $verifier->verify($this->getRequest('v0=eccbb868bfb12a61eace17dada510e742793a6614852bd734634cebeae401a00'));

        $this->assertTrue(true);
    }

    public function test_invalid()
    {
        $this->expectException(VerificationFailureException::class);

        $verifier = new RequestVerifier('test');
        $verifier->verify($this->getRequest('invalid'));
    }

    private function getRequest(string $signature): Request
    {
        return new Request([], [], [], [], [], [
            'HTTP_X_Slack_Signature' => $signature,
            'HTTP_X_Slack_Request_Timestamp' => '12345',
        ], 'dummy_key=dummy_value');
    }

}
