<?php
get_header();
if ( isset($_GET['id']) && !empty($_GET['id']) && doesCurrentUserOwnItem($_GET['id']) ) {
	$item = get_item_details($_GET['id']);
} else {
	$message = "There was an issue with the request at the previous URL.";
	echo '<script>window.location = "' . site_url() . '/dashboard/?message=' . urlencode($message) . '&type=alert' . '";</script>';
}
?>

<div class="main-wrap full-width" role="main">

<?php do_action( 'foundationpress_before_content' ); ?>
<?php while ( have_posts() ) : the_post(); ?>
	<article <?php post_class('main-content') ?> id="post-<?php the_ID(); ?>">
		<header>
			<h1 class="entry-title"><?php the_title(); ?> <span class="subheader"> - <?php echo stripslashes($item->name); ?></span></h1>
		</header>
		<?php do_action( 'foundationpress_page_before_entry_content' ); ?>
		<div class="entry-content">
			<?php the_content(); ?>

			<form action="<?php echo admin_url( 'admin-ajax.php' ) ?>" method="post" enctype="multipart/form-data">
				<div class="grid-x grid-padding-x">
			    <div class="cell medium-6">
			      <label>Item Name
			        <input type="text" name="item_name" value="<?php echo htmlspecialchars(stripslashes($item->name)); ?>">
			      </label>

			      <label>Details
			        <textarea name="details" cols="30" rows="4"><?php echo htmlspecialchars(stripslashes($item->details)); ?></textarea>
			      </label>
			    </div>

			    <div class="cell medium-6">
			    	<label>Category
			        <input type="text" name="category" list="categories" value="<?php echo htmlspecialchars(stripslashes($item->category)); ?>">
			        <?php $categories = getCategories();
			        if (!empty($categories)) { ?>
				        <datalist id="categories">
				        	<?php foreach ($categories as $category) {
				        		echo '<option value="' . $category . '">';
				        	} ?>
								</datalist>
							<?php } ?>
			      </label>

			      <label>Purchase Price
							<input type="number" name="purchase_price" min="0" max="1000000" step=".01" value="<?php echo htmlspecialchars(stripslashes($item->purchase_price)); ?>">
			      </label>

			      <label>Purchase Date
			      	<input type="date" name="purchase_date" value="<?php echo $item->purchase_date; ?>">
			      </label>
			    </div>
			    <div class="cell">
			    	<?php if ($item->photo != "") {
			    		echo '<img class="thumbnail" src="' . $item->photo . '" />';
			    	} ?>

			    	<label><?php if ($item->photo != "") { echo 'New '; } ?>Photo
			    		<input type="file" name="photo" accept="image/*">
			    	</label>
			    </div>
			    <div class="cell">
			    	<?php wp_nonce_field( 'submit_content', 'my_nonce_field' ); ?>
			    	<input type="hidden" name="item_id" value="<?php echo $_GET['id']; ?>">
			    	<input type="hidden" name="action" value="edit_item">
			    	<button type="submit" class="button">Save Changes</button>
			    </div>
			  </div>
			</form>
		</div>
	</article>
<?php endwhile;?>

<?php do_action( 'foundationpress_after_content' ); ?>

</div>

<?php get_footer();
