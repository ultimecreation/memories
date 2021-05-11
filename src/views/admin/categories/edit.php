<div class="container">
    
    <h1>
        <a href="<?php echo siteUrl('/admin/categories');?>">Categories</a> / Créer
    </h1>
    <section id="article-form">



    <form action="<?php echo siteUrl('/admin/categories/editer/').$data['category']->id;?>" method="POST" >
        <?php //debug($data['errors']);?>

        <div class="form-group">
            <label for="title">Nom</label>
            <input type="text" name="name" id="name" class="form-control" value="<?php echo $data['category']->name ?? '';?>">
        </div>
        <input type="hidden" name="idToUpdate" value="<?php echo $data['category']->id;?>">
       
        
        <button type="submit">Soumettre</button>

    </form>
        
    </section>
</div>