<?php

use Dotenv\Dotenv;
use Github\Client as GithubClient;
use Github\Api\Issue as GithubIssueClient;
use GuzzleHttp\Client as HttpClient;
use Quartetcom\SlackToGithubIssue\ActionResolver;
use Quartetcom\SlackToGithubIssue\Action\CreateGithubIssue;
use Quartetcom\SlackToGithubIssue\Action\OpenModal;
use Quartetcom\SlackToGithubIssue\App;
use Quartetcom\SlackToGithubIssue\Payload\PayloadFactory;

require __DIR__.'/../vendor/autoload.php';

if (file_exists(__DIR__.'/../.env')) {
    Dotenv::create(__DIR__ . '/../')->load();
}

$httpClient = new HttpClient();
$githubClient = new GithubClient();
$githubClient->authenticate('http_token', getenv('GITHUB_OAUTH_TOKEN'));

$app = new App(new PayloadFactory(), new ActionResolver([
    new OpenModal($httpClient, getenv('SLACK_OAUTH_TOKEN')),
    new CreateGithubIssue(new GithubIssueClient($githubClient), getenv('GITHUB_ORG'), getenv('GITHUB_REPOSITORY')),
]));
$app->run();

