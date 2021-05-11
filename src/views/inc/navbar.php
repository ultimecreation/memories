<div id="main__nav">
    <div class="logo">
        <h1><a href="<?php echo siteUrl();?>/">Memories</a></h1>
    </div>
    <nav>

        <ul>
            <li><a href="<?php echo siteUrl();?>/">Accueil</a></li>
            <li><a href="<?php echo siteUrl();?>/blog/page/1">Blog</a></li>
        </ul>
        <ul>
            <li>
               <form action="<?php echo siteUrl('/blog/recherche');?>" method="POST" >
                    <input type="text" name="terme" id="terme" aria-label="terme">
                    <button type="submit" aria-label="bouton"><i class="fa fa-search" aria-hidden="true"></i></button>
                </form>
            
            <li>
                <a href="#" aria-label="utilisateur"><i class="fa fa-user fa-2x"></i></a>
               
                    <ul> 
                        <?php if(isUserLogged()):?>
                        
                            <li><a href="<?php echo siteUrl();?>/deconnexion">Déconnexion</a></li>
                            
                            <?php if(in_array('USER',getUserData('roles'))):?>
                                
                                <li><a href="<?php echo siteUrl('/utilisateurs/mon-compte');?>">Mon compte</a></li>
                            <?php endif;?>
                            <?php if(in_array('ADMIN',getUserData('roles'))):?>
                                
                                    <li><a href="<?php echo siteUrl('/admin');?>">Administration</a></li>
                            <?php endif;?>
                         <?php else:?>  
                         <li><a href="<?php echo siteUrl();?>/connexion">Connexion</a></li>
                        <li><a href="<?php echo siteUrl();?>/inscription">Inscription</a></li>
                    </ul>
                     <?php endif;?>
            </li>
            
           
           
            
            </li>
        </ul>
    </nav>
   
    <div id="hamburger">
        <span></span>
        <span></span>
        <span></span>

    </div>
</div> <?php //debug(getUserData('roles'));?>