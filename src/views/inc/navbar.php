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
<li>
               <form action="<?php echo siteUrl('/blog/recherche');?>" method="GET" >
                <input type="text" name="terme" id="terme">
                <button type="submit"><i class="fa fa-search" aria-hidden="true"></i></button>
            </form>
            <?php if(isUserLogged()):?>
            <li>
                <a href="#"><i class="fa fa-user fa-2x"></i></a>
                
                    <ul>
                        <li><a href="<?php echo siteUrl();?>/deconnexion">Déconnexion</a></li>
                        <hr>
                        <?php if(in_array('ADMIN',getUserData('roles'))):?>
                            <li><a href="<?php echo siteUrl('/admin');?>">Administration</a></li>
                            <li><a href="">lien 2</a></li>
                            <li><a href="">lien 3</a></li> 
                        <?php endif;?>
                    </ul>
               
            </li>
            <?php else:?>
            <li><a href="<?php echo siteUrl();?>/connexion">Connexion</a></li>
            <li><a href="<?php echo siteUrl();?>/inscription">Inscription</a></li>
            <?php endif;?>
            
            </li>
        </ul>
    </nav>
    <div id="hamburger">
        <span></span>
        <span></span>
        <span></span>

    </div>
</div>