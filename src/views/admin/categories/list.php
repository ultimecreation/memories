<div class="container admin custom-padding">
    <h1><a href="<?php echo siteUrl('/admin');?>">Administration</a> / Categories</h1>
    <section id="articles">
      
    <a href="<?php echo siteUrl('/admin/categories/creer');?>" class="btn-create">Créer une catégories</a>

        <?php if($data['categories']):?>
        <?php foreach($data['categories'] as $category):?>
        <article class="article">
            <header class="article__header">
                <h2><?php echo $category->name;?> | <a href="<?php echo siteUrl('/admin/categories/voir-details/').$category->id;?>"><small> Voir en détail <i class="fa fa-eye"></i></small></a></h2>
               
            </header>
            
        </article>
        <?php endforeach;?>
       
        <?php else:?>
        <p class="no-articles">Pas de catégorie à afficher</p>
        <?php endif;?>
    </section>
</div>