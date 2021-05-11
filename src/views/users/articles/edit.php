<div class="container">
    
    <h1>
        <a href="<?php echo siteUrl('/utilisateurs/mes-articles/page/1');?>">Mes articles</a> / Éditer
    </h1>
    <section id="article-form">



    <form action="<?php echo siteUrl('/utilisateurs/mes-articles/editer/').$data['article']->article_id;?>" method="POST" enctype="multipart/form-data">
        <?php //debug($data['errors']);?>

        <div class="form-group">
            <label for="title">Titre</label>
            <input type="text" name="title" id="title" class="form-control" value="<?php echo $data['article']->title;?>">
            <?php $title_err = isset($data['errors']['title'])?  $data['errors']['title']:"";
        echo "<p class='danger'>$title_err</p>";
        ?>
        </div>
        <div class="form-group">
            <label for="category">Catégorie</label>
            <select name="category_id" id="category_id" class="form-control">
                <option value="<?php echo $data['article']->category_id;?>" selected><?php echo $data['article']->cat_name;?></option>
                <?php  debug($data['categories']); foreach($data['categories'] as $category):?>
                    <option value="<?php echo $category->id;?>"><?php echo $category->name;?></option>
                <?php endforeach;?>
            </select>
        </div>

        <div class="form-group">
            <label for="text">Contenu</label>
             <script src="https://cdn.ckeditor.com/4.14.1/standard/ckeditor.js"></script>
            <textarea name="content" id="content" cols="30" rows="10" class="form-control"><?php echo $data['article']->content;?></textarea>
            <script >
            CKEDITOR.replace( 'content' );
            
            </script>
            <?php $content_err = isset($data['errors']['content'])?  $data['errors']['content']:"";
        echo "<p class='danger'>$content_err</p>";
        ?>
        </div>

        <div class="form-group">
            <label for="img">Uploader une image</label>
            <input type="file" name="img" id="img">
        </div>
        <div class="form-group">
            <label for="to_be_published">Demander la Publication</label>
            <input type="checkbox" name="to_be_published" id="to_be_published">
        </div>
        <input type="hidden" name="article_id" value="<?php echo $data['article']->article_id;?>">
        <input type="hidden" name="old_img" value="<?php echo $data['article']->img;?>">
        <button type="submit">Soumettre</button>

    </form>
        
    </section>
</div>