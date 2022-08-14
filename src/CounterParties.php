<?php

declare(strict_types=1);

namespace app;

use Symfony\Component\Console\Helper\ProgressBar;

class CounterParties extends Counter
{
    public function preload(): void
    {
        $this->output?->writeln('Counter: Parties');

        $doc = new \DOMDocument();
        $doc->load(DataPath::parties());

        $parties = [];
        $bar = null;
        if ($this->output) {
            $cnt = count($doc->getElementsByTagName('CVS_ROW'));
            $bar = new ProgressBar($this->output, $cnt);
        }
        foreach ($doc->getElementsByTagName('CVS_ROW') as $row) {
            $key = (int)$row->getElementsByTagName('VSTRANA')->item(0)->nodeValue;
            $name = $row->getElementsByTagName('NAZEVCELK')->item(0)->nodeValue;
            $parties[$key] = $name;
            $bar?->advance();
        }
        $bar?->finish();
        $this->output?->writeln('');

        $this->counter = $parties;
    }

    public function getName(int $vstrana): ?string
    {
        if ($this->counter === null) {
            $this->preload();
        }

        return $this->counter[$vstrana] ?? null;
    }

}
