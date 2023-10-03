<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        //Admin Permission
        // create permissions for posts
        Permission::create(['name' => 'admin-create-posts', 'guard_name' => 'web']);
        Permission::create(['name' => 'admin-read-posts', 'guard_name' => 'web']);
        Permission::create(['name' => 'admin-update-posts', 'guard_name' => 'web']);
        Permission::create(['name' => 'admin-delete-posts', 'guard_name' => 'web']);
        // create permission for category
        Permission::create(['name' => 'admin-create-category', 'guard_name' => 'web']);
        Permission::create(['name' => 'admin-read-category', 'guard_name' => 'web']);
        Permission::create(['name' => 'admin-update-category', 'guard_name' => 'web']);
        Permission::create(['name' => 'admin-delete-category', 'guard_name' => 'web']);
        //create permission for author
        Permission::create(['name' => 'admin-create-author', 'guard_name' => 'web']);
        Permission::create(['name' => 'admin-read-author', 'guard_name' => 'web']);
        Permission::create(['name' => 'admin-update-author', 'guard_name' => 'web']);
        Permission::create(['name' => 'admin-delete-author', 'guard_name' => 'web']);
        //create permission for comment
        Permission::create(['name' => 'admin-create-comment', 'guard_name' => 'web']);
        Permission::create(['name' => 'admin-read-comment', 'guard_name' => 'web']);
        Permission::create(['name' => 'admin-update-comment', 'guard_name' => 'web']);
        Permission::create(['name' => 'admin-delete-comment', 'guard_name' => 'web']);
        //Author Permission
        // create permissions for posts
        Permission::create(['name' => 'author-create-posts', 'guard_name' => 'web']);
        Permission::create(['name' => 'author-read-posts', 'guard_name' => 'web']);
        Permission::create(['name' => 'author-update-posts', 'guard_name' => 'web']);
        Permission::create(['name' => 'author-delete-posts', 'guard_name' => 'web']);

        // create roles and assign existing permissions for admin
        $role1 = Role::create(['name' => 'admin']);
        $role1->givePermissionTo('admin-create-posts',);
        $role1->givePermissionTo('admin-read-posts',);
        $role1->givePermissionTo('admin-update-posts',);
        $role1->givePermissionTo('admin-delete-posts',);

        $role1->givePermissionTo('admin-create-category');
        $role1->givePermissionTo('admin-read-category');
        $role1->givePermissionTo('admin-update-category');
        $role1->givePermissionTo('admin-delete-category');

        $role1->givePermissionTo('admin-create-author');
        $role1->givePermissionTo('admin-read-author');
        $role1->givePermissionTo('admin-update-author');
        $role1->givePermissionTo('admin-delete-author');

        $role1->givePermissionTo('admin-create-comment');
        $role1->givePermissionTo('admin-read-comment');
        $role1->givePermissionTo('admin-update-comment');
        $role1->givePermissionTo('admin-delete-comment');

        $role2 = Role::create(['name' => 'author']);
        $role2->givePermissionTo('author-create-posts');
        $role2->givePermissionTo('author-read-posts');
        $role2->givePermissionTo('author-update-posts');
        $role2->givePermissionTo('author-delete-posts');

        // create demo users
        $user = \App\Models\Admin::factory()->create([
            'name' => 'Super Admin',
            'email' => 'superadmin@gmail.com',
            'password' => Hash::make('admin1234'),
            'profile_image' => fake()->imageUrl,
            // 'category_id' => 1
            //            'guard_name' => 'admin'
        ]);
        $user->assignRole($role1);

        $second_user = \App\Models\Admin::factory()->create([
            'name' => 'Author',
            'email' => 'author@gmail.com',
            'password' => Hash::make('author1234'),
            'profile_image' => fake()->imageUrl,
            // 'category_id' => 1
            //            'guard_name'=>'author'
        ]);
        $second_user->assignRole($role2);
    }
}
