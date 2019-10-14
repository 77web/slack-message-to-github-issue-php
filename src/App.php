<?php


namespace Quartetcom\SlackToGithubIssue;


use Quartetcom\SlackToGithubIssue\Payload\PayloadFactory;
use Quartetcom\SlackToGithubIssue\Slack\RequestVerifier;
use Symfony\Component\HttpFoundation\Request;

class App
{
    /**
     * @var RequestVerifier
     */
    private $requestVerifier;

    /**
     * @var PayloadFactory
     */
    private $payloadFactory;

    /**
     * @var ActionResolver
     */
    private $actionResolver;

    /**
     * @param RequestVerifier $requestVerifier
     * @param PayloadFactory $payloadFactory
     * @param ActionResolver $actionResolver
     */
    public function __construct(RequestVerifier $requestVerifier, PayloadFactory $payloadFactory, ActionResolver $actionResolver)
    {
        $this->requestVerifier = $requestVerifier;
        $this->payloadFactory = $payloadFactory;
        $this->actionResolver = $actionResolver;
    }

    /**
     * @param Request|null $request
     * @throws Slack\VerificationFailureException
     */
    public function run(Request $request = null)
    {
        $request = $request ?? Request::createFromGlobals();
        $this->requestVerifier->verify($request);
        $payload = $this->payloadFactory->create($request);

        $action = $this->actionResolver->resolve($payload);
        $action->invoke($payload);
    }
}
