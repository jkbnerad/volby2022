<?php

declare(strict_types=1);

namespace app;

use Symfony\Component\Console\Helper\ProgressBar;

class CounterMunicipalities extends Counter
{

    public function preload(): void
    {
        $bar = null;
        if ($this->output) {
            $this->output->writeln('Counter: Municipalities');
            $doc = new \DOMDocument();
            $doc->load(DataPath::municipalities(), LIBXML_NOWARNING);
            $cnt = count($doc->getElementsByTagName('KV_RZCOCO_ROW'));
            $bar = new ProgressBar($this->output, $cnt);
        }

        /** @var \XMLReader $XMLReader */
        $XMLReader = \XMLReader::XML(file_get_contents(DataPath::municipalities()));
        $municipalities = [];
        while ($XMLReader->read()) {
            if ($XMLReader->nodeType === \XMLReader::ELEMENT && $XMLReader->name === 'KV_RZCOCO_ROW') {
                $key = 0;
                $municipality = '';
                foreach ($XMLReader->expand()->childNodes as $childNode) {
                    $name = $childNode->nodeName;

                    if ($name === 'KODZASTUP') {
                        $key = (int)$childNode->nodeValue;
                    }

                    if ($name === 'NAZEVZAST') {
                        $municipality = (string)$childNode->nodeValue;
                    }
                }

                if ($key && $municipality) {
                    $municipalities[$key] = $municipality;
                }

                $bar?->advance();
            }
        }

        $bar?->finish();
        $this->output?->writeln('');

        $this->counter = $municipalities;
    }

    public function getMunicipality(int $kodzastup): ?string
    {
        if ($this->counter === null) {
            $this->preload();
        }

        return $this->counter[$kodzastup] ?? null;
    }

}
