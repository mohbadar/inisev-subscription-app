<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\Permission;
use App\Models\User;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $password = bcrypt('secret');

       $user =  User::create([
            'name' => 'Administrator',
            'email' => 'admin@test.com',
            'password' => $password,
        ]);


        $owner = Role::create([
            'name' => 'owner',
            'display_name' => 'Project Owner', // optional
            'description' => 'User is the owner of a given project', // optional
        ]);

        $admin = Role::create([
            'name' => 'admin',
            'display_name' => 'User Administrator', // optional
            'description' => 'User is allowed to manage and edit other users', // optional
        ]);


        $createPost = Permission::create([
            'name' => 'create-post',
            'display_name' => 'Create Posts', // optional
            'description' => 'create new blog posts', // optional
            ]);

        $editUser = Permission::create([
            'name' => 'edit-user',
            'display_name' => 'Edit Users', // optional
            'description' => 'edit existing users', // optional
        ]);



        //assignment

        $admin->attachPermission($createPost); // parameter can be a Permission object, array or id
        // equivalent to $admin->permissions()->attach([$createPost->id]);

        $owner->attachPermissions([$createPost, $editUser]); // parameter can be a Permission object, array or id
        // equivalent to $owner->permissions()->attach([$createPost->id, $editUser->id]);

        $owner->syncPermissions([$createPost, $editUser]); // parameter can be a Permission object, array or id
        // equivalent to $owner->permissions()->sync([$createPost->id, $editUser->id]);


        // $admin->detachPermission($createPost); // parameter can be a Permission object, array or id
        // // equivalent to $admin->permissions()->detach([$createPost->id]);

        // $owner->detachPermissions([$createPost, $editUser]); // parameter can be a Permission object, array or id
        // // equivalent to $owner->permissions()->detach([$createPost->id, $editUser->id]);



        $user->attachRole($admin); // parameter can be a Role object, array, id or the role string name
        // equivalent to $user->roles()->attach([$admin->id]);

        // $user->attachRoles([$admin, $owner]); // parameter can be a Role object, array, id or the role string name
        // // equivalent to $user->roles()->attach([$admin->id, $owner->id]);

        // $user->syncRoles([$admin->id, $owner->id]);
        // // equivalent to $user->roles()->sync([$admin->id, $owner->id]);

        // $user->syncRolesWithoutDetaching([$admin->id, $owner->id]);
        // // equivalent to $user->roles()->syncWithoutDetaching([$admin->id, $owner->id]);


        // $user->attachPermission($editUser); // parameter can be a Permission object, array, id or the permission string name
        // // equivalent to $user->permissions()->attach([$editUser->id]);

        // $user->attachPermissions([$editUser, $createPost]); // parameter can be a Permission object, array, id or the permission string name
        // // equivalent to $user->permissions()->attach([$editUser->id, $createPost->id]);

        // $user->syncPermissions([$editUser->id, $createPost->id]);
        // // equivalent to $user->permissions()->sync([$editUser->id, createPost->id]);

        // $user->syncPermissionsWithoutDetaching([$editUser, $createPost]); // parameter can be a Permission object, array or id
        //     // equivalent to $user->permissions()->syncWithoutDe


        // $user->detachPermission($createPost); // parameter can be a Permission object, array, id or the permission string name
        //     // equivalent to $user->permissions()->detach([$createPost->id]);

        //     $user->detachPermissions([$createPost, $editUser]); // parameter can be a Permission object, array, id or the permission string name
        // // equivalent to $user->permissions()->detach([$createPost->id, $editUser->id]);



        // $user->hasRole('owner');   // false
        // $user->hasRole('admin');   // true
        // $user->isAbleTo('edit-user');   // false
        // $user->isAbleTo('create-post'); // tru

        // $user->hasRole(['owner', 'admin']);       // true
        // $user->isAbleTo(['edit-user', 'create-post']); // true

        // $user->hasRole('owner|admin');       // true
        // $user->isAbleTo('edit-user|create-post'); // true

        // $user->hasRole(['owner', 'admin']);             // true
        // $user->hasRole(['owner', 'admin'], true);       // false, user does not have admin role
        // $user->isAbleTo(['edit-user', 'create-post']);       // true
        // $user->isAbleTo(['edit-user', 'create-post'], true); // false, user does not have edit-user permission


        // Laratrust::hasRole('role-name');
        // Laratrust::isAbleTo('permission-name');

        // // is identical to

        // Auth::user()->hasRole('role-name');
        // Auth::user()->hasPermission('permission-name');



    }
}
