<?php get_header(); ?>

<div class="main-wrap full-width" role="main">

<?php do_action( 'foundationpress_before_content' ); ?>
<?php while ( have_posts() ) : the_post(); ?>
	<article <?php post_class('main-content') ?> id="post-<?php the_ID(); ?>">
		<header>
			<h1 class="entry-title"><?php the_title(); ?></h1>
		</header>
		<?php do_action( 'foundationpress_page_before_entry_content' ); ?>
		<div class="entry-content">
			<?php the_content(); ?>

			<form action="<?php echo admin_url( 'admin-ajax.php' ) ?>" method="post" enctype="multipart/form-data">
				<div class="grid-x grid-padding-x">
			    <div class="cell medium-6">
			      <label>Item Name
			        <input type="text" name="item_name">
			      </label>

			      <label>Details
			        <textarea name="details" cols="30" rows="4" value="<?php $_POST['details']; ?>"></textarea>
			      </label>
			    </div>

			    <div class="cell medium-6">
			    	<label>Category
			        <input type="text" name="category" list="categories">
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
							<input type="number" name="purchase_price" min="0" max="1000000" step=".01" value="">
			      </label>

			      <label>Purchase Date
			      	<input type="date" name="purchase_date" value="<?php echo current_time('Y-m-d'); ?>">
			      </label>
			    </div>
			    <div class="cell">
			    	<label>Photo
			    		<input type="file" name="photo" accept="image/*">
			    	</label>
			    </div>
			    <div class="cell">
			    	<?php wp_nonce_field( 'submit_content', 'my_nonce_field' ); ?>
			    	<input type="hidden" name="action" value="add_item">
			    	<button type="submit" class="button">Add Item</button>
			    </div>
			  </div>
			</form>
		</div>
	</article>
<?php endwhile;?>

<?php do_action( 'foundationpress_after_content' ); ?>

</div>

<?php get_footer();
