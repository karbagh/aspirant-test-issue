<?php declare(strict_types=1);

namespace App\Command;

use App\Services\Apple\Trailers\AppleTrailerClientService;
use App\Services\Apple\Trailers\AppleTrailerDBService;
use App\Services\Apple\Trailers\AppleTrailerXmlParserService;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class FetchDataCommand extends Command
{
    protected static $defaultName = 'fetch:trailers';

    private AppleTrailerClientService $clientService;

    private AppleTrailerXmlParserService $xmlParserService;

    private AppleTrailerDBService $trailerDBService;

    /**
     * FetchDataCommand constructor.
     *
//     * @param ClientInterface        $httpClient
     * @param LoggerInterface        $logger
     * @param EntityManagerInterface $em
     * @param string|null            $name
     */
    public function __construct(
        private LoggerInterface           $logger,
        private EntityManagerInterface    $em,
        string                            $name = null
    ) {
        parent::__construct($name);
    }

    public function configure(): void
    {
        $this
            ->setDescription('Fetch data from iTunes Movie Trailers')
            ->addArgument('source', InputArgument::OPTIONAL, 'Overwrite source');
        $this->clientService = new AppleTrailerClientService();
        $this->xmlParserService = new AppleTrailerXmlParserService();
        $this->trailerDBService = new AppleTrailerDBService();
    }

    protected function execute(
        InputInterface $input,
        OutputInterface $output
    ): int {
        $this->logger->info(sprintf('Start %s at %s', __CLASS__, (string) date_create()->format(DATE_ATOM)));

        $moviesListResponse = $this->clientService->getTrailers();

        $io = new SymfonyStyle($input, $output);
        $io->title(sprintf('Fetch data from %s', $moviesListResponse->getEndpoint()));

        $movies = $this->xmlParserService->parse($moviesListResponse->getData());
        $io->title(sprintf('Parsing movies data %s', $moviesListResponse->getEndpoint()));

        foreach ($movies as $movie) {
            $message = $this->trailerDBService->store($movie, $this->em);
            $this->logger->info($message, ['title' => $movie->getTitle()]);
        }

        $this->logger->info(sprintf('End %s at %s', __CLASS__, (string) date_create()->format(DATE_ATOM)));

        return 0;
    }
}
