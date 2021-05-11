<div class="container admin custom-padding">
    <?php if ($data['user']) : ?>
        <h1>
            <a href="<?php echo siteUrl('/admin/utilisateurs/page/1'); ?>">Utilisateurs</a> / <?php echo "{$data['user']->first_name} {$data['user']->last_name}"; ?>
        </h1>
        <section id="user">

       

            <article class="user">
                <header class="user__header">
                    <span>
                        <a><?php echo $data['user']->email; ?></a>
                    </span>
                </header>
                <div class="user__body">
                    <p><?php echo "Compte créé le : {$data['user']->created_at}"; ?></p>
                </div>
                <div class="user__footer">
                    <?php $roles = '';
                        foreach ($data['user']->roles as $role) $roles .= "{$role->name} |";
                    ?>
                    <span> <?php echo substr($roles, 0, -1); ?> </span>
                </div>
                <div class="user__options">
                    <form action="<?php echo siteUrl('/admin/utilisateurs/supprimer'); ?>" method="POST">
                        <input type="hidden" name="idToDelete" value="<?php echo $data['user']->id; ?>">
                        <button type="submit" class="btn-danger">Supprimer <i class="fa fa-trash"></i></button>
                    </form>
                    <a href="<?php echo siteUrl('/admin/utilisateurs/editer/') . $data['user']->id; ?>" class="btn-info">Éditer <i class="fa fa-edit"></i></a>

                </div>
            </article>

        <?php else : ?>
            <p class="no-articles">Pas d'article à afficher</p>
        <?php endif; ?>
        </section>
</div>