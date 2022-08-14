<?php

declare(strict_types=1);

namespace app;

use Symfony\Component\Console\Helper\ProgressBar;

class CounterProposingParties extends Counter
{
    public function preload(): void
    {
        $this->output?->writeln('Counter: Parties - Proposing');

        $doc = new \DOMDocument();
        $doc->load(DataPath::proposingParties());

        $parties = [];
        $bar = null;
        if ($this->output) {
            $cnt = count($doc->getElementsByTagName('CNS_ROW'));
            $bar = new ProgressBar($this->output, $cnt);
        }
        foreach ($doc->getElementsByTagName('CNS_ROW') as $row) {
            $key = (int)$row->getElementsByTagName('NSTRANA')->item(0)->nodeValue;
            $name = $row->getElementsByTagName('NAZEV_STRN')->item(0)->nodeValue;
            $parties[$key] = $name;
            $bar?->advance();
        }
        $bar?->finish();
        $this->output?->writeln('');

        $this->counter = $parties;
    }

    public function getName(int $nstrana): ?string
    {
        if ($this->counter === null) {
            $this->preload();
        }

        return $this->counter[$nstrana] ?? null;
    }

}
