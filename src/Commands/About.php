<?php

declare(strict_types=1);

namespace app\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class About extends Command
{
    protected static $defaultName = 'about';

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('Volby 2022');
        return 0;
    }
}
