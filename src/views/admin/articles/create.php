<div class="container">
    
    <h1>
        <a href="<?php echo siteUrl('/admin/articles');?>">Articles</a> / Créer
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
            <label for="category">Catégorie</label>
            <select name="category" id="category" class="form-control">
                <?php foreach($data['categories'] as $category):?>
                    <option value="<?php echo $category->id;?>"><?php echo $category->name;?></option>
                <?php endforeach;?>
            </select>
        </div>

        <div class="form-group">
            <label for="text">Contenu</label>
            <textarea name="content" id="content" cols="30" rows="10" class="form-control"><?php echo $data['content'];?></textarea>
            <?php $content_err = isset($data['errors']['content'])?  $data['errors']['content']:"";
        echo "<p class='danger'>$content_err</p>";
        ?>
        </div>

        <input type="hidden" name="article_id" value="<?php echo $data['article_id'];?>">
        <button type="submit">Soumettre</button>

    </form>
        
    </section>
</div>