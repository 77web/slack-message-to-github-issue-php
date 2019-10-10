<?php


namespace Quartetcom\SlackToGithubIssue\DependencyInjection;


use Quartetcom\SlackToGithubIssue\Action\ActionInterface;
use Quartetcom\SlackToGithubIssue\ActionResolver;
use Ray\Di\Di\Named;
use Ray\Di\ProviderInterface;

class ActionResolverProvider implements ProviderInterface
{
    /**
     * @var ActionInterface
     */
    private $openModalAction;

    /**
     * @var ActionInterface
     */
    private $createIssueAction;

    /**
     * @Named("openModalAction=openModalAction, createIssueAction=createIssueAction")
     * @param ActionInterface $openModalAction
     * @param ActionInterface $createIssueAction
     */
    public function __construct(ActionInterface $openModalAction, ActionInterface $createIssueAction)
    {
        $this->openModalAction = $openModalAction;
        $this->createIssueAction = $createIssueAction;
    }


    public function get()
    {
        return new ActionResolver([
            $this->openModalAction,
            $this->createIssueAction,
        ]);
    }
}
