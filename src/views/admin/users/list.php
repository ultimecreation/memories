<div class="container">
    <h1>Utilisateurs</h1>
    <section id="users">

        <?php if($data['users']):?>
        <?php foreach($data['users'] as $user):?>
        <article class="user">
            <header class="user__header">
                <h2><?php echo "{$user->first_name} {$user->last_name}";?> | <a
                        href="<?php echo siteUrl('/admin/users/voir-details/').$user->id;?>"><small> Voir en détail <i
                                class="fa fa-eye"></i></small></a></h2>

            </header>
            <div class="user__footer">
                <p>
                    Rôle(s) :
                    <?php foreach($user->roles as $role):?>
                    <span><a><?php echo ucfirst(strtolower($role->name));?></a></span>
                    <?php endforeach;?>
                </p>
            </div>
            <div class="user-options">


            </div>
        </article>
        <?php endforeach;?>
        <?php if($data['pagination']):?>
        <ul class="pagination">
            <li>
                <?php if(isset($data['pagination']->previous_page)):?>
                <a href="<?php echo siteUrl('/admin/articles/page/').$data['pagination']->previous_page;?>"><i
                        class="fa fa-arrow-left"></i></a>
                <?php endif;?>
            </li>
            <li>
                <a><?php echo "{$data['pagination']->current_page}/{$data['pagination']->total_pages}";?></a>
            </li>
            <li>
                <?php if(isset($data['pagination']->next_page)):?>
                <a href="<?php echo siteUrl('/admin/articles/page/').$data['pagination']->next_page;?>"><i
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