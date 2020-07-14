<div class="container">
    <?php if($data['count']>1):?>
        <h1><?php echo $data['count'];?> Résultats pour <?php echo $data['term'];?></h1>
        <?php elseif($data['count']==1):?>
            <h1><?php echo $data['count'];?> Résultat pour <?php echo $data['term'];?></h1>
    
    <section id="articles">
        

        <?php if($data['articles']):?>
        <?php foreach($data['articles'] as $article):?>
        <article class="article">
            <header class="article__header">
                <h2><a href="<?php echo siteUrl('/blog/article/').$article->article_id;?>"><?php echo $article->title;?></a></h2>
                <span><a href="<?php echo siteUrl('/blog/categorie/').$article->category_id;?>"><?php echo $article->cat_name;?></a></span>
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
        <?php else:?>
            <h1><?php echo $data['count'];?> Résultat pour <?php echo $data['term'];?></h1>
        <?php endif;?>
    </section>
</div>