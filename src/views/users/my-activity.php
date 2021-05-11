<div class="container">
	<h1>
        <a href="<?php echo siteUrl("/utilisateurs/mon-compte");?> ">Mon compte </a>
        / Mon activité recente
    </h1>

	<section id="user-activity">
		<ul>
			<?php foreach ($data['activities'] as $activity) :
				$date = (new DateTimeImmutable($activity->completed_at))->format("d/m/Y");
				$time = (new DateTimeImmutable($activity->completed_at))->format("H:i");

			?>
				<li>

					<p><?php echo "<span>le $date à $time </span><br>    - {$activity->action} :  $activity->points_gained pts ! "; ?>
						<?php if (!empty($activity->article_id)) : ?>
							<a href="<?php echo siteUrl("/blog/article/{$activity->article_id}"); ?>">Voir l'article</a>
						<?php endif; ?>
						<?php if (!empty($activity->product_id)) : ?>
							<a href="<?php echo siteUrl("/utilisateurs/mon-compte"); ?>">Achat <?php echo $activity->product_name ;?></a>
						<?php endif; ?>
					</p>
				</li>
			<?php endforeach; ?>
		</ul>
		<div class="pagination">
			<?php $user = getUserData(('id'));?>
			<ul class="pagination">
                <li>
					<?php if(isset($data['prev_page'])):?>
						<?php $prev = $data['prev_page'];?>
						<a href="<?php echo siteUrl("/utilisateurs/mon-activite/page/$prev/utilisateur/$user");?>"><i class="fa fa-arrow-left"></i></a>
					<?php endif;?>
                </li>
                <li>
                    <a ><?php echo "{$data['current_page']}/{$data['pages_count']}";?></a>
                    </li>
                <li>
					<?php if(isset($data['next_page'])):?>
						<?php $next = $data['next_page'];?>
						<a href="<?php echo siteUrl("/utilisateurs/mon-activite/page/$next/utilisateur/$user")?>">
						<i class="fa fa-arrow-right"></i>
					</a>
					<?php endif;?>
                </li>
            </ul>



           
        </div>
	</section>
<?php// debug($data);?>
</div>
