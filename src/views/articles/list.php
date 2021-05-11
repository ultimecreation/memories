<div class="container custom-padding">
    <h1 class="single">Blog</h1>
    <section id="articles">


        <?php if($data['articles']):?>
        <?php foreach($data['articles'] as $article):?>
        <article class="article">
            <header class="article__header">
                <h2><a
                        href="<?php echo siteUrl('/blog/article/').$article->article_id;?>"><?php echo $article->title;?></a>
                </h2>
                <span><a
                        href="<?php echo siteUrl('/blog/categorie/').$article->category_id;?>"><?php echo $article->cat_name;?></a></span>
            </header>
            <div class="article__body">
                <p><?php echo $article->content;?></p>
            </div>
            <div class="article__footer">
                <p>
                    <span>Publié par :
                    
                        <?php echo $article->author_name;?>.
                    </span>
                    <span>Le
                    
                        <?php echo $article->article_created_at;?>
                    </span>
                </p>
                <?php 
               
                    if($article->notes->note_number==0) $header_note = "Pas de note";
                    elseif($article->notes->note_number==1) $header_note = "{$article->notes->note_number} note";
                    else $header_note = "{$article->notes->note_number} notes";
               ?>
                <div class="article__rating">
                    <p><?php echo $header_note;?> </p>

                    <div class="rated">
                        <?php for($i=1;$i<=5;$i++):?>
                            <?php if($i<= $article->notes->avg_rating):?>
                                 <p class="fa fa-star"></p>
                            <?php else:?>
                                <p class="fa fa-star-o"></p>
                            <?php endif;?>
                        <?php endfor;?>
                       
                    </div>
                    <?php 
                        $avg_note = $article->notes->avg_rating ?? 0;
                    ?>
                    <p><?php echo $avg_note>0 ? "Note globale : {$avg_note}/5":"";?> </p>
                </div>

            </div>
        </article>
        <?php endforeach;?>
        <?php if($data['pagination']):?>
        <ul class="pagination">
            <li>
                <?php if(isset($data['pagination']->previous_page)):?>
                <a href="<?php echo siteUrl('/blog/page/').$data['pagination']->previous_page;?>"><i
                        class="fa fa-arrow-left"></i></a>
                <?php endif;?>
            </li>
            <li>
                <a><?php echo "{$data['pagination']->current_page}/{$data['pagination']->total_pages}";?></a>
            </li>
            <li>
                <?php if(isset($data['pagination']->next_page)):?>
                <a href="<?php echo siteUrl('/blog/page/').$data['pagination']->next_page;?>"><i
                        class="fa fa-arrow-right"></i></a>
                <?php endif;?>
            </li>
        </ul>
        <?php endif;?>
        <?php else:?>
        <p class="no-articles">Pas d'articles à afficher</p>
        <?php endif;?>
    </section>
</div>