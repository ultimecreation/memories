<div class="container custom-padding">
    <h1 class="single">Inscription</h1>
    <form action="<?php echo siteUrl();?>/inscription" method="POST">
        <?php // debug($data['user']);?>

        <div class="form-group">
            <label for="first_name">Prénom</label>
            <input type="text" name="first_name" id="first_name" class="form-control" value="<?php echo isset($data['user']->first_name) ?$data['user']->first_name:'';?>">
            <?php $first_name_err = isset($data['errors']['first_name'])?  $data['errors']['first_name']:"";
        echo "<p class='danger'>$first_name_err</p>";
        ?>
        </div>
        <div class="form-group">
            <label for="last_name">Nom</label>
            <input type="text" name="last_name" id="last_name" class="form-control" value="<?php echo isset($data['user']->last_name) ?$data['user']->last_name:'';?>">
            <?php $last_name_err = isset($data['errors']['last_name'])?  $data['errors']['last_name']:"";
        echo "<p class='danger'>$last_name_err</p>";
        ?>
        </div>
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" name="email" id="email" class="form-control" value="<?php echo isset($data['user']->email) ?$data['user']->email:'';?>">
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
        <div class="form-group">
            <label for="password_confirm">Confirmation mot de passe</label>
            <input type="password" name="password_confirm" id="password_confirm" class="form-control">
            <?php $password_confirm_err = isset($data['errors']['password_confirm'])?  $data['errors']['password_confirm']:"";
        echo "<p class='danger'>$password_confirm_err</p>";
        ?>
        </div>

        <button type="submit">Soumettre</button>

    </form>
</div>