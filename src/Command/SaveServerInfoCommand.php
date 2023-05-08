<?php

namespace App\Command;

use App\Entity\Server;
use App\Enum\Currency;
use App\Service\ServerService;
use Doctrine\ORM\EntityManagerInterface;
use League\Csv\Reader;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:save-server-info',
    description: 'It reads the csv file of server info and insert the data into the DB.',
)]
class SaveServerInfoCommand extends Command
{
    /**
     * @param EntityManagerInterface $entityManager
     * @param ServerService          $serverService
     */
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly ServerService          $serverService,
    ) {
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setDescription('Inserts data from a CSV file into MySQL')
            ->addArgument('filename', InputArgument::REQUIRED, 'The path to the CSV file')
            ->addOption('headers', 'H', InputOption::VALUE_REQUIRED, 'The headers to import (comma-separated)');

    }

    /**
     * @param  InputInterface  $input
     * @param  OutputInterface $output
     * @return int
     *
     * Usage sample: bin/console app:save-server /home/mert/filter-api/assets/servers_filters_assignment.csv -H Model,Ram,HDD,Location,Price
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        try {
            $filename = $input->getArgument('filename');
            $headers = $input->getOption('headers');

            $csv = Reader::createFromPath($filename);

            if (!empty($headers)) {
                $headers = explode(',', $headers);
            } else {
                $headers = null;
            }

            foreach ($csv->getRecords($headers) as $k => $record) {
                // Continue if it's the csv header line
                if ($k == 0) {
                    continue;
                }

                $ramInfo = $this->serverService->getRamInfo($record['Ram']);
                if ($ramInfo['status'] != true) {
                    $output->writeln(sprintf('[Model:%s] An error occurred during the getting Ram info: %s', $record['Model'], $ramInfo['message']));
                }
                $hddInfo = $this->serverService->getHddInfo($record['HDD']);
                if ($hddInfo['status'] != true) {
                    $output->writeln(sprintf('[Model:%s] An error occurred during the getting Hdd info: %s', $record['Model'], $hddInfo['message']));
                }
                $currencyInfo = $this->serverService->getCurrencyInfo($record['Price']);
                if ($currencyInfo['status'] != true) {
                    $output->writeln(sprintf('[Model:%s] An error occurred during the getting Currency info: %s', $record['Model'], $currencyInfo['message']));
                }

                $server = new Server();
                $server->setModel($record['Model']);
                $server->setLocation($record['Location']);
                $server->setRamSize($ramInfo['data']['size']);
                $server->setRamType($ramInfo['data']['type']);
                $server->setRamSizeType($ramInfo['data']['sizeType']);
                $server->setActualRamSize($ramInfo['data']['actualSize']);
                $server->setHddCount($hddInfo['data']['count']);
                $server->setHddSize($hddInfo['data']['size']);
                $server->setHddSizeType($hddInfo['data']['sizeType']);
                $server->setHddType($hddInfo['data']['type']);
                $server->setHddTotalSize($hddInfo['data']['totalSize']);
                $server->setActualHddSize($hddInfo['data']['actualSize']);
                $server->setPrice($currencyInfo['data']['price']);
                $server->setPriceCurrency($currencyInfo['data']['currency']);

                $this->entityManager->persist($server);
            }

            $this->entityManager->flush();

            $output->writeln('server information are imported successfully.');

            return Command::SUCCESS;
        } catch (\Exception $e) {
            // Handle any exceptions that occur during the conversion.
            $output->writeln(sprintf('An error occurred during the process: %s', $e->getMessage()));
            return Command::FAILURE;
        }

    }
}
