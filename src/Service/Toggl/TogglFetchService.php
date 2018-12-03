<?php

namespace App\Service\Toggl;

use App\Model\TogglTimeEntry;
use GuzzleHttp\Client;
use Psr\Http\Message\ResponseInterface;

class TogglFetchService
{
    const BASE_URI = 'https://toggl.com/reports/api/v2/';

    /** @var TogglTimeTransformer */
    protected $transformer;
    /** @var string */
    protected $workspaceId;
    /** @var string */
    protected $projectId;
    /** @var Client */
    protected $client;

    public function __construct(TogglTimeTransformer $transformer, string $apiKey, string $projectId, string $workspaceId)
    {
        $this->transformer = $transformer;

        $this->workspaceId = $workspaceId;
        $this->projectId = $projectId;

        $this->client = new Client([
            'base_uri' => self::BASE_URI,
            'auth' => [
                $apiKey,
                'api_token',
            ],
        ]);
    }

    /**
     * @param \DateTime $start
     * @param \DateTime $end
     *
     * @return TogglTimeEntry[][]
     */
    public function getTimeEntries(\DateTime $start, \DateTime $end): array
    {
        $total = 99999;
        $page = 1;
        $results = [];

        while (count($results) < $total) {
            $response = $this->getEntry($start, $end, $page);
            $data = json_decode($response->getBody()->getContents(), true);
            $results = array_merge($results, $data['data']);
            $total = $data['total_count'];

            ++$page;
        }

        $results = array_map(function (array $entry) {
            return $this->transformer->transform($entry);
        }, $results);

        return TogglTimeJoiner::joinTime($results);
    }

    protected function getEntry(\DateTime $start, \DateTime $end, int $page): ResponseInterface
    {
        return $this->client->get('details', [
            'query' => [
                'since' => $start->format(DATE_ATOM),
                'until' => $end->format(DATE_ATOM),
                'workspace_id' => $this->workspaceId,
                'project_ids' => $this->projectId,
                'user_agent' => 'xor29a@bk.ru',
                'order_desc' => 'off',
                'page' => $page,
            ],
        ]);
    }
}
