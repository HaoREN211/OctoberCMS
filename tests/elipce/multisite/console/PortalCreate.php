<?php namespace Elipce\Multisite\Console;

use Illuminate\Console\Command;
use Elipce\Multisite\Models\Portal as PortalModel;
use October\Rain\Exception\ValidationException;
use Symfony\Component\Console\Input\InputArgument;
use October\Rain\Support\Facades\File;
use Cms\Classes\Theme;

/**
 * Class PortalCreate
 * @package Elipce\Multisite\Console
 */
class PortalCreate extends Command
{
    /**
     * @var string The console command name.
     */
    protected $name = 'portal:create';

    /**
     * @var string The console command description.
     */
    protected $description = 'Create a new portal.';

    /**
     * Execute the console command.
     * @return void
     */
    public function fire()
    {
        try {
            $theme = Theme::load($this->argument('theme'));
            $newThemeName = $this->clean($this->argument('name'));
            $this->duplicate($theme, $newThemeName);

            PortalModel::create([
                'name' => $this->argument('name'),
                'theme' => $newThemeName,
                'domain' => $this->argument('domain'),
                'subdomain' => $this->argument('subdomain')
            ]);
            $this->info("Portal successfuly created !");

        } catch (\Exception $e) {
            $this->error($e->getMessage());
            die();
        }
    }

    /**
     * Remove special characters and clean portal's name
     *
     * @param $name
     * @return string
     */
    protected function clean($name)
    {
        return preg_replace('/[^A-Za-z]/', '', strtolower(trim($name)));
    }

    /**
     * Duplicate a theme
     *
     * @param $theme
     * @param $name
     * @throws ValidationException
     */
    protected function duplicate($theme, $name)
    {
        // Set up paths
        $sourcePath = $theme->getPath();
        $destinationPath = themes_path() . '/' . $name;

        // Check if the directory doesn't exist
        if (File::isDirectory($destinationPath)) {
            throw new ValidationException(['new_dir_name' => trans('cms::lang.theme.dir_name_taken')]);
        }

        // Copy theme directory
        File::copyDirectory($sourcePath, $destinationPath);

        // Rename theme code
        $newTheme = Theme::load($name);
        $newTheme->writeConfig(['name' => $name, 'code' => $name]);
    }

    /**
     * Get the console command arguments.
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['name', InputArgument::REQUIRED, 'Portal\'s name.'],
            ['theme', InputArgument::REQUIRED, 'Portal\'s theme.'],
            ['domain', InputArgument::REQUIRED, 'Portal\'s domain.'],
            ['subdomain', InputArgument::OPTIONAL, 'Portal\'s subdomain.']
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