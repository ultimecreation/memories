<div class="container">
<?php //debug($data); ?>
    <h1>
        <a href="<?php echo siteUrl('/admin/utilisateurs/page/1');?>">Utilisateurs</a> /<a href="<?php echo siteUrl("/admin/utilisateurs/voir-details/{$data['user']->id}");?>"><?php echo " {$data['user']->first_name} {$data['user']->last_name} ";?></a> / Éditer
    </h1>
    <section id="article-form">



    <form action="<?php echo siteUrl('/admin/utilisateurs/editer/').$data['user']->id;?>" method="POST">
        <?php //debug($data['errors']);?>

        <div class="form-group">
            <label for="last_name">Nom</label>
            <input type="text" name="last_name" id="last_name" class="form-control" value="<?php echo $data['user']->last_name;?>">
            <?php $last_name_err = isset($data['errors']['last_name'])?  $data['errors']['last_name']:"";
        echo "<p class='danger'>$last_name_err</p>";
        ?>
        </div>

        <div class="form-group">
            <label for="first_name">Prénom</label>
            <input type="text" name="first_name" id="first_name" class="form-control" value="<?php echo $data['user']->first_name;?>">
            <?php $first_name_err = isset($data['errors']['first_name'])?  $data['errors']['first_name']:"";
        echo "<p class='danger'>$first_name_err</p>";
        ?>
        </div>

        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" name="email" id="email" class="form-control" value="<?php echo $data['user']->email;?>">
            <?php $email_err = isset($data['errors']['first_name'])?  $data['errors']['email']:"";
        echo "<p class='danger'>$email_err</p>";
        ?>
        </div>


        <div class="form-group">
            <label for="category">Rôles</label>
            <select name="roles[]" id="roles" class="form-control" multiple>
                <?php
                    $tmp_user_roles = [];
                    foreach($data['user']->roles as $user_role) array_push($tmp_user_roles,$user_role->name);
                ?>
                <?php foreach($data['roles'] as $role):?>
                    <option value="<?php echo $role->id;?>" 
                            <?php echo $selected = in_array($role->name,$tmp_user_roles)?'selected':'' ;?>>
                        <?php echo $role->name;?>
                    </option>
                      
                   
                <?php endforeach;?>
            </select>

        <input type="hidden" name="user_id" value="<?php echo $data['user']->id;?>">
        <button type="submit">Soumettre</button>

    </form>
        
    </section>
</div>