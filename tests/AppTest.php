<?php


namespace Quartetcom\SlackToGithubIssue;


use PHPUnit\Framework\TestCase;
use Quartetcom\SlackToGithubIssue\Action\ActionInterface;
use Quartetcom\SlackToGithubIssue\Payload\Payload;
use Quartetcom\SlackToGithubIssue\Payload\PayloadFactory;
use Symfony\Component\HttpFoundation\Request;

class AppTest extends TestCase
{
    public function test()
    {
        $req = $this->prophesize(Request::class)->reveal();
        $actionResolverP = $this->prophesize(ActionResolver::class);
        $actionP = $this->prophesize(ActionInterface::class);
        $payloadFactoryP = $this->prophesize(PayloadFactory::class);
        $payload = $this->prophesize(Payload::class)->reveal();

        $payloadFactoryP->create($req)->willReturn($payload)->shouldBeCalled();
        $actionP->invoke($payload)->shouldBeCalled();
        $actionResolverP->resolve($payload)->willReturn($actionP->reveal())->shouldBeCalled();

        $SUT = new App($payloadFactoryP->reveal(), $actionResolverP->reveal());
        $SUT->run($req);
    }
}
