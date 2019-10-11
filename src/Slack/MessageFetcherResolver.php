<?php


namespace Quartetcom\SlackToGithubIssue\Slack;


use Quartetcom\SlackToGithubIssue\Payload\Payload;

class MessageFetcherResolver
{
    /**
     * @var MessageFetcherInterface[]
     */
    private $fetchers;

    /**
     * @param MessageFetcherInterface[] $fetchers
     */
    public function __construct(array $fetchers)
    {
        $this->fetchers = $fetchers;
    }

    public function resolve(Payload $payload): MessageFetcherInterface
    {
        foreach ($this->fetchers as $fetcher) {
            if ($fetcher->supports($payload)) {
                return $fetcher;
            }
        }

        throw new \LogicException('No MessageFetcher found.');
    }
}
