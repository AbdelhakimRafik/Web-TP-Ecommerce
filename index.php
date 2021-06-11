<?php
	include_once('api/product_list.php')
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Data List</title>

	<!-- bootstrap -->
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
	<!-- fontawesome -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
	<!--  -->
	<link rel="stylesheet" href="public/assets/css/style.css">

</head>
<body>
	<div class="container">
		<div class="row">
			<div class="col-12">
				<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#importModal">
					Import Data
				</button>
			</div>
		</div>
	</div>

	<div class="page-header breadcrumb-wrap">
		<div class="container">
			<div class="breadcrumb">
				<a href="#" rel="nofollow">Accueil</a>
				<span></span> Produits
			</div>
			<div class="cart float-end">
				<button type="button" class="btn btn-none btn-cart" data-bs-toggle="modal" data-bs-target="#cartModal">
					<i class="fas fa-cart-plus"></i>
					<span>0</span>
				</button>
			</div>
		</div>
	</div>

	<div class="container">
		<div class="row">
			<?php if(count($products) > 0) : ?>
				<?php foreach($products as $product) : ?>
					<div class="col-12 col-sm-4 col-lg-3 col-md-3">
						<div class="product-cart-wrap mb-30">
							<div class="product-img-action-wrap">
								<div class="product-img product-img-zoom">
									<a href="#">
										<img class="default-img" src="<?= $product->image ?>" alt="">
									</a>
								</div>
								<div class="product-action-1">
									<a aria-label="Ajouter au panier" class="action-btn hover-up btn-cart-event" event="add" target="<?= $product->sku ?>"><i class="fas fa-cart-plus"></i></a>
								</div>
								<div class="product-badges product-badges-position product-badges-mrg">
									<span class="hot">Hot</span>
								</div>
							</div>
							<div class="product-content-wrap">
								<div class="product-category">
									<a href="#">Musical Instruments</a>
								</div>
								<h2><a href="#"><?= $product->name ?></a></h2>
								<div class="product-price">
									<span><?= $product->price ?> MAD</span>
									<span class="shipping-price">Livraison: <?= $product->shipping ?> MAD</span>
								</div>
							</div>
						</div>
					</div>
				<?php endforeach ?>

				<div class="row">
					<nav class="float-right" aria-label="...">
						<ul class="pagination">
							<li class="page-item <?= $offset == 0 ? 'disabled' : '' ?>">
								<a class="page-link" href="?offset=0">Previous</a>
							</li>
							<?php for($i=0; $i<=$count; $i++): ?>
								<li class="page-item <?= $offset == $i ? 'active' : '' ?>" aria-current="page">
									<a class="page-link" href="?offset=<?= $i ?>"><?= $i ?></a>
								</li>
							<?php endfor ?>
							<li class="page-item <?= $offset == $count ? 'disabled' : '' ?>">
								<a class="page-link" href="?offset=<?= $count ?>">Next</a>
							</li>
						</ul>
					</nav>
				</div>
				
			<?php else : ?>
				<div>Pas de produits</div>
			<?php endif; ?>
		</div>
	</div>
	
	<!-- import modal -->
	<div id="cartModal" class="modal" tabindex="-1">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title">Panier</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
					<ul id="cart" class="empty">
						<li class="empty-cart">
							<p>Panier est vide !</p>
						</li>
						<li class="has-scroll">
							<table class="table">
								<tbody class="cart-content">
									
								</tbody>
							</table>
						</li>
						<li class="controls">
							<table class="table">
								<tbody>
									<tr>
										<td class="text-left">Total :</td>
										<td class="text-right cart-total">00.00 MAD</td>
									</tr>
								</tbody>
							</table>
							<p class="text-center cart-button">
								<button class="btn btn-danger btn-cart-clear">Clear</button>
								<button class="btn btn-primary">Valider</button>
							</p>
						</li>
					</ul>
				</div>
			</div>
		</div>
	</div>

	<!-- import modal -->
	<div id="importModal" class="modal" tabindex="-1">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title">Importing data</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
					<div class="input-group mb-3">
						<input type="file" name="file" class="form-control" id="fileInput">
						<label class="input-group-text" for="fileInput">File</label>
					</div>
					<div class="progress">
						<div class="progress-bar" role="progressbar" style="width: 0%;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">0%</div>
					</div>
					<div class="import-console"></div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
					<button target="#fileInput" type="button" class="btn btn-primary submit">Import</button>
				</div>
			</div>
		</div>
	</div>

	<template id="cart-item-tempalte">
		<tr>
			<td class="text-center">
				<a href="#">
					<img class="cart-image" src="http://ravado4.demo.towerthemes.com/image/cache/catalog/Products/1-80x96.jpg" alt="Bonavita BV 1900TS" title="Bonavita BV 1900TS">
				</a>
			</td>
			<td class="text-left info-item">
				<span class="cart-title"></span>                       			
				<p class="cart-quantity"><input class="input-cart-quantity" type="number" min="1"></p>
				<p class="cart-price"></p>
			</td>
			<td class="text-center cart-close">
				<button type="button" title="Remove" event="remove" class="btn btn-danger btn-xs btn-cart-remove btn-cart-event"><i class="fas fa-trash-alt"></i></button>
			</td>
		</tr>
	</template>

	<!-- jquery -->
	<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
	<!-- bootstrap -->
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>
	
	<script>
		$(document).ready(function(){

			// update cart
			updateCarte();

			var cart_item_template = document.querySelector('#cart-item-tempalte');

			// get cart element when modal opens
			$('#cartModal').on('show.bs.modal', updateCarte);

			// update carte
			function updateCarte() {
				cartEventsHandler({action: 'getList'}, function(res){

					// clear cart content
					$('#cartModal .cart-content').html('');
					var cart = res.cart;
					if(res.status == 200 && cart.count != 0){
						
						$('.btn-cart span').text(cart.count);
						$('#cartModal #cart').removeClass('empty');
					
						// add products
						for(key in cart.products) {
							var cart_item = cart_item_template.content.querySelector('tr');
							var item = document.importNode(cart_item, true);

							// add item image
							$(item).find('.cart-image').attr('src', cart.products[key].image);
							$(item).find('.cart-title').text(cart.products[key].name);
							$(item).find('.input-cart-quantity').attr('target', key).val(cart.products[key].quantity);
							$(item).find('.cart-price').text(`${cart.products[key].price} MAD`);
							$(item).find('.btn-cart-remove').attr('target', key);

							// apprend item to cart table
							$('#cartModal .cart-content').append(item);
						}
						// update total
						$('#cartModal .cart-total').text(`${cart.total.toFixed(2)} MAD`);
					}
					else{
						$('#cartModal #cart').addClass('empty');
						$('.btn-cart span').text('0');
					}
				});
			}

			// handle cart events
			function cartEventsHandler(eventData, cb) {
				$.ajax({
					url: 'api/cart.php',
					method: 'get',
					data: eventData,
					dataType: 'json',
					success: cb
				})
			}

			// clear cart
			$('.btn-cart-clear').click(function(){
				cartEventsHandler({ action: 'clear'}, function(res){
					updateCarte();
				});
			});

			// cart quantity input on change
			$(document).on('change', '.input-cart-quantity', function(){
				var sku = $(this).attr('target');
				var quantity = $(this).val();

				// send event request to server
				cartEventsHandler({ action: 'quantityUp', sku: sku, quantity: quantity }, function(res){
					updateCarte();
				});
			});

			// cart btn events
			$(document).on('click', '.btn-cart-event', function(e){
				var sku = $(this).attr('target');
				var event = $(this).attr('event');
				// send event request to server
				cartEventsHandler({ action: event, sku: sku }, function(res){
					updateCarte();
				});
			});

			// set import button listener
			$('#importModal .submit').click(function(e){
				var self = this;
				// disable import button
				$(self).attr('disabled', 'true');

				var fileInput = $(self).attr('target');
				var file = $(fileInput).prop('files')[0];
				var formData = new FormData();
				formData.append('file', file);
				$.ajax({
					url: 'api/import_file.php',
					method: 'post',
					dataType: 'json',
					data: formData,
					contentType: false,
        			processData: false,
					success: function(res) {
						if(res.status == 200) {
							startImport(res.file, self);
							progressFn = setInterval(getProgress, 1000);
						}
						else {
							var message = res.status == 415 ? "Type de fichier non pris en charge" : "Une erreur s'est produite lors de l'importation du fichier";
							$('#importModal .import-console').append(`<div class="error">${message}</div>`);
							// enable import button
							$(self).removeAttr('disabled');
						}
					}
				});
			});

			/**
			 * watch data import
			 */
			function startImport(file, btn) {
				$.ajax({
					url: 'api/import_data.php',
					method: 'post',
					data: {
						file: file
					},
					dataType: 'json',
					success: function(res) {

						clearInterval(progressFn);

						var message = res.status == 200 ? "Données importées avec succès" : "Une erreur s'est produite lors de l'importation du fichier";
						var classname = res.status == 200 ? "success" : "error";
						$('#importModal .import-console').append(`<div class="${classname}">${message}</div>`);
						// enable import button
						$(btn).removeAttr('disabled');
						
					}
				});
			}

			/**
			 * watch data import progress
			 */
			function getProgress() {
				$.ajax({
					url: 'api/import_progress.php',
					method: 'post',
					dataType: 'json',
					contentType: false,
        			processData: false,
					success: function(res) {
						
						if(res.status == 0)
							clearInterval(progressFn);
						else {
							// set progress value
							var consoleDiv = $('#importModal .import-console');
							var presentage = `${Math.round(((res.inserted+res.ignored)/res.total)*100)}%`;
							$('#importModal .progress-bar').css('width', presentage).text(`${res.inserted+res.ignored}/${res.total}`);
							res.elements.forEach(elem => {
								if(elem.status == 200)
									$('#importModal .import-console').append(`<div class="success">Produit ${elem.id}: Bien ajouter</div>`);
								else {
									var message = elem.code == 23000 ? "Produit avec même identifiant existe déjà" : "Erreur de serveur s'est produite";
									$('#importModal .import-console').append(`<div class="error">Produit ${elem.id}: ${message}</div>`);
								}
							});
							$(consoleDiv).scrollTop = $(consoleDiv).scrollHeight;
						}
					}
				});
			}
		})
	</script>
</body>
</html>