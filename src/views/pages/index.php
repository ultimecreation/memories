<div id="front">
    <header>
        <div class="overlay"> 
            <img src="<?php echo publicUrl('/assets/img/header-bg.jpg');?>" alt="hero image" width="100%" height="auto">
            <div class="text">
                <h1>Découvrons ensemble <br>des lieux merveilleux</h1>
                <p>
                    Notez, Likez,<br>
                    Gagnez des points tous les jours<br>
                    Et publiez vos souvenirs de vacances
                </p>
                <a href="<?php echo siteUrl('/connexion');?>">Je m'inscris !</a>
            </div>
            
        </div>
       

    </header>

    <?php if($data['lastArticles']):?>
    <section id="last-news">
        <div class="container">
            <?php foreach($data['lastArticles'] as $lastArticle):?>
            <article>
                <div class="article__header">
                    <img src="<?php echo publicUrl('/assets/uploads/').$lastArticle->img;?>" alt="<?php echo $lastArticle->img;?>" loading="lazy" height="218" width="328">
                    <small>&Acirc; la Une</small>
                </div>
                <div class="article__text">
                    <p><?php echo truncateText($lastArticle->content,50) ;?></p>
                </div>
                <div class="article__footer">
                    <a href="<?php echo siteUrl('/blog/article/').$lastArticle->article_id;?>">Lire la suite</a>
                </div>
            </article>
            <?php endforeach;?>
        </div> 
       
    </section> 
     <div class="front__blog-link">
        <a href="<?php echo siteUrl('/blog/page/1');?>" >Voir tous les articles</a>
        </div>
    <?php endif;?>

    <?php if($data['mostActiveUsers']):?>
    <section id="most-active-users">
        <div class="container">
           
            <div class="right">
                <ul>
                    <?php foreach($data['mostActiveUsers'] as $mostActiveUser):?>
                        <?php if($mostActiveUser->username !=="admin admin"):?>
                            <li>
                            <p><?php echo "{$mostActiveUser->username} : {$mostActiveUser->vote_number} votes";?></p>
                        </li>
                        <?php endif;?>
                    <?php endforeach;?>
                </ul>
            </div> 
            <div class="left">
                <h2>Top </h2><h3>Votants</h3>
            </div>
        </div>
    </section>
    <?php endif;?>

    
    <?php if($data['mostActiveArticles']):?>
    <section id="most-artive-articles">
        <div class="container">
            <?php foreach($data['mostActiveArticles'] as $mostActiveArticles):?>
            <article>
                <div class="article__header">
                    <img src="<?php echo publicUrl('/assets/uploads/').$mostActiveArticles->img;?>" alt="<?php echo $mostActiveArticles->img;?>" loading="lazy" height="218" width="328">
                    <small>Les plus populaires</small>
                </div>
                <div class="article__text">
                    <p><?php echo truncateText($mostActiveArticles->content,150) ;?></p>
                </div>
                <div class="article__footer">
                    <a href="<?php echo siteUrl('/blog/article/').$mostActiveArticles->article_id;?>">Lire la suite</a>
                </div>
            </article>
            <?php endforeach;?>
        </div>
    </section>
    <?php endif;?>

    <?php if($data['usersActions']):?>
    <section id="users-actions">
        <div class="container">
            <div class="left">
                <h3>Activité </h3><h3>Ré&ccedil;ente</h3>
            </div>
            <div class="right">
                <ul>
                    <?php foreach($data['usersActions'] as $usersAction):?>
                       
                            <li>
                            <p><?php echo "{$usersAction->username} : {$usersAction->action}";?></p>
                        </li>
                       
                    <?php endforeach;?>
                </ul>
            </div>
            
        </div>
    </section>
    <?php endif;?>

    <?php if($data['highestRatedArticles']):?>
    <section id="highest-rated-articles">
        <div class="container">
            <?php foreach($data['highestRatedArticles'] as $highestRatedArticles):?>
            <article>
                <div class="article__header">
                    <img src="<?php echo publicUrl('/assets/uploads/').$highestRatedArticles->img;?>" alt="<?php echo $highestRatedArticles->img;?>" loading="lazy" height="218" width="328">
                    <small>Les mieux notés</small>
                </div>
                <div class="article__text">
                    <p><?php echo truncateText($highestRatedArticles->content,150) ;?></p>
                </div>
                <div class="article__footer">
                    <a href="<?php echo siteUrl('/blog/article/').$highestRatedArticles->article_id;?>">Lire la
                        suite</a>
                </div>
            </article>
            <?php endforeach;?>
        </div>
    </section>
    <?php endif;?>

   <?php//debug($data['usersActions']);?>
</div>