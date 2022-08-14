<?php

declare(strict_types=1);

namespace app\Commands;

use app\CounterAffiliationParties;
use app\CounterMunicipalities;
use app\CounterPartiesRegister;
use app\CounterProposingParties;
use app\CounterRegions;
use app\DownloadData;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ExportToCsv extends Command
{
    protected static $defaultName = 'exportToCsv';

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('Download data from volby.cz');
        $downloader = new DownloadData();
        $downloader->download($output);
        $output->writeln('Data has been downloaded.');

        $counterPartiesRegister = new CounterPartiesRegister();
        $counterPartiesRegister->setOutput($output);
        $counterPartiesRegister->preload();

        $counterProposingParties = new CounterProposingParties();
        $counterProposingParties->setOutput($output);
        $counterProposingParties->preload();

        $counterAffiliationParties = new CounterAffiliationParties();
        $counterAffiliationParties->setOutput($output);
        $counterAffiliationParties->preload();

        $counterMunicipalities = new CounterMunicipalities();
        $counterMunicipalities->setOutput($output);
        $counterMunicipalities->preload();

        $counterRegions = new CounterRegions();
        $counterRegions->setOutput($output);
        $counterRegions->preload();

        $toCsv = new \app\ExportToCsv(
            $counterAffiliationParties, $counterMunicipalities, $counterPartiesRegister, $counterProposingParties, $counterRegions
        );

        $toCsv->export($output);

        return 0;
    }
}
