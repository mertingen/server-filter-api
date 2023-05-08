<?php

namespace App\Command;

use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:convert-xlsx-to-csv',
    description: 'Add a short description for your command',
)]
class ConvertXlsxToCsvCommand extends Command
{
    protected function configure()
    {
        $this->setDescription('Converts an XLSX file to a CSV file with custom headers.')
            ->addOption('input', 'i', InputOption::VALUE_REQUIRED, 'The input XLSX file path.')
            ->addOption('output', 'o', InputOption::VALUE_REQUIRED, 'The output CSV file path.')
            ->addOption('headers', 'H', InputOption::VALUE_REQUIRED, 'The custom headers as a comma-separated string.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        try {
            // Retrieve the input file path, output file path, and custom headers.
            $inputFilePath = $input->getOption('input');
            $outputFilePath = $input->getOption('output');
            $customHeaders = $input->getOption('headers');

            // Convert the custom headers from a comma-separated string to an array.
            $headers = explode(',', $customHeaders);

            // Load the XLSX file.
            $reader = new Xlsx();
            $spreadsheet = $reader->load($inputFilePath);

            // Get the first worksheet.
            $worksheet = $spreadsheet->getActiveSheet();

            // Open the output file.
            $file = new \SplFileObject($outputFilePath, 'w');

            // Write the custom headers to the CSV file.
            $file->fputcsv($headers);

            // Write the worksheet data to the CSV file.
            foreach ($worksheet->toArray() as $row) {
                $file->fputcsv($row);
            }

            // Close the output file.
            $file = null;

            // Output a success message.
            $output->writeln(sprintf('The XLSX file "%s" has been converted to a CSV file with custom headers at "%s".', $inputFilePath, $outputFilePath));
            return Command::SUCCESS;
        } catch (\Exception $e) {
            // Handle any exceptions that occur during the conversion.
            $output->writeln(sprintf('An error occurred during the XLSX to CSV conversion: %s', $e->getMessage()));
            return Command::FAILURE;
        }

    }
}
