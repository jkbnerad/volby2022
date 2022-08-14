<?php

declare(strict_types=1);

namespace app;

class DataPath
{


    public static function regions(): string
    {
        return __DIR__ . '/../data/cnumnuts.xml';
    }

    public static function municipalities(): string
    {
        return __DIR__ . '/../data/kvrzcoco.xml';
    }

    public static function partiesRegister(): string
    {
        return __DIR__ . '/../data/kvros.xml';
    }

    public static function parties(): string
    {
        return __DIR__ . '/../data/cvs.xml';
    }

    public static function affiliationParties(): string
    {
        return __DIR__ . '/../data/cpp.xml';
    }

    public static function proposingParties(): string
    {
        return __DIR__ . '/../data/cns.xml';
    }

    public static function results(): string
    {
        return __DIR__ . '/../data/kvrk.xml';
    }

}
