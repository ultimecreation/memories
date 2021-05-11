<div class="container custom-padding">
    <?php if($data['category']):?>
    <h1>
        <a href="<?php echo siteUrl('/admin/categories');?>">Catégories</a> / <?php echo $data['category']->name;?>
    </h1>
    <section id="article">


        <article class="article">
           
            <div class="article__body">
                <p><?php echo $data['category']->name;?></p>
            </div>
           
            <div class="article__options">
                <form action="<?php echo siteUrl('/admin/categories/supprimer');?>" method="POST">
                    <input type="hidden" name="idToDelete" value="<?php echo $data['category']->id ;?>" >
                    <button type="submit" class="btn-danger">Supprimer <i class="fa fa-trash"></i></button>
                </form>
                <a href="<?php echo siteUrl('/admin/categories/editer/').$data['category']->id;?>" class="btn-info">Éditer <i class="fa fa-edit"></i></a>
                
            </div>
        </article>

        <?php else:?>
        <p class="no-articles">Pas d'article à afficher</p>
        <?php endif;?>
    </section>
</div>