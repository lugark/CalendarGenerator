<?php

namespace App\Command;

use App\Calendar\Calendar;
use App\Renderer\LandscapeYear;
use App\Renderer\LandscapeYearMpdf;
use App\Renderer\LandscapeYearTwig;
use App\Repository\HolidaysRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Twig\Environment;

class CalendarGenerateCommand extends Command
{
    protected static $defaultName = 'calendar:generate';

    /** @var HolidaysRepository */
    protected $holidayRepo;

    public function __construct(HolidaysRepository $holidaysRepository, Environment $twig)
    {
        $this->holidayRepo = $holidaysRepository;
        $this->twig = $twig;
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setDescription('Add a short description for your command')
            ->addArgument('startdate', InputArgument::REQUIRED, 'Argument description')
            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description')
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
        $calendar = new Calendar($startDate);
        $io->title('Starting calender generation with startdate ' . $startDate->format('Y-m-d'));

        if (!empty($publicHolidaysFor)) {
            $io->text('* loading holidays for ' . $publicHolidaysFor);
            $holidays = $this->holidayRepo->getPublicHolidays($publicHolidaysFor);
            $calendar->addEvents($holidays);
        }

        if (!empty($schoolHolidaysFor)) {
            $io->text('* loading school vacations for ' . $schoolHolidaysFor);
            $vacations = $this->holidayRepo->getSchoolHolidays($schoolHolidaysFor);
            $calendar->addEvents($vacations);
        }

        $io->text('* generating calendar');
        $calendar->generateCalendarData();

        $io->text('* rendering calendar');
        $io->newLine();
        $renderer = new LandscapeYearMpdf();
        $renderer->setCalendarData($calendar->getData());
        /** TODO: do not pass events through calendar - renderer can filter */
        $renderer->setCalendarEvents($calendar->getActiveCalendarEvents());
        $renderer->renderCalendar(realpath(__DIR__ . '/../../') . '/test_direct.pdf');

        $io->success('You have a new command! Now make it your own! Pass --help to see your options.');

        return 0;
    }
}
