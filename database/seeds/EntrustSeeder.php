<?php
/**
 * Created by PhpStorm.
 * User: Denis
 * Date: 30.12.2016
 * Time: 00:16
 */
use App\Role;
use App\User;
use Illuminate\Database\Seeder;

class EntrustSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $user_admin = User::create([
            'name' => 'admin',
            'email' => 'something@something.com',
            'password' => bcrypt('qwerty'),
        ]);

        $user_mod = User::create([
            'name' => 'moderator_przemek',
            'email' => 'something22@xddd.com',
            'password' => bcrypt('qwerty'),
        ]);

        //create a role of admin
        $admin = Role::create([
            'name' => 'admin',
            'display_name' => 'Admin',
            'description' => 'Have all permission',
        ]);
        //create a role of mode

        $mod = Role::create(array(
            'name' => 'mod',
            'display_name' => 'Moderator',
            'description' => 'Mange trios',
        ));

        //trio permission
        $manage_trio = \App\Permission::create(array(
            'name' => 'trio.manage',
            'display_name' => 'Manage trios',
            'description' => 'Creating, updating and deleting trios'
        ));

        //user management permission
        $manage_user = \App\Permission::create(array(
            'name' => 'user.manage',
            'display_name' => 'Manage users',
            'description' => 'Creating, updating and deleting user accounts'
        ));


        $admin->attachPermission(array($manage_trio, $manage_user));
        $mod->attachPermission(array($manage_trio));

        $user_admin->attachRole($admin);
        $user_mod->attachRole($mod);
    }
}
