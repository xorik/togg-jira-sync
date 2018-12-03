<?php

namespace App\Service\Jira;

class JiraIssueIdParser
{
    const DEFAULT_ID = 'WPAP-1';

    public static function parse(string $title): string
    {
        preg_match('/^([A-Z]+-\d+)/', $title, $m);

        return $m[1] ?? self::DEFAULT_ID;
    }
}
