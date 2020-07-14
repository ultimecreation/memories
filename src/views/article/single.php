<div class="container">
    <section id="articles">
        <h1>Blog</h1>

        <?php if($data['article']):?>
        
        <article class="article">
            <header class="article__header">
                <h2><?php echo $data['article']->title;?></h2>
                <span><?php echo $data['article']->cat_name;?></span>
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