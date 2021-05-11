<div class="container">
    
    <h1>
        <a href="<?php echo siteUrl('/admin/articles/page/1');?>">Categories</a> / Créer
    </h1>
    <section id="article-form">



    <form action="<?php echo siteUrl();?>/admin/categories/creer" method="POST" >
        <?php //debug($data['errors']);?>

        <div class="form-group">
            <label for="title">Nom</label>
            <input type="text" name="name" id="name" class="form-control" value="<?php echo $data['category']->name ?? '';?>">
            <?php 
            if(!empty($data['errors']['name'])) echo "<p class='danger'><i class='fa fa-exclamation-triangle'></i> {$data['errors']['name']}</p>";
             ?>
        </div>
        
       
        
        <button type="submit">Soumettre</button>

    </form>
        
    </section>
</div>