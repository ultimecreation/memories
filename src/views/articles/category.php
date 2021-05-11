<div class="container custom-padding">
<h1 class="single">
           
<h1>
            <a href="<?php echo siteUrl('/blog/page/1');?>">Blog</a> / <?php echo "Catégorie : {$data['cat_name']}";?>
        </h1>
    <section id="articles">
        

        <?php if($data['articles']):?>
        <?php foreach($data['articles'] as $article):?>
        <article class="article">
            <header class="article__header">
                <h2><a href="<?php echo siteUrl('/blog/article/').$article->article_id;?>"><?php echo $article->title;?></a></h2>
                <span><a href="<?php echo siteUrl('/blog/categorie/').$data['article']->category_id;?>"><?php echo $article->cat_name;?></a></span>
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