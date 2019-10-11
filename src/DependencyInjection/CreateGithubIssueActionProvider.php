<?php


namespace Quartetcom\SlackToGithubIssue\DependencyInjection;


use Github\Api\Issue;
use Github\Client;
use Quartetcom\SlackToGithubIssue\Action\CreateGithubIssue;
use Ray\Di\Di\Named;
use Ray\Di\ProviderInterface;

class CreateGithubIssueActionProvider implements ProviderInterface
{
    /**
     * @var Client
     */
    private $githubClient;

    /**
     * @var string
     */
    private $githubOrganization;

    /**
     * @var string
     */
    private $githubRepository;

    /**
     * @Named("githubClient=githubClient,githubOrganization=githubOrganization,githubRepository=githubRepository")
     * @param Client $githubClient
     * @param string $githubOrganization
     * @param string $githubRepository
     */
    public function __construct(Client $githubClient, string $githubOrganization, string $githubRepository)
    {
        $this->githubClient = $githubClient;
        $this->githubOrganization = $githubOrganization;
        $this->githubRepository = $githubRepository;
    }


    public function get()
    {
        return new CreateGithubIssue(new Issue($this->githubClient), $this->githubOrganization, $this->githubRepository);
    }

}
