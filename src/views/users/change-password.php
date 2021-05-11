<div class="container">
    <h1><a href="<?php echo siteUrl('/utilisateurs/mon-compte');?>">Mon compte</a> / Modifier mon mot de passe</h1>

    <form action="<?php echo siteUrl();?>/utilisateurs/changement-mot-de-passe" method="POST">
        <?php //debug($data['errors']);?>

        <div class="form-group">
            <label for="password">Mot de passe</label>
            <input type="password" name="password" id="password" class="form-control">
            <?php $password_err = isset($data['errors']['password'])?  $data['errors']['password']:"";
        echo "<p class='danger'>$password_err</p>";
        ?>
        </div>
        <div class="form-group">
            <label for="confirm_password">Confirmation mot de passe</label>
            <input type="password" name="confirm_password" id="confirm_password" class="form-control">
            <?php $confirm_password_err = isset($data['errors']['confirm_password'])?  $data['errors']['confirm_password']:"";
        echo "<p class='danger'>$confirm_password_err</p>";
        ?>
        </div>

        <button type="submit">Soumettre</button>

    </form>
</div>