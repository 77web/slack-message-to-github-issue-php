<?php


namespace Quartetcom\SlackToGithubIssue;


use Quartetcom\SlackToGithubIssue\Action\ActionInterface;
use Quartetcom\SlackToGithubIssue\Payload\Payload;

class ActionResolver
{
    /**
     * @var ActionInterface[]
     */
    private $actions;

    public function __construct(array $actions)
    {
        $this->actions = $actions;
    }

    public function resolve(Payload $payload): ActionInterface
    {
        foreach ($this->actions as $action) {
            if ($action->supports($payload)) {
                return $action;
            }
        }

        throw new \LogicException('No supported action defined.');
    }
}
