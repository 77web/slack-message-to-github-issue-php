<?php


namespace Quartetcom\SlackToGithubIssue\Slack;


use Symfony\Component\HttpFoundation\Request;

class RequestVerifier
{
    /**
     * @var string
     */
    private $signSecret;

    /**
     * @param string $signSecret
     */
    public function __construct(string $signSecret)
    {
        $this->signSecret = $signSecret;
    }

    /**
     * @param Request $request
     * @throws VerificationFailureException
     */
    public function verify(Request $request)
    {
        $actualSignature = $request->headers->get('X-Slack-Signature');
        if ($this->computeExpectedSignature($request) !== $actualSignature) {
            throw new VerificationFailureException();
        }
    }

    private function computeExpectedSignature(Request $request)
    {
        $requestTimestamp = $request->headers->get('X-Slack-Request-Timestamp');
        $value = sprintf('v0:%s:%s', $requestTimestamp, $request->getContent());

        return 'v0='. hash_hmac('sha256', $value, $this->signSecret);
    }


}
