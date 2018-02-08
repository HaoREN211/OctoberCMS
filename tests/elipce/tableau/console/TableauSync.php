<?php namespace Elipce\Tableau\Console;

use Elipce\Tableau\Models\Site;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class TableauSync extends Command
{
    /**
     * @var string The console command name.
     */
    protected $name = 'tableau:sync';

    /**
     * @var string The console command description.
     */
    protected $description = 'Sychronize Tableau Online data.';

    /**
     * Execute the console command.
     * @return void
     */
    public function fire()
    {
        // Fetch all sites
        $sites = Site::all();

        // Handle option value
        if (!empty($this->option('site'))) {
            $sites = $sites->where('url', $this->option('site'));
        }

        // Check if there are available sites
        if ($sites->count() == 0) {
            $this->error("No site available !");
            die();
        }

        // Synchronize each site
        $sites->each(function ($site) {
            try {
                $this->synchronize($site, $this->argument('choice'));
                $site->touch();
            } catch (\Exception $e) {
                $this->error($e->getMessage());
                die();
            }
        });
    }

    protected function synchronize($site, $choice)
    {
        switch ($choice) {
            case 'all':
                $this->info(".... Synchonize {$site->name} :");
                $this->info("...... Synchronize groups :");
                $site->syncGroups();
                $this->info("...... Groups synchronized !");
                $this->info("...... Synchronize workbooks :");
                $site->syncWorkbooks();
                $this->info("...... Workbooks synchronized !");
                $this->info("...... Synchronize views :");
                $site->syncViews();
                $this->info("...... Views synchronized !");
                $this->info("...... Synchronize viewers :");
                $site->syncViewers();
                $this->info("...... Viewers synchronized !");
                $this->info("...... Synchronize users-groups :");
                $site->syncUsersGroups();
                $this->info("...... Users-Groups synchronized !");
                $this->info("...... Synchronize subscriptions :");
                $site->syncSubscriptions();
                $this->info("...... Subscriptions synchronized !");
                $this->info(".... {$site->name} synchronized !");
                break;
            case 'groups':
                $this->info(".... Synchonize {$site->name} :");
                $this->info("...... Synchronize groups :");
                $site->syncGroups();
                $this->info("...... Groups synchronized !");
                $this->info(".... {$site->name} synchronized !");
                break;
            case 'workbooks':
                $this->info(".... Synchonize {$site->name} :");
                $this->info("...... Synchronize workbooks :");
                $site->syncWorkbooks();
                $this->info("...... Workbooks synchronized !");
                $this->info(".... {$site->name} synchronized !");
                break;
            case 'views':
                $this->info(".... Synchonize {$site->name} :");
                $this->info("...... Synchronize views :");
                $site->syncViews();
                $this->info("...... Views synchronized !");
                $this->info(".... {$site->name} synchronized !");
                break;
            case 'viewers':
                $this->info(".... Synchonize {$site->name} :");
                $this->info("...... Synchronize viewers :");
                $site->syncViewers();
                $this->info("...... Viewers synchronized !");
                $this->info(".... {$site->name} synchronized !");
                break;
            case 'usersgroups':
                $this->info(".... Synchonize {$site->name} :");
                $this->info("...... Synchronize users-groups :");
                $site->syncUsersGroups();
                $this->info("...... Users-Groups synchronized !");
                $this->info(".... {$site->name} synchronized !");
                break;
            case 'subscriptions':
                $this->info(".... Synchonize {$site->name} :");
                $this->info("...... Synchronize subscriptions :");
                $site->syncSubscriptions();
                $this->info("...... Subscriptions synchronized !");
                $this->info(".... {$site->name} synchronized !");
                break;
            default:
                $this->error("Invalid argument !");
                $this->info("Usage: tableau:sync your_choice");
                $this->info("Choices : all | groups | workbooks | views | viewers | usersgroups | subscriptions");
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
            ['choice', InputArgument::OPTIONAL, 'Things to synchronize.']
        ];
    }

    /**
     * Get the console command options.
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['site', null, InputOption::VALUE_OPTIONAL, 'Tableau site to synchronize (url).']
        ];
    }
}