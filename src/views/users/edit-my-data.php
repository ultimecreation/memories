<div class="container">
    <h1><a href="<?php echo siteUrl('/utilisateurs/mon-compte');?>">Mon compte</a> / Modifier mes données</h1>
    <form action="<?php echo siteUrl();?>/utilisateurs/editer-mes-informations" method="POST">
        <?php debug($data['user']->first_name);?>

        <div class="form-group">
            <label for="first_name">Prénom</label>
            <input type="text" name="first_name" id="first_name" class="form-control" value="<?php echo $data['user']->first_name;?>">
            <?php $first_name_err = isset($data['errors']['first_name'])?  $data['errors']['first_name']:"";
                echo "<p class='danger'>$first_name_err</p>";
            ?>
        </div>
        <div class="form-group">
            <label for="last_name">Nom</label>
            <input type="text" name="last_name" id="last_name" class="form-control" value="<?php echo $data['user']->last_name;?>">
            <?php $last_name_err = isset($data['errors']['last_name'])?  $data['errors']['last_name']:"";
        echo "<p class='danger'>$last_name_err</p>";
        ?>
        </div>
        
      

        <button type="submit">Soumettre</button>

    </form>
</div>