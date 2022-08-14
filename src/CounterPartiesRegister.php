<?php

declare(strict_types=1);

namespace app;

use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Output\OutputInterface;

class CounterPartiesRegister extends Counter
{

    public function preload(): void
    {
        $this->output?->writeln('Counter: Parties - Register');

        $bar = null;
        if ($this->output) {
            $doc = new \DOMDocument();
            $doc->load(DataPath::partiesRegister(), LIBXML_NOWARNING);
            $cnt = count($doc->getElementsByTagName('KV_ROS_ROW'));
            $bar = new ProgressBar($this->output, $cnt);
        }

        /** @var \XMLReader $XMLReader */
        $XMLReader = \XMLReader::XML(file_get_contents(DataPath::partiesRegister()));
        while ($XMLReader->read()) {
            if ($XMLReader->nodeType === \XMLReader::ELEMENT && $XMLReader->name === 'KV_ROS_ROW') {
                $ostrana = $kodzastup = $party = null;
                foreach ($XMLReader->expand()->childNodes as $childNode) {
                    $name = $childNode->nodeName;
                    if ($name === 'OSTRANA') {
                        $ostrana = (int)$childNode->nodeValue;
                    }

                    if ($name === 'KODZASTUP') {
                        $kodzastup = (int)$childNode->nodeValue;
                    }

                    if ($name === 'NAZEVCELK') {
                        $party = (string)$childNode->nodeValue;
                    }
                }

                $this->counter[$ostrana . '_' . $kodzastup] = $party;
                $bar?->advance();
            }
        }

        $bar?->finish();
        $this->output?->writeln('');
    }

    public function getName(int $ostrana, int $kodzastup): ?string
    {
        if ($this->counter === null) {
            $this->preload();
        }

        return $this->counter[$ostrana . '_' . $kodzastup] ?? null;
    }

}
