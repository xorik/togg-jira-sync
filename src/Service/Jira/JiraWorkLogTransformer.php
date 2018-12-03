<?php

namespace App\Service\Jira;

use App\Model\JiraWorkLog;
use App\Model\TogglTimeEntry;
use JiraRestApi\Issue\Worklog;

class JiraWorkLogTransformer
{
    public static function transformToModel(Worklog $worklog, string $issueId): JiraWorkLog
    {
        $model = new JiraWorkLog();

        $model->setId($worklog->id);
        $model->setIssueId($issueId);
        $model->setDescription($worklog->comment);
        $model->setDate(new \DateTime($worklog->started));
        $model->setDur($worklog->timeSpentSeconds * 1000);

        return $model;
    }

    public static function transformFromModel(JiraWorkLog $model): Worklog
    {
        $worklog = new Worklog();

        $mins = round($model->getDur() / 1000 / 60);

        $worklog
            ->setComment($model->getDescription())
            ->setStarted($model->getDate())
            ->setTimeSpent($mins.'m')
        ;

        return $worklog;
    }

    public static function transformFromTimeEntry(TogglTimeEntry $entry): JiraWorkLog
    {
        $model = new JiraWorkLog();

        $model->setDur($entry->getDur());
        $model->setDate($entry->getStart());
        $model->setDescription($entry->getDescription());

        return $model;
    }
}
