<?php namespace Elipce\Multisite\Console;

use Illuminate\Console\Command;
use Elipce\Multisite\Models\Portal as PortalModel;

class PortalList extends Command
{
    /**
     * @var string The console command name.
     */
    protected $name = 'portal:list';

    /**
     * @var string The console command description.
     */
    protected $description = 'List all available portals.';

    /**
     * Execute the console command.
     * @return void
     */
    public function fire()
    {
        $portals = PortalModel::all();

        if ($portals->count() > 0) {
            $portals->each(function ($portal) {
                $output = "[{$portal->id}]\t{$portal->name} -> {$portal->url}";
                $this->info($output);
            });
        } else {
            $this->error("No portal found !");
        }
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