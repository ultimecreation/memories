<div class="container custom-padding">
    <h1 class="single">Connexion</h1>

    <form action="<?php echo siteUrl();?>/connexion" method="POST">
        <?php //debug($data['errors']);?>

        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" name="email" id="email" class="form-control">
            <?php $email_err = isset($data['errors']['email'])?  $data['errors']['email']:"";
        echo "<p class='danger'>$email_err</p>";
        ?>
        </div>
        <div class="form-group">
            <label for="password">Mot de passe</label>
            <input type="password" name="password" id="password" class="form-control">
            <?php $password_err = isset($data['errors']['password'])?  $data['errors']['password']:"";
        echo "<p class='danger'>$password_err</p>";
        ?>
        </div>

        <button type="submit">Soumettre</button>

    </form>
</div>