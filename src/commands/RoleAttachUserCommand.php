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

class RoleAttachUserCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'entrust:role:attachuser
                            {role : The name of the role}
                            {users :  username of users to be attached, separated by comma}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Attach users to role';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function fire()
    {
        $roleModel       = Config::get('entrust.role');
        $userModel       = str_replace('Role', 'User', $roleModel);

        $role        = $roleModel::where('name', $this->argument('role'))->firstOrFail();
        $usernames   = explode(',', str_replace(' ', '', $this->argument('users')));

        $users = $userModel::whereIn('username', $usernames)->get();
        foreach ($users as $user) {
            $user->attachRole($role); // parameter can be an Role object, array, or id
            $this->info('Role ' . $role->name . ' has succesfully attached to ' . $user->username);
        }
    }
}
