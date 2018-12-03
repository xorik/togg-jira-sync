<?php

namespace App\Service\Jira;

use App\Model\JiraWorkLog;
use App\Model\TogglTimeEntry;
use JiraRestApi\Issue\Worklog;

class JiraWorkLogService
{
    /** @var JiraApiService */
    protected $api;

    public function __construct(JiraApiService $api)
    {
        $this->api = $api;
    }

    public function getWorkLog(string $issueId, \DateTime $date): ?JiraWorkLog
    {
        // TODO: cache
        $worklog = $this->api->getWorkLog($issueId);

        $date->setTimezone(new \DateTimeZone('UTC'));
        $dateString = $date->format('Y-m-d');

        foreach ($worklog as $entry) {
            $model = JiraWorkLogTransformer::transformToModel($entry, $issueId);

            if ($model->getDate()->format('Y-m-d') == $dateString) {
                return $model;
            }
        }

        return null;
    }

    public function addWorkLog(TogglTimeEntry $entry, string $issueId): void
    {
        $worklog = $this->workLogFromTimeEntry($entry);
        $this->api->addWorkLog($issueId, $worklog);
    }

    public function editWorkLog(TogglTimeEntry $entry, string $issueId, int $workLogId): void
    {
        $worklog = $this->workLogFromTimeEntry($entry);
        $this->api->editWorkLog($issueId, $worklog, $workLogId);
    }

    protected function workLogFromTimeEntry(TogglTimeEntry $entry): Worklog
    {
        $log = JiraWorkLogTransformer::transformFromTimeEntry($entry);

        return JiraWorkLogTransformer::transformFromModel($log);
    }
}
