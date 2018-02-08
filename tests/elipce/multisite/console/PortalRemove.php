<?php namespace Elipce\Multisite\Console;

use Illuminate\Console\Command;
use Elipce\Multisite\Models\Portal as PortalModel;
use Symfony\Component\Console\Input\InputArgument;
use October\Rain\Support\Facades\File;
use Cms\Classes\Theme;

/**
 * Class PortalRemove
 * @package Elipce\Multisite\Console
 */
class PortalRemove extends Command
{
    /**
     * @var string The console command name.
     */
    protected $name = 'portal:remove';

    /**
     * @var string The console command description.
     */
    protected $description = 'Remove an existing portal.';

    /**
     * Execute the console command.
     * @return void
     */
    public function fire()
    {
        // Fetch portal
        $portal = PortalModel::find($this->argument('id'));

        // Check if portal exists
        if ($portal == null) {
            $this->error("Portal does not exist !");
            die();
        }

        try {
            // Remove theme directory
            $theme = Theme::load($portal->theme);
            File::deleteDirectory($theme->getPath());
            // Delete database record
            $portal->delete();
            $this->info("Portal successfuly removed !");
        } catch (\Exception $e) {
            $this->error($e->getMessage());
            die();
        }
    }

    /**
     * Get the console command arguments.
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['id', InputArgument::REQUIRED, 'Portal\'s id.']
        ];
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