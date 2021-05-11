<div class="container admin custom-padding">
    <?php if($data['article']):?>
    <h1>
        <a href="<?php echo siteUrl('/utilisateurs/mes-articles/page/1');?>">Articles</a> / <?php echo $data['article']->title;?>
    </h1>
    <section id="article">


        <article class="article">
            <header class="article__header">
                <span>
                    <a><?php echo $data['article']->cat_name;?></a>
                </span>
            </header>
            <div class="article__img">
                <img src="<?php echo publicUrl('/assets/uploads/').$data['article']->img;?>" alt="">
            </div>
            <div class="article__body">
                <p><?php echo $data['article']->content;?></p>
            </div>
            <div class="article__footer">
                <p>Publié par :
                    <span>
                        <?php echo $data['article']->author_name;?>
                    </span>
                    . le
                    <span>
                        <?php echo $data['article']->article_created_at;?>
                    </span>
                </p>
            </div>
            <div class="article__options">
                <form action="<?php echo siteUrl('/utilisateurs/mes-articles/supprimer');?>" method="POST">
                    <input type="hidden" name="idToDelete" value="<?php echo $data['article']->article_id ;?>" >
                    <button type="submit" class="btn-danger">Supprimer <i class="fa fa-trash"></i></button>
                </form>
                <?php if(!$data['article']->published === true):?>
                    <a href="<?php echo siteUrl('/utilisateurs/mes-articles/editer/').$data['article']->article_id;?>" class="btn-info">Éditer <i class="fa fa-edit"></i></a>
                <?php endif;?>
                
            </div>
        </article>

        <?php else:?>
        <p class="no-articles">Pas d'article à afficher</p>
        <?php endif;?>
    </section>
</div>