<?php


namespace Quartetcom\SlackToGithubIssue\Action;


use Github\Api\Issue;
use Quartetcom\SlackToGithubIssue\Payload\Payload;
use Quartetcom\SlackToGithubIssue\Payload\PayloadTypes;

class CreateGithubIssue implements ActionInterface
{
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
     * @param Issue $githubIssuesApi
     * @param string $githubOrganization
     * @param string $githubRepository
     */
    public function __construct(Issue $githubIssuesApi, string $githubOrganization, string $githubRepository)
    {
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
        $submission = $payload->getSubmission();
        // generate title & desc
        $title = $submission['issue_title'];
        $description = implode("\n", [$submission['issue_body'], $submission['slack_url']]);

        $this->githubIssuesApi->create($this->githubOrganization, $this->githubRepository, [
            'title' => $title,
            'body' => $description,
        ]);
    }

}
