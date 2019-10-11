<?php


namespace Quartetcom\SlackToGithubIssue\Action;


use Github\Api\Issue;
use Quartetcom\SlackToGithubIssue\Github\DescriptionBuilderInterface;
use Quartetcom\SlackToGithubIssue\Payload\Payload;
use Quartetcom\SlackToGithubIssue\Payload\PayloadTypes;

class CreateGithubIssue implements ActionInterface
{
    /**
     * @var DescriptionBuilderInterface
     */
    private $descriptionBuilder;

    /**
     * @var Issue
     */
    private $githubIssuesApi;

    /**
     * @var string
     */
    private $githubOrganization;

    /**
     * @var string
     */
    private $githubRepository;

    /**
     * @param DescriptionBuilderInterface $descriptionBuilder
     * @param Issue $githubIssuesApi
     * @param string $githubOrganization
     * @param string $githubRepository
     */
    public function __construct(DescriptionBuilderInterface $descriptionBuilder, Issue $githubIssuesApi, string $githubOrganization, string $githubRepository)
    {
        $this->descriptionBuilder = $descriptionBuilder;
        $this->githubIssuesApi = $githubIssuesApi;
        $this->githubOrganization = $githubOrganization;
        $this->githubRepository = $githubRepository;
    }

    public function supports(Payload $payload): bool
    {
        return $payload->getType() === PayloadTypes::TYPE_SUBMISSION;
    }

    public function invoke(Payload $payload): void
    {
        // gather info to create issue
        $title = $payload->getSubmission()['issue_title'];
        $description = $this->descriptionBuilder->build($payload);

        $this->githubIssuesApi->create($this->githubOrganization, $this->githubRepository, [
            'title' => $title,
            'body' => $description,
        ]);
    }

}
