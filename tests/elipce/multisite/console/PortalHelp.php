<?php namespace Elipce\Multisite\Console;

use Illuminate\Console\Command;

/**
 * Class PortalHelp
 * @package Elipce\Multisite\Console
 */
class PortalHelp extends Command
{
    /**
     * @var string The console command name.
     */
    protected $name = 'portal:help';

    /**
     * @var string The console command description.
     */
    protected $description = 'Give help for portal commands.';

    /**
     * Execute the console command.
     * @return void
     */
    public function fire()
    {
        $this->info("Refresh portal cache table:\n  portal:clear\n");
        $this->info("Create a new portal:\n  portal:create [name] [theme] [domain] [subdomain]\n");
        $this->info("Display portal list:\n  portal:list\n");
        $this->info("Remove a portal:\n  portal:remove [id]\n");
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