<?php

namespace App\Service\Toggl;

use App\Model\TogglTimeEntry;
use App\Service\Jira\JiraIssueIdParser;

class TogglTimeJoiner
{
    const ROUND_TIME = 15 * 60 * 1000; // 15 minutes in ms

    /**
     * @param TogglTimeEntry[] $entries
     *
     * @return TogglTimeEntry[][]
     */
    public static function joinTime(array $entries): array
    {
        $entryGroups = self::groupEntries($entries);

        $results = [];

        foreach ($entryGroups as $date => $issues) {
            foreach ($issues as $issueId => $entries) {
                $firstEntry = clone $entries[0];
                $results[$date][$issueId] = $firstEntry;

                $totalDur = self::totalDur($entries);
                $totalRounded = self::round($totalDur);

                $firstEntry->setDur($totalRounded);
            }
        }

        return $results;
    }

    protected static function groupEntries(array $entries): array
    {
        $entryGroups = [];

        foreach ($entries as $entry) {
            $date = $entry->getStart()->format('Y-m-d');
            $taskId = JiraIssueIdParser::parse($entry->getDescription());

            if (!isset($entryGroups[$date])) {
                $entryGroups[$date] = [];
            }

            if (!isset($entryGroups[$date][$taskId])) {
                $entryGroups[$date][$taskId] = [];
            }

            $entryGroups[$date][$taskId][] = $entry;
        }

        return $entryGroups;
    }

    protected static function totalDur(array $entries): int
    {
        $total = 0;

        /** @var TogglTimeEntry $entry */
        foreach ($entries as $entry) {
            $total += $entry->getDur();
        }

        return $total;
    }

    protected static function round(int $total): int
    {
        $count = round($total / self::ROUND_TIME);

        return ($count > 0) ? $count * self::ROUND_TIME : 1;
    }
}
