<?php

namespace App\Command;

use App\ApiDataLoader\ApiDataLoader;
use App\ApiDataLoader\Loader\ApiFeiertage;
use App\ApiDataLoader\Loader\MehrSchulferienApi;
use App\Repository\HolidaysRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class CalendarFetchHolidaysCommand extends Command
{
    protected static string $defaultName = 'calendar:fetch:holidays';

    public function __construct(
        private readonly HolidaysRepository $holidayRepo,
        private readonly ApiDataLoader $apiCrawler
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Fetches holidays/vacations from different API\'s to store in local file')
            ->addArgument('holidayTypes', InputArgument::REQUIRED, 'Which type to fetch - [public, school]')
            ->addOption('year', 'y', InputArgument::OPTIONAL, 'The year to be fetched - default "this" year');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $holidayTypes = [];
        if ($input->hasArgument('holidayTypes')) {
            $holidayTypes = explode(',', (string) $input->getArgument('holidayTypes'));
        }

        $years = ! empty($input->getOption('year')) ?
            explode(',', (string) $input->getOption('year')) :
            [date('Y')];

        if (in_array('public', $holidayTypes)) {
            $result = [];
            foreach ($years as $year) {
                $result[] = array_merge($this->apiCrawler->fetchData(ApiFeiertage::LOADER_TYPE, $year), $result);
            }
            $this->holidayRepo->savePublicHolidays(array_merge(...$result));
            $io->success('Successfully loaded data from https://deutsche-feiertage-api.de');
        }

        if (in_array('school', $holidayTypes)) {
            $result = [];
            foreach ($years as $year) {
                $result[] = $this->apiCrawler->fetchData(MehrSchulferienApi::LOADER_TYPE, $year);
            }
            $this->holidayRepo->saveSchoolHolidays(array_merge(...$result));
            $io->success('Successfully loaded data from https://mehr-schulferien.de');
        }

        return 0;
    }
}
