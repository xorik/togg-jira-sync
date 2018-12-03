<?php

namespace App\Service;

use App\Model\JiraWorkLog;
use App\Model\TogglTimeEntry;

class TimeLogComparator
{
    const STATUS_NEW = 1;
    const STATUS_CHANGED = 2;
    const STATUS_SYNCED = 3;

    public function compare(TogglTimeEntry $entry, ?JiraWorkLog $log): int
    {
        if (null === $log) {
            return self::STATUS_NEW;
        }

        if ($entry->getDur() !== $log->getDur()) {
            return self::STATUS_CHANGED;
        }

        return self::STATUS_SYNCED;
    }
}
