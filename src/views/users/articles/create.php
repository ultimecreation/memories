<div class="container">
    
    <h1>
        <a href="<?php echo siteUrl('/utilisateurs/mes-articles/page/1');?>">Mes articles</a> / Créer
    </h1>
    <section id="article-form">



    <form action="<?php echo siteUrl( "/utilisateurs/mes-articles/creer");?>" method="POST" enctype="multipart/form-data">
        <?php //debug($data['errors']);?>

        <div class="form-group">
            <label for="title">Titre</label>
            <input type="text" name="title" id="title" class="form-control" value="<?php echo $data['article']->title ?? '';?>">
            <?php 
            if(!empty($data['errors']['title'])) echo "<p class='danger'><i class='fa fa-exclamation-triangle'></i> {$data['errors']['title']}</p>";
             ?>
        </div>
        <div class="form-group">
            <label for="category_id">Catégorie</label>
            <select name="category_id" id="category_id" class="form-control">
                
                <?php foreach($data['categories'] as $category):?>
                    <option value="<?php echo $category->id;?>"><?php echo $category->name;?></option>
                <?php endforeach;?>
            </select>
        </div>

        <div class="form-group">
        <script src="https://cdn.ckeditor.com/4.14.1/standard/ckeditor.js"></script>
            <label for="text">Contenu</label>
            <textarea name="content" id="content" cols="30" rows="10" class="form-control"><?php echo $data['article']->content ?? '';?></textarea>
            <script >
            CKEDITOR.replace( 'content' );
            </script>
             <?php 
            if(!empty($data['errors']['content'])) echo "<p class='danger'><i class='fa fa-exclamation-triangle'></i> {$data['errors']['content']}</p>";
             ?>
        </div>
        
        <div class="form-group">
            <label for="img">Uploader une image</label>
            <input type="file" name="img" id="img">
        </div>
        
        <button type="submit">Soumettre</button>

    </form>
        
    </section>
</div>