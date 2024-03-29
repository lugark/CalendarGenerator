<?php

namespace App\Command;

use App\Calendar\Events;
use App\Renderer\EventRenderer;
use App\Renderer\LandscapeYear;
use App\Renderer\RenderRequest;
use App\Renderer\RenderRequest\RequestTypes;
use App\Repository\HolidaysRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class CalendarGenerateCommand extends Command
{
    protected static $defaultName = 'calendar:generate';

    /** @var HolidaysRepository */
    protected $holidayRepo;

    public function __construct(HolidaysRepository $holidaysRepository)
    {
        $this->holidayRepo = $holidaysRepository;
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setDescription('Add a short description for your command')
            ->addArgument('startdate', InputArgument::REQUIRED, 'Argument description')
            ->addOption('publicholidays', null, InputOption::VALUE_OPTIONAL, 'Use public holidays for federal country')
            ->addOption('schoolholidays', null, InputOption::VALUE_OPTIONAL, 'Use school holidays for federal country')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        setlocale(LC_TIME, 'de_DE');

        $io = new SymfonyStyle($input, $output);
        $arg1 = $input->getArgument('startdate');
        $publicHolidaysFor = strtoupper($input->getOption('publicholidays'));
        $schoolHolidaysFor = strtoupper($input->getOption('schoolholidays'));

        $startDate = new \DateTime($arg1);
        $renderRequest = new RenderRequest(RequestTypes::LANDSCAPE_YEAR, $startDate);
        $io->title('Starting calender generation with startdate ' . $startDate->format('Y-m-d'));

        $events = new Events();
        if (!empty($publicHolidaysFor)) {
            $io->text('* loading holidays for ' . $publicHolidaysFor);
            $holidays = $this->holidayRepo->getPublicHolidays($publicHolidaysFor);
            $events->addEvents($holidays);
        }

        if (!empty($schoolHolidaysFor)) {
            $io->text('* loading school vacations for ' . $schoolHolidaysFor);
            $vacations = $this->holidayRepo->getSchoolHolidays($schoolHolidaysFor);
            $events->addEvents($vacations);
        }

        $io->text('* rendering calendar');
        $io->newLine();
        $renderer = new LandscapeYear(new EventRenderer());
        $renderer->setCalendarEvents($events);
        $renderer->renderCalendar($renderRequest);

        return 0;
    }
}
