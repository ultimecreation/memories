<div class="container">
    <section id="articles">
        <h1>Blog</h1>

        <?php if($data['articles']):?>
        <?php foreach($data['articles'] as $article):?>
        <article class="article">
            <header class="article__header">
                <h2><a href="<?php echo siteUrl('/blog/article/').$article->article_id;?>"><?php echo $article->title;?></a></h2>
                <span><?php echo $article->cat_name;?></span>
            </header>
            <div class="article__body">
                <p><?php echo $article->content;?></p>
            </div>
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
        </article>
        <?php endforeach;?>
        <?php else:?>
        <p class="no-articles">Pas d'articles à afficher</p>
        <?php endif;?>
    </section>
</div>