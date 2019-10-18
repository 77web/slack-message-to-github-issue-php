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
            $payload['submission'] ?? []
        );
    }

    private function getPayloadData(Request $request): array
    {
        $parsed = [];
        parse_str($request->getContent(), $parsed);

        return json_decode($parsed['payload'], true);
    }
}
