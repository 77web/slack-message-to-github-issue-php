<?php


namespace Quartetcom\SlackToGithubIssue\DependencyInjection;

use Github\Client as GithubClient;
use GuzzleHttp\Client as HttpClient;
use Quartetcom\SlackToGithubIssue\Action\ActionInterface;
use Quartetcom\SlackToGithubIssue\Action\CreateGithubIssue;
use Quartetcom\SlackToGithubIssue\Action\OpenModal;
use Quartetcom\SlackToGithubIssue\ActionResolver;
use Quartetcom\SlackToGithubIssue\Github\DescriptionBuilderInterface;
use Quartetcom\SlackToGithubIssue\Github\PlainDescriptionBuilder;
use Quartetcom\SlackToGithubIssue\Slack\MessageFetcherResolver;
use Ray\Di\AbstractModule;

class SlackToGithubIssueModule extends AbstractModule
{
    /**
     * @var string
     */
    private $slackToken;

    /**
     * @var string
     */
    private $githubToken;

    /**
     * @var string
     */
    private $githubOrganization;

    /**
     * @var string
     */
    private $githubRepository;

    /**
     * @param string $slackToken
     * @param string $githubToken
     * @param string $githubOrganization
     * @param string $githubRepository
     */
    public function __construct(
        string $slackToken,
        string $githubToken,
        string $githubOrganization,
        string $githubRepository
    ) {
        $this->slackToken = $slackToken;
        $this->githubToken = $githubToken;
        $this->githubOrganization = $githubOrganization;
        $this->githubRepository = $githubRepository;

        parent::__construct();
    }

    protected function configure()
    {
        // Slack
        $this->bind(HttpClient::class)->annotatedWith('httpClient')->to(HttpClient::class);
        $this->bind()->annotatedWith('slackToken')->toInstance($this->slackToken);

        // Github
        $this->bind(GithubClient::class)->annotatedWith('githubClient')->toProvider(GithubClientProvider::class);
        $this->bind()->annotatedWith('githubToken')->toInstance($this->githubToken);
        $this->bind()->annotatedWith('githubOrganization')->toInstance($this->githubOrganization);
        $this->bind()->annotatedWith('githubRepository')->toInstance($this->githubRepository);

        // ActionResolver
        $this->bind(ActionResolver::class)->toProvider(ActionResolverProvider::class);
        $this->bind(ActionInterface::class)->annotatedWith('openModalAction')->toProvider(OpenModalActionProvider::class);
        $this->bind(ActionInterface::class)->annotatedWith('createIssueAction')->toProvider(CreateGithubIssueActionProvider::class);

        // MessageFetcherResolver
        $this->bind(MessageFetcherResolver::class)->annotatedWith('messageFetcherResolver')->toProvider(MessageFetcherResolverProvider::class);

        // DescriptionBuilder
        $this->bind(DescriptionBuilderInterface::class)->annotatedWith('descriptionBuilder')->to(PlainDescriptionBuilder::class);

    }

}
