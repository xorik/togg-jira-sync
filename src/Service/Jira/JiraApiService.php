<?php

namespace App\Service\Jira;

use JiraRestApi\Issue\IssueService;
use JiraRestApi\Issue\Worklog;

class JiraApiService
{
    /** @var IssueService */
    protected $api;

    public function __construct()
    {
        $this->api = new IssueService();
    }

    /**
     * @param string $issueId
     *
     * @return Worklog[]
     */
    public function getWorkLog(string $issueId): array
    {
        $results = [];

        $logs = $this->api->getWorklog($issueId);
        foreach ($logs->getWorklogs() as $worklog) {
            if ($worklog->author['name'] === $_ENV['JIRA_USER']) {
                $results[] = $worklog;
            }
        }

        return $results;
    }

    public function addWorkLog(string $issueId, Worklog $worklog): void
    {
        $this->api->addWorklog($issueId, $worklog);
    }

    public function editWorkLog(string $issueId, Worklog $worklog, int $worklogId): void
    {
        $this->api->editWorklog($issueId, $worklog, $worklogId);
    }
}
