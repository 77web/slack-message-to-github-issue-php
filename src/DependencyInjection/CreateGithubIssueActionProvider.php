<?php


namespace Quartetcom\SlackToGithubIssue\DependencyInjection;


use Github\Api\Issue;
use Github\Client;
use Quartetcom\SlackToGithubIssue\Action\CreateGithubIssue;
use Quartetcom\SlackToGithubIssue\Github\DescriptionBuilderInterface;
use Ray\Di\Di\Named;
use Ray\Di\ProviderInterface;

class CreateGithubIssueActionProvider implements ProviderInterface
{
    /**
     * @var DescriptionBuilderInterface
     */
    private $descriptionBuilder;

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
     * @Named("descriptionBuilder=descriptionBuilder,githubClient=githubClient,githubOrganization=githubOrganization,githubRepository=githubRepository")
     * @param DescriptionBuilderInterface $descriptionBuilder
     * @param Client $githubClient
     * @param string $githubOrganization
     * @param string $githubRepository
     */
    public function __construct(DescriptionBuilderInterface $descriptionBuilder, Client $githubClient, string $githubOrganization, string $githubRepository)
    {
        $this->descriptionBuilder = $descriptionBuilder;
        $this->githubClient = $githubClient;
        $this->githubOrganization = $githubOrganization;
        $this->githubRepository = $githubRepository;
    }


    public function get()
    {
        return new CreateGithubIssue($this->descriptionBuilder, new Issue($this->githubClient), $this->githubOrganization, $this->githubRepository);
    }

}
