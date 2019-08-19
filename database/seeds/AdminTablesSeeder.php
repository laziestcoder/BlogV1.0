<?php

namespace Encore\Admin\Auth\Database;

use Illuminate\Database\Seeder;

class AdminTablesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // create a user.
        Administrator::truncate();
        Administrator::create(
            [
            'username' => 'admin',
            'password' => bcrypt('admin'),
            'name'     => 'Administrator',
            ],
            [
                'username' => 'towfiq',
                'password' => bcrypt('i am boss'),
                'name' => 'Towfiqul Islam',
            ],
            [
                'username' => 'supervisor',
                'password' => bcrypt('i am supervisor'),
                'name' => 'Supervisor',
            ],
    );

        // create a role.
        Role::truncate();
        Role::create(
            [
                'name' => 'Administrator',
                'slug' => 'administrator',
            ],
            [
                'name' => 'Supervisor',
                'slug' => 'Supervisor',
            ],
            [
                'name' => 'Media Editor',
                'slug' => 'media editor',
            ],
            [
                'name' => 'Notice Editor',
                'slug' => 'editor',
            ],
    );

        // add role to user.
        Administrator::find(1)->roles()->save(Role::find(1));
        Administrator::find(2)->roles()->save(Role::find(1));
        Administrator::find(3)->roles()->save(Role::find(1));

        //create a permission
        Permission::truncate();
        Permission::insert([
            [
                'name'        => 'All permission',
                'slug'        => '*',
                'http_method' => '',
                'http_path'   => '*',
            ],
            [
                'name'        => 'Dashboard',
                'slug'        => 'dashboard',
                'http_method' => 'GET',
                'http_path'   => '/',
            ],
            [
                'name'        => 'Login',
                'slug'        => 'auth.login',
                'http_method' => '',
                'http_path'   => "/auth/login\r\n/auth/logout",
            ],
            [
                'name'        => 'User setting',
                'slug'        => 'auth.setting',
                'http_method' => 'GET,PUT',
                'http_path'   => '/auth/setting',
            ],
            [
                'name'        => 'Auth management',
                'slug'        => 'auth.management',
                'http_method' => '',
                'http_path'   => "/auth/roles\r\n/auth/permissions\r\n/auth/menu\r\n/auth/logs",
            ],
            [
                'name' => 'Media Manager',
                'slug' => 'ext.media-manager',
                'http_method' => '',
                'http_path' => '/media*',
            ],
            [
                'name' => 'Post',
                'slug' => 'post',
                'http_method' => '',
                'http_path' => '/auth/posts*',
            ],
        ]);

        Role::first()->permissions()->save(Permission::first());

        // add default menus.
        Menu::truncate();
        Menu::insert([
            [
                'parent_id' => 0,
                'order'     => 1,
                'title'     => 'Dashboard',
                'icon'      => 'fa-bar-chart',
                'uri'       => '/',
            ],
            [
                'parent_id' => 0,
                'order'     => 2,
                'title'     => 'Admin',
                'icon'      => 'fa-tasks',
                'uri'       => '',
            ],
            [
                'parent_id' => 2,
                'order'     => 3,
                'title'     => 'Users',
                'icon'      => 'fa-users',
                'uri'       => 'auth/users',
            ],
            [
                'parent_id' => 2,
                'order'     => 4,
                'title'     => 'Roles',
                'icon'      => 'fa-user',
                'uri'       => 'auth/roles',
            ],
            [
                'parent_id' => 2,
                'order'     => 5,
                'title'     => 'Permission',
                'icon'      => 'fa-ban',
                'uri'       => 'auth/permissions',
            ],
            [
                'parent_id' => 2,
                'order'     => 6,
                'title'     => 'Menu',
                'icon'      => 'fa-bars',
                'uri'       => 'auth/menu',
            ],
            [
                'parent_id' => 2,
                'order'     => 7,
                'title'     => 'Operation log',
                'icon'      => 'fa-history',
                'uri'       => 'auth/logs',
            ],
            [
                'parent_id' => 0,
                'order'     => 8,
                'title'     => 'Posts',
                'icon'      => 'fa-files-o',
                'uri'       => '/auth/post',
            ],
            [
                'parent_id' => 8,
                'order' => 9,
                'title' => 'All Post',
                'icon' => 'fa-files-o',
                'uri' => '/auth/post',
            ],
            [
                'parent_id' => 8,
                'order' => 10,
                'title' => 'New Post',
                'icon' => 'fa-pencil-square-o',
                'uri' => '/auth/post/create',
            ],
        ]);

        // add role to menu.
        Menu::find(2)->roles()->save(Role::first());
    }
}
