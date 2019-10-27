<?php


namespace Quartetcom\SlackToGithubIssue\Payload;


use Symfony\Component\HttpFoundation\Request;

class PayloadFactory
{
    public function create(Request $request): Payload
    {
        $payload = $this->getPayloadData($request);

        return new Payload(
            $payload['type'],
            $payload['trigger_id'] ?? '',
            $payload['channel'] ?? [],
            $payload['message_ts'] ?? '',
            $payload['message'] ?? [],
            $this->getSubmissionData($payload)
        );
    }

    private function getPayloadData(Request $request): array
    {
        $parsed = [];
        parse_str($request->getContent(), $parsed);

        return json_decode($parsed['payload'], true);
    }

    private function getSubmissionData(array $payload): array
    {
        if (empty($payload['view']['state']['values'])) {
            return [];
        }

        $submission = [];
        foreach ($payload['view']['state']['values'] as $valueData) {
            $name = array_key_first($valueData);
            $value = reset($valueData)['value'];

            $submission[$name] = $value;
        }

        return $submission;


    }
}
