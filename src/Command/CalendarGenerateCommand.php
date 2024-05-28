<?php

namespace App\Command;

use App\Repository\HolidaysRepository;
use Calendar\Pdf\Renderer\Event\Events;
use Calendar\Pdf\Renderer\Renderer\CalendarRenderer;
use Calendar\Pdf\Renderer\Renderer\LandscapeYear;
use Calendar\Pdf\Renderer\Renderer\PdfRenderer;
use Calendar\Pdf\Renderer\Renderer\RenderRequest;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class CalendarGenerateCommand extends Command
{
    protected static $defaultName = 'calendar:generate';

    public function __construct(protected HolidaysRepository $holidaysRepository)
    {
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
        $publicHolidaysFor = strtoupper((string) $input->getOption('publicholidays'));
        $schoolHolidaysFor = strtoupper((string) $input->getOption('schoolholidays'));

        $startDate = new \DateTime($arg1);
        $io->title('Starting calender generation with startdate ' . $startDate->format('Y-m-d'));

        $events = new Events();
        if (!empty($publicHolidaysFor)) {
            $io->text('* loading holidays for ' . $publicHolidaysFor);
            $holidays = $this->holidaysRepository->getPublicHolidays($publicHolidaysFor);
            $events->addEvents($holidays);
        }

        if (!empty($schoolHolidaysFor)) {
            $io->text('* loading school vacations for ' . $schoolHolidaysFor);
            $vacations = $this->holidaysRepository->getSchoolHolidays($schoolHolidaysFor);
            $events->addEvents($vacations);
        }

        $io->text('* rendering calendar');
        $io->newLine();

        $renderRequest = new RenderRequest(LandscapeYear::class, $startDate);
        $renderRequest->setEvents($events);

        $renderer = new CalendarRenderer(new PdfRenderer());
        $renderer->renderCalendar($renderRequest);

        return 0;
    }
}
