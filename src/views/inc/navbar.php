<div id="main__nav">
    <div class="logo">
       <h1>My - Website</h1>
    </div>
    <nav>
        
        <ul>
            <li><a href="<?php echo siteUrl();?>/">Accueil</a></li>
            <li><a href="<?php echo siteUrl();?>/blog">Blog</a></li>
        </ul>
        <ul>
        <?php if(isUserLogged()):?>
            <li><a href="<?php echo siteUrl();?>/deconnexion">Déconnexion</a></li>
        <?php else:?>
            <li><a href="<?php echo siteUrl();?>/connexion">Connexion</a></li>
            <li><a href="<?php echo siteUrl();?>/inscription">Inscription</a></li>
        <?php endif;?>
           
        </ul>
    </nav>
</div>

