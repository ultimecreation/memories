<div class="container">
<?php if($data['article']):?>
        <h1>
            <a href="<?php echo siteUrl('/blog');?>">Blog</a> / <?php echo $data['article']->title;?>
        </h1>
    <section id="article">
        

        
        <article class="article">
            <header class="article__header">
                
            <span><a href="<?php echo siteUrl('/blog/categorie/').$data['article']->category_id;?>"><?php echo $data['article']->cat_name;?></a></span>
            </header>
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
        </article>
     
        <?php else:?>
        <p class="no-articles">Pas d'article à afficher</p>
        <?php endif;?>
    </section>
</div>