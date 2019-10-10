<?php


namespace Quartetcom\SlackToGithubIssue;


use Quartetcom\SlackToGithubIssue\Payload\PayloadFactory;
use Symfony\Component\HttpFoundation\Request;

class App
{
    /**
     * @var PayloadFactory
     */
    private $payloadFactory;

    /**
     * @var ActionResolver
     */
    private $actionResolver;

    /**
     * @param PayloadFactory $payloadFactory
     * @param ActionResolver $actionResolver
     */
    public function __construct(PayloadFactory $payloadFactory, ActionResolver $actionResolver)
    {
        $this->payloadFactory = $payloadFactory;
        $this->actionResolver = $actionResolver;
    }

    public function run(Request $request = null)
    {
        $request = $request ?? Request::createFromGlobals();
        $payload = $this->payloadFactory->create($request);

        $action = $this->actionResolver->resolve($payload);
        $action->invoke($payload);
    }
}
