<div class="container">
<h1>Accueil Admin</h1>
<ul class="admin-summary">
    <li>
        
        <a href="<?php echo siteUrl('/admin/articles/page/1');?>">
            <div class="fa fa-newspaper-o fa-2x"></div>
            <span>Articles</span>
        </a>
    </li>
    <li> 
        
        <a href="<?php echo siteUrl('/admin/categories');?>">
            <div class="fa fa-object-group fa-2x"></div>
            <span>Catégories</span>
        </a>
    </li>
    <li>
       
        <a href="<?php echo siteUrl('/admin/utilisateurs/page/1');?>">
             <div class="fa fa-users fa-2x"></div>
             <span>Utilisateurs</span>
        </a>
    </li>
</ul>
<section id="awaiting-approval-articles">
    <h2>Articles en attente de publication</h2>
    <ol>
        <?php foreach($data['unpublishedArticles'] as $upArticle):?>
        <li>
            <h3><a href="<?php echo siteUrl("/admin/articles/voir-details/{$upArticle->id}");?>"><?php echo $upArticle->title;?></a></h3>

            <small></small>
        </li>
<?php endforeach;?>
    </ol>
</section>
<section id="most-active-articles">
    <h2>Top Articles les plus notés</h2>
    <ol>
        <?php foreach($data['mostActiveArticles'] as $msArticle):?>
        <li>
            <h3><a href="<?php echo siteUrl("/admin/articles/voir-details/{$msArticle->article_id}");?>"><?php echo $msArticle->title;?></a></h3>

            <small><?php echo " {$msArticle->note_number} notes | moyenne : {$msArticle->avg_rating}" ;?></small>
        </li>
<?php endforeach;?>
    </ol>
</section>
<section id="highest-rated-articles">
<h2>Top Articles les mieux notés</h2>
    <ol>
        <?php foreach($data['highestRatedArticles'] as $hrArticle):?>
        <li>
            <h3><a href="<?php echo siteUrl("/admin/articles/voir-details/{$hrArticle->article_id}");?>"><?php echo $hrArticle->title;?></a></h3>

            <small><?php echo "{$hrArticle->note_number} notes | moyenne : {$hrArticle->avg_rating}" ;?></small>
        </li>
<?php endforeach;debug($data['unpublishedArticles']);?>
    </ol>
</section>
</div>