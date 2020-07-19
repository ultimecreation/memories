<div class="container">
    <h1>Articles</h1>
    <section id="articles">
        
    <a href="<?php echo siteUrl('/admin/articles/creer');?>">Créer un article</a>

        <?php if($data['articles']):?>
        <?php foreach($data['articles'] as $article):?>
        <article class="article">
            <header class="article__header">
                <h2><?php echo $article->title;?> | <a href="<?php echo siteUrl('/admin/articles/voir-details/').$article->article_id;?>"><small> Voir en détail <i class="fa fa-eye"></i></small></a></h2>
                <span><a><?php echo $article->cat_name;?></a></span>
            </header>
            <div class="article__footer"> 
               <p>Publié par : 
               <span>
                    <?php echo $article->author_name;?>
                </span>
                . le 
                <span>
                    <?php echo $article->article_created_at;?>
                </span>
               </p>
            </div>
            <div class="article-options">
               <a href=""></a>
              
            </div>
        </article>
        <?php endforeach;?>
        <?php if($data['pagination']):?>
            <ul class="pagination">
                <li>
                    <?php if(isset($data['pagination']->previous_page)):?>
                        <a href="<?php echo siteUrl('/admin/articles/page/').$data['pagination']->previous_page;?>"><i class="fa fa-arrow-left"></i></a>
                    <?php endif;?>
                </li>
                <li>
                    <a ><?php echo "{$data['pagination']->current_page}/{$data['pagination']->total_pages}";?></a>
                    </li>
                <li>
                    <?php if(isset($data['pagination']->next_page)):?>
                        <a href="<?php echo siteUrl('/admin/articles/page/').$data['pagination']->next_page;?>"><i class="fa fa-arrow-right"></i></a>
                    <?php endif;?>
                </li>
            </ul>
        <?php endif;?>
        <?php else:?>
        <p class="no-articles">Pas d'articles à afficher</p>
        <?php endif;?>
    </section>
</div>