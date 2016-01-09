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

class CreatePermissionCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'entrust:permission
                            {name : The name of the role}
                            {--display_name= :  Human readable name for the permission}
                            {--description= :  A more detailed explanation of what the permission does}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates new permission';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function fire()
    {
        $permissionModel     = Config::get('entrust.permission');

        $permission = new $permissionModel;
        $permission->name         = $this->argument('name');
        $permission->display_name = $this->option('display_name'); // optional
        $permission->description  = $this->option('description');  // optional
        $permission->save();

        $this->info('New permission created: ' . $permission->name);
    }
}
