<?php

declare(strict_types=1);

namespace app;

use Symfony\Component\Console\Output\OutputInterface;

abstract class Counter
{
    protected ?array $counter = null;
    protected ?OutputInterface $output = null;

    abstract public function preload(): void;

    public function setOutput(?OutputInterface $output): void
    {
        $this->output = $output;
    }
}
