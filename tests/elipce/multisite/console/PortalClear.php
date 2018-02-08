<?php namespace Elipce\Multisite\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

/**
 * Class PortalClear
 * @package Elipce\Multisite\Console
 */
class PortalClear extends Command
{
    /**
     * @var string The console command name.
     */
    protected $name = 'portal:clear';

    /**
     * @var string The console command description.
     */
    protected $description = 'Clear portals cache table.';

    /**
     * Execute the console command.
     * @return void
     */
    public function fire()
    {
        Cache::forget('elipce_multisite_portals');
        $this->info("Portals cache table cleared!");
    }

    /**
     * Get the console command arguments.
     * @return array
     */
    protected function getArguments()
    {
        return [];
    }

    /**
     * Get the console command options.
     * @return array
     */
    protected function getOptions()
    {
        return [];
    }
}