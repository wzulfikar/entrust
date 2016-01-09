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

class RoleListCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'entrust:role:list';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'List all available roles';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function fire()
    {
        $roleModel       = Config::get('entrust.role');
        $permissionModel = Config::get('entrust.permission');
        $userModel       = str_replace('Role', 'User', $roleModel);

        $headers = ['ID', 'Name', 'Display Name', 'Description', 'Permissions', 'Attached Users'];

        $roles = $roleModel::all(['id','name', 'display_name', 'description']);

        $permission_role_table = DB::table(Config::get('entrust.permission_role_table'));
        $role_user_table       = DB::table(Config::get('entrust.role_user_table'));
        
        foreach ($roles as $key => $role) {
            $permission_roles = $permission_role_table->where('role_id', $role->id)->get();
            $role_users = $role_user_table->where('role_id', $role->id)->get();
            
            $permissions = [];
            $usernames   = [];
            
            foreach ($permission_roles as $permission_role) {
                $permissions[] = $permissionModel::find($permission_role->permission_id)->name;
            }
            foreach ($role_users as $role_user) {
                $usernames[] = $userModel::find($role_user->user_id)->username;
            }
            
            $role->permissions = implode(', ', $permissions);
            $role->users = implode(', ', $usernames);
        }

        $this->table($headers, $roles->toArray());
    }
}
