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

class RoleAttachPermissionCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'entrust:role:attachpermission
                            {role : The name of the role}
                            {permissions :  Name of permission to be attached, separated by comma}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Attach permissions to role';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function fire()
    {
        $roleModel       = Config::get('entrust.role');
        $permissionModel = Config::get('entrust.permission');

        $roleName       = $this->argument('role');
        $permissionName = explode(',', str_replace(' ', '', $this->argument('permissions')));

        $permissions = $permissionModel::whereIn('name', $permissionName)->get();
        
        $role = $roleModel::where('name', $roleName)
              ->firstOrFail();

        foreach ($permissions as $permission) {
            $role->attachPermission($permission);
            $this->info('Permission ' . $permission->name . ' has succesfully attached to ' . $role->name);
        }
    }
}
