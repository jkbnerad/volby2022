<?php

declare(strict_types=1);

namespace app;

use Symfony\Component\Console\Output\OutputInterface;

class DownloadData
{
    public function download(OutputInterface $output): void
    {
        $files = [
            'cns.xml',
            'cnumnuts.xml',
            'cpp.xml',
            'cvs.xml',
            'kvrk.zip',
            'kvros.xml',
            'kvrzcoco.xml'
        ];

        $baseUrl = 'https://volby.cz/opendata/kv2022/xml/';

        foreach ($files as $file) {
            if (file_exists(__DIR__ . '/../data/' . $file) === false) {
                $fileName = $baseUrl . $file;
                $data = file_get_contents($fileName);
                if (file_put_contents(__DIR__ . '/../data/' . $file, $data)) {
                    if (str_contains($fileName, 'zip')) {
                        $zip = new \ZipArchive();
                        $read = $zip->open(__DIR__ . '/../data/' . $file);
                        if ($read) {
                            $zip->extractTo(__DIR__ . '/../data/');
                            $zip->close();
                        }
                    }

                    $output->writeln(sprintf('File %s has been downloaded.', $file));
                } else {
                    $output->writeln(sprintf('Failed. File %s has NOT been downloaded.', $file));
                }
            } else {
                $output->writeln(sprintf('File %s exists.', $file));
            }
        }
    }
}
