<?php

namespace App\Console\Commands;

use SoSmaller\Components\Logger;

class IndexCommand extends BaseCommand
{
    public function handle()
    {
        Logger::instance()->info('IndexCommand');
        echo "IndexCommand\n";
        return true;
    }
}
