<?php namespace Zizaco\Entrust;

/**
 * This file is part of Entrust,
 * a role & permission management solution for Laravel.
 *
 * @license MIT
 * @package Zizaco\Entrust
 */

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;

class CreateRoleCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'entrust:role
                            {name : The name of the role}
                            {--display_name= :  Human readable name for the role}
                            {--description= :  A more detailed explanation of what the role does}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates new role';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function fire()
    {
        $roleModel           = Config::get('entrust.role');

        $role = new $roleModel;
        $role->name         = $this->argument('name');
        $role->display_name = $this->option('display_name'); // optional
        $role->description  = $this->option('description');  // optional
        $role->save();

        $this->info('New role created: ' . $role->name);
    }
}
