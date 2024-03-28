<?php

namespace App\Console\Commands;

class IndexCommand extends BaseCommand
{
    public function handle()
    {
        app('logger')->info('IndexCommand');
        echo "IndexCommand\n";
        return true;
    }
}