<?php namespace KurtJensen\MyCalendar\Updates;

use October\Rain\Database\Updates\Seeder;
use System\Classes\PluginManager;

class SeedAllTables extends Seeder
{

    public function run()
    {
        $manager = PluginManager::instance();
        if ($manager->exists('kurtjensen.passage')) {

            if (!\KurtJensen\Passage\Models\Key::where('name', '=', 'calendar_public')->first()) {
                \KurtJensen\Passage\Models\Key::create([
                    'name' => 'calendar_public',
                    'description' => 'Public Calendar Events ( no user account required to view )',
                ]);
            }

            if (!\KurtJensen\Passage\Models\Key::where('name', '=', 'calendar_deny_all')->first()) {
                \KurtJensen\Passage\Models\Key::create([
                    'name' => 'calendar_deny_all',
                    'description' => 'Denied Calendar Events ( no one can see these events )',
                ]);
            }
        }

    }
}
