<?php namespace Zizaco\Entrust;

/**
 * This file is part of Entrust,
 * a role & permission management solution for Laravel.
 *
 * @license MIT
 * @package Zizaco\Entrust
 */

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;

class PermissionListCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'entrust:permission:list';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'List all available permissions';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function fire()
    {
        $roleModel           = Config::get('entrust.role');
        $permissionModel     = Config::get('entrust.permission');
        
        $headers = ['ID', 'Name', 'Display Name', 'Description', 'Attached Roles'];

        $permissions = $permissionModel::all(['id', 'name', 'display_name', 'description']);

        $permission_role_table = DB::table(Config::get('entrust.permission_role_table'));
        
        foreach ($permissions as $key => $permission) {
            $permission_roles = $permission_role_table->where('permission_id', $permission->id)->get();
            $roles = [];
            foreach ($permission_roles as $permission_role) {
                $roles[] = $roleModel::find($permission_role->role_id)->name;
            }
            $permission->roles = implode(', ', $roles);
        }

        $this->table($headers, $permissions->toArray());
    }
}
