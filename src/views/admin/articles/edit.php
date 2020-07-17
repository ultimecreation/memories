<div class="container">
    
    <h1>
        <a href="<?php echo siteUrl('/blog');?>">Blog</a> / <?php echo $data['article']->title;?>
    </h1>
    <section id="article-form">



    <form action="<?php echo siteUrl();?>/connexion" method="POST">
        <?php //debug($data['errors']);?>

        <div class="form-group">
            <label for="title">Titre</label>
            <input type="text" name="title" id="title" class="form-control">
            <?php $title_err = isset($data['errors']['title'])?  $data['errors']['title']:"";
        echo "<p class='danger'>$title_err</p>";
        ?>
        </div>

        <div class="form-group">
            <label for="password">Mot de passe</label>
            <textarea name="content" id="content" cols="30" rows="10"><?php echo $data['content'];?></textarea>
            <?php $content_err = isset($data['errors']['content'])?  $data['errors']['content']:"";
        echo "<p class='danger'>$content_err</p>";
        ?>
        </div>

        <select name="category" id=""></select>
        <div class="form-group">
            <label for="password">Mot de passe</label>
            <input type="password" name="password" id="password" class="form-control">
            <?php $password_err = isset($data['errors']['password'])?  $data['errors']['password']:"";
        echo "<p class='danger'>$password_err</p>";
        ?>
        </div>
        <input type="hidden" name="article_id" value="<?php echo $data['article_id'];?>">
        <button type="submit">Soumettre</button>

    </form>
        
    </section>
</div>