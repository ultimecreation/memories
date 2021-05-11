<div class="container custom-padding">
<?php if($data['article']):?>
        <h1 class="single">
            <a href="<?php echo siteUrl('/blog/page/1');?>">Blog</a> / <?php echo $data['article']->title;?>
        </h1>
    <section id="article">
        

        
        <article class="article">
            <header class="article__header">
                
            <span><a href="<?php echo siteUrl('/blog/categorie/').$data['article']->category_id;?>"><?php echo $data['article']->cat_name;?></a></span>
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
               <?php 
                    if($data['notes']->note_number==0) $header_note = "Pas de note";
                    elseif($data['notes']->note_number==1) $header_note = "{$data['notes']->note_number} note";
                    else $header_note = "{$data['notes']->note_number} notes";
               ?>
               <div class="article__rating "><p><?php echo $header_note;?> </p>
               <div class="rated">
                        <?php for($i=1;$i<=5;$i++):?>
                            <?php if($i<=$data['notes']->avg_rating):?>
                                 <p class="fa fa-star"></p>
                            <?php else:?>
                                <p class="fa fa-star-o"></p>
                            <?php endif;?>
                        <?php endfor;?>
                       
                    </div>
                    <?php 
                        $avg_note = $data['notes']->avg_rating ?? 0;
                    ?>
                    <p><?php echo ($avg_note!=0) ? "Note globale : {$avg_note}/5":"";?> </p>
                    
                </div>
               
            </div>
        </article>
        <div class="article__review">
            <h3>Laisser une note</h3>
            <form action="<?php echo siteUrl("/blog/article/{$data['article']->article_id}/note");?>" method="POST">
<label for="rating">Note: </label>
                <div class="ratings">

                <input type="radio" name="rating" value=1 class="fa fa-star-o">
                <input type="radio" name="rating" value=2 class="fa fa-star-o">
                <input type="radio" name="rating" value=3 class="fa fa-star-o">
                <input type="radio" name="rating" value=4 class="fa fa-star-o">
                <input type="radio" name="rating" value=5 class="fa fa-star-o">
                </div>
                <input type="hidden" name="article_id" value="<?php echo $data['article']->article_id;?>">
                <button type="submit">Enregistrer</button>
            </form>
        </div>
     
        <?php else:?>
        <p class="no-articles">Pas d'article à afficher</p>
        <?php endif;?>
    </section>
</div>