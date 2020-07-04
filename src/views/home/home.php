<?php foreach ($data['users'] as $user) : ?>
    <h3><?php echo $user->name; ?></h3>

    <p><?php echo $user->email; ?></p>
    <hr>

<?php endforeach; ?>