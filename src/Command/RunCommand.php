<?php

namespace App\Command;

use App\Model\TogglTimeEntry;
use App\Service\Jira\JiraWorkLogService;
use App\Service\TimeLogComparator;
use App\Service\Toggl\TogglFetchService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class RunCommand extends Command
{
    protected static $defaultName = 'app:run';

    /** @var TogglFetchService */
    protected $togglService;

    /** @var JiraWorkLogService */
    protected $workLogService;

    /** @var TimeLogComparator */
    protected $logComparator;

    public function __construct(TogglFetchService $togglService, JiraWorkLogService $workLogService, TimeLogComparator $logComparator)
    {
        parent::__construct();
        $this->togglService = $togglService;
        $this->workLogService = $workLogService;
        $this->logComparator = $logComparator;
    }

    protected function configure()
    {
        $this
            ->setDescription('Add a short description for your command')
            ->addArgument('startDate', InputArgument::REQUIRED, 'StartDate')
            ->addArgument('endDate', InputArgument::REQUIRED, 'StartDate')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $startDate = $input->getArgument('startDate');
        $endDate = $input->getArgument('endDate');

        $timeEntries = $this->togglService->getTimeEntries(
            new \DateTime($startDate),
            new \DateTime($endDate)
        );

        foreach ($timeEntries as $date => $issues) {
            /** @var TogglTimeEntry $entry */
            foreach ($issues as $issueId => $entry) {
                $io->title("{$date} - {$entry->getDescription()} ({$this->formatTime($entry->getDur())})");

                $log = $this->workLogService->getWorkLog($issueId, new \DateTime($date));

                $status = $this->logComparator->compare($entry, $log);

                if (TimeLogComparator::STATUS_SYNCED == $status) {
                    $io->comment('Already synced');
                } elseif (TimeLogComparator::STATUS_NEW == $status) {
                    $io->comment('New entry');
                    if ($io->confirm("Add entry for the {$issueId}?")) {
                        $this->workLogService->addWorkLog($entry, $issueId);
                    }
                } elseif (TimeLogComparator::STATUS_CHANGED == $status) {
                    $io->comment('Entity has changed, old duration: '.$this->formatTime($log->getDur()));
                    if ($io->confirm("Update the {$issueId}?")) {
                        $this->workLogService->editWorkLog($entry, $issueId, $log->getId());
                    }
                }
            }
        }
    }

    protected function formatTime(int $dur): string
    {
        $mins = $dur / 1000 / 60;
        $h = floor($mins / 60);
        $m = $mins - $h * 60;

        return "{$h}h {$m}m";
    }
}
