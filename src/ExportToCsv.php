<?php

declare(strict_types=1);

namespace app;

use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Output\OutputInterface;

class ExportToCsv
{
    public function __construct(
        private CounterAffiliationParties $counterAffiliationParties,
        private CounterMunicipalities $counterMunicipalities,
        private CounterPartiesRegister $counterPartiesRegister,
        private CounterProposingParties $counterProposingParties,
        private CounterRegions $counterRegions
    ) {
    }

    public function export(OutputInterface $output): void
    {
        $output->writeln('Export to CSV');
        $this->removeOutputs();

        $doc = new \DOMDocument();
        $doc->load(DataPath::results());
        $bar = new ProgressBar($output, count($doc->getElementsByTagName('KV_REGKAND_ROW')));

        /** @var \XMLReader $XMLReader */
        $XMLReader = \XMLReader::XML(file_get_contents(DataPath::results()));

        $fileHandler = fopen(__DIR__ . '/../output/all.csv', 'wb');
        while ($XMLReader->read()) {
            if ($XMLReader->nodeType === \XMLReader::ELEMENT && $XMLReader->name === 'KV_REGKAND_ROW') {
                $regionCode = $municipalityCode = $partyCode = $partyProposingCode = $partyAffiliationCode = $age = $order = 0;
                $firstname = $lastname = $degree = $job = '';

                foreach ($XMLReader->expand()->childNodes as $childNode) {
                    $name = $childNode->nodeName;
                    if ($name === 'OKRES') {
                        $regionCode = (int)$childNode->nodeValue;
                    }

                    if ($name === 'KODZASTUP') {
                        $municipalityCode = (int)$childNode->nodeValue;
                    }

                    if ($name === 'OSTRANA') {
                        $partyCode = (int)$childNode->nodeValue;
                    }

                    if ($name === 'NSTRANA') {
                        $partyProposingCode = (int)$childNode->nodeValue;
                    }

                    if ($name === 'PSTRANA') {
                        $partyAffiliationCode = (int)$childNode->nodeValue;
                    }

                    if ($name === 'JMENO') {
                        $firstname = (string)$childNode->nodeValue;
                    }

                    if ($name === 'PRIJMENI') {
                        $lastname = (string)$childNode->nodeValue;
                    }

                    if ($name === 'TITULPRED') {
                        $degree = (string)$childNode->nodeValue;
                    }

                    if ($name === 'VEK') {
                        $age = (int)$childNode->nodeValue;
                    }

                    if ($name === 'POVOLANI') {
                        $job = (string)$childNode->nodeValue;
                    }

                    if ($name === 'PORCISLO') {
                        $order = (int)$childNode->nodeValue;
                    }
                }

                $region = $this->counterRegions->getName($regionCode);
                $municipality = $this->counterMunicipalities->getMunicipality($municipalityCode);

                $party = $this->counterPartiesRegister->getName($partyCode, $municipalityCode);
                $partyProposing = $this->counterProposingParties->getName($partyProposingCode);
                $partyAffiliation = $this->counterAffiliationParties->getName($partyAffiliationCode);

                $item = [
                    $region,
                    $municipality,
                    $party,
                    $order,
                    $firstname,
                    $lastname,
                    $degree,
                    $age,
                    $job,
                    $partyProposing,
                    $partyAffiliation
                ];

                fputcsv($fileHandler, $item);
                $bar->advance();

                $regionFile = __DIR__ . '/../output/regions/ ' . $region . '.csv';
                if (file_exists($regionFile)) {
                    $r = fopen($regionFile, 'ab');
                } else {
                    $r = fopen($regionFile, 'wb');
                }
                fputcsv($r, $item);
                fclose($r);
            }
        }

        fclose($fileHandler);

        $bar->finish();
        $output->writeln('');
    }

    private function removeOutputs(): void
    {
        $files = glob(__DIR__ . '/../output/regions/*'); // get all file names
        foreach ($files as $file) {
            if (is_file($file)) {
                unlink($file); // delete file
            }
        }

        $all = __DIR__ . '/../output/all.csv';
        if (file_exists($all)) {
            unlink(__DIR__ . '/../output/all.csv');
        }
    }

}
