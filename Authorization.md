### Authorization Module

The authentication and authorization module has five (or six if you use teams feature) tables in the database:
•	User – stores user records.
•	roles — stores role records.
•	permissions — stores permission records.
•	teams — stores teams records (Only if you use the teams feature).
•	role_user — stores polymorphic relations between roles and users.
•	permission_role — stores many-to-many relations between roles and permissions.
•	permission_user — stores polymorphic relations between users and permissions.

```

$adminRole = Role::where('name', 'admin')->first();
$editUserPermission = Permission::where('name', 'edit-user')->first();
$user = User::find(1);

$user->attachRole($adminRole);
// Or
$user->attachRole('admin');

$user->attachPermission($editUserPermission);
// Or
$user->attachPermission('edit-user');


$user->isAbleTo('edit-user');

$user->hasRole('admin');
$user->isA('guide');
$user->isAn('admin');


```
