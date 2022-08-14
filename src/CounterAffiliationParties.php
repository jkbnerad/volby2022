<?php

declare(strict_types=1);

namespace app;

use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Output\OutputInterface;

class CounterAffiliationParties extends Counter
{
    public function preload(): void
    {
        $this->output?->writeln('Counter: Affiliation Parties');

        $parties = [];

        $doc = new \DOMDocument();
        $doc->load(DataPath::affiliationParties());

        $bar = null;
        if ($this->output) {
            $cnt = count($doc->getElementsByTagName('CPP_ROW'));
            $bar = new ProgressBar($this->output, $cnt);
        }

        foreach ($doc->getElementsByTagName('CPP_ROW') as $row) {
            $key = (int)$row->getElementsByTagName('PSTRANA')->item(0)->nodeValue;
            $name = $row->getElementsByTagName('NAZEV_STRP')->item(0)->nodeValue;
            $parties[$key] = $name;
            $bar?->advance();
        }
        $bar?->finish();
        $this->output?->writeln('');

        $this->counter = $parties;
    }

    public function getName(int $pstrana): ?string
    {
        return $this->counter[$pstrana] ?? null;
    }

}
