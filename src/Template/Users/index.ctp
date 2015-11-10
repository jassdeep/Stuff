<h1>Current users</h1>
<table>
    <tr>
        <th>Id</th>
        <th>Name</th>
        <th>Role</th>
    </tr>

<!-- Here's where we loop through our $users query object, printing out user info -->

    <?php foreach ($users as $user): ?>
    <tr>
        <td><?= $user->id ?></td>
        <td>
            <?= $user->username ?>
        </td>
        <td><?= $user->role ?></td>
    </tr>
    <?php endforeach; ?>

</table>