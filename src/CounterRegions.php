<?php

declare(strict_types=1);

namespace app;

use Symfony\Component\Console\Helper\ProgressBar;

class CounterRegions extends Counter
{

    public function preload(): void
    {
        $this->output?->writeln('Counter: Regions');

        $doc = new \DOMDocument();
        $doc->load(DataPath::regions(), LIBXML_NOWARNING);

        $bar = null;
        if ($this->output) {
            $cnt = count($doc->getElementsByTagName('CNUMNUTS_ROW'));
            $bar = new ProgressBar($this->output, $cnt);
        }

        $regions = [];
        foreach ($doc->getElementsByTagName('CNUMNUTS_ROW') as $row) {
            $number = (int)$row->getElementsByTagName('NUMNUTS')->item(0)->nodeValue;
            $region = $row->getElementsByTagName('NAZEVNUTS')->item(0)->nodeValue;
            $regions[$number] = $region;
            $bar?->advance();
        }

        $bar?->finish();

        $this->output?->writeln('');

        $this->counter = $regions;
    }

    public function getName(int $NUMNUTS): ?string
    {
        if ($this->counter === null) {
            $this->preload();
        }

        return $this->counter[$NUMNUTS] ?? null;
    }

}
