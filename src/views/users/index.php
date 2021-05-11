<div class="container custom-padding">
	<h1>Mon compte</h1>
	<?php if(isset($_COOKIE['success-purchase'])):?>
    <?php 
    $updatedUser = (object) getUserData();
    $updatedUser->total_points = $_COOKIE['total-points'];
    setUserData($updatedUser);

    ?>
		<div class="alert-success">
            <?php echo $_COOKIE['success-purchase'];?>
        </div>
	<?php endif; ?>
	<br>
	<a href="<?php echo siteUrl("/utilisateurs/mes-articles/page/1");?> ">Mes articles</a>

	<section id="buy-credits">
		<h2>Acheter des crédits</h2>
		
		<div>
			
			<select name="amount" id="amount">
				<option value="">Sélectionner</option>
			<?php foreach($data['products'] as $product):?>
				<option value="<?php echo $product->price;?>" data-product_name="<?php echo $product->name;?>" data-product_id="<?php echo $product->id;?>"><?php echo "{$product->value} points pour {$product->price}€ ( {$product->name} )";?></option>

			<?php endforeach;?>
			</select>

			<div id="paypal-button-container" data-user_id="<?php echo getUserData('id');?>"></div>
		</div>
	</section>
	
	<section id="user-data">
		<h2>Mes informations</h2>
		<ul>
			<li><span>Prénom :</span> <?php echo $data['user']->first_name; ?></li>
			<li><span>Nom :</span> <?php echo $data['user']->last_name; ?></li>
			<li><span>Email : </span> <?php echo getUserData('email'); ?></li>
			<li><span>Points : </span> <?php echo getUserData('total_points')." pts"; ?></li>
			<li>
				<?php
				$roleHeading =  (count(getUserData('roles')) > 1) ? "Mes rôles" : "Mon rôle";
				$roleString = '';
				foreach (getUserData('roles') as $role) $roleString .= " $role |";
				echo "<span>$roleHeading :</span> " . substr($roleString, 0, -1);
				?>
			</li>
			<li>
				<a href="<?php echo siteUrl('/utilisateurs/editer-mes-informations'); ?>">Éditer mes informations</a>

			</li>
			<li>
				<a href="<?php echo siteUrl('/utilisateurs/changement-mot-de-passe'); ?>">Changer mon mot de passe</a>
			</li>
            <li>
				<a href="<?php echo siteUrl('/utilisateurs/supprimer-mon-compte'); ?>">Supprimer mon compte</a>
			</li>
		</ul>
	</section>

	<section id="user-activity">
		<h2>Mon activité recente</h2>

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
					</p>
				</li>
			<?php endforeach; ?>
		</ul>
		<a href="<?php echo siteUrl("/utilisateurs/mon-activite/page/1/utilisateur/".getUserData('id'));?> ">Voir toute mon activité</a>
	</section>

<?php 
?>
</div>
<script src="https://www.paypal.com/sdk/js?client-id=ARfF7X6QHlMiDMt-dQSu4Xi0cz5u7vQm55mRI3EmZkf5S7XFlds_EkoMu2U0tC77S1TNoPtdZF8q9Ar1&currency=EUR&intent=capture&components=buttons"> // Required. Replace SB_CLIENT_ID with your sandbox client ID.
    </script>
<script>
	 const item = document.querySelector('#amount')
	
    const saveTransaction = (transactionData)=>{
        
       fetch('https://mymemories.frameworks.software/achats/sauvegarder',{
           header:{'Content-Type':'application/json'},
           method:"POST",
           body: JSON.stringify(transactionData)
       })
       .then(res => {return res.json()})
       .then(data => {
		   if(data[0]=='success'){
				const product_name = item.options[item.selectedIndex].getAttribute('data-product_name')
				let total_points = data[2]
                document.cookie = `success-purchase=${product_name}. Votre achat a été enregistré avec succès.;max-age=10`;
                document.cookie = `total-points=${total_points};max-age=10`;
			   location.reload()
		   }
	   }).catch(err => console.log(err))
	}
	
	paypal.Buttons({
		createOrder: function(data, actions) {
            const product_id = item.options[item.selectedIndex].getAttribute('data-product_id')
            
            // This function sets up the details of the transaction, including the amount and line item details.
            return actions.order.create({
                purchase_units: [{
                    amount: {
                        value: item.value
                    },
                   reference_id: product_id
                }]
            });
		},
		onApprove: function(data, actions) {
            // This function captures the funds from the transaction.
            return actions.order.capture().then(function(details) {
                //This function shows a transaction success message to your buyer.
                let user_id = document.querySelector('#paypal-button-container')
                user_id = parseInt(user_id.dataset.user_id)
                details.user_id = user_id
                saveTransaction(details)
            });
		}
	}).render('#paypal-button-container');
	//This function displays Smart Payment Buttons on your web page.
</script>