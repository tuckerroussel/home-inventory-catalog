<?php get_header(); ?>

 <div class="main-wrap" role="main">

 <?php do_action( 'foundationpress_before_content' ); ?>
 <?php while ( have_posts() ) : the_post(); ?>
	<article <?php post_class('main-content') ?> id="post-<?php the_ID(); ?>">
		<header>
			<h1 class="entry-title"><?php the_title(); ?></h1>
		</header>
		<?php do_action( 'foundationpress_page_before_entry_content' ); ?>
		<div class="entry-content">
			<?php the_content(); ?>

			<?php $items = getItems();
      if (!empty($items)) { ?>
        <table class="table-expand hover">
				  <thead>
				    <tr class="table-expand-row">
				      <th>Name</th>
				      <th>Category</th>
				      <th>Purchase Date</th>
				      <th>Purchase Price</th>
				      <th width="80">Details</th>
				    </tr>
				  </thead>
				  <tbody>
	        	<?php foreach ($items as $item) { ?>
		        	<tr class="table-expand-row" data-open-details>
					      <td><strong><?php echo stripcslashes($item->name); ?></strong></td>
					      <td><?php echo stripcslashes($item->category); ?></td>
					      <td><?php echo stripcslashes($item->purchase_date); ?></td>
					      <td>$<?php echo stripcslashes($item->purchase_price); ?></td>
					      <td class="text-center"><span class="expand-icon"></span></td>
					    </tr>

					    <tr class="table-expand-row-content">
					      <td colspan="5" class="table-expand-row-nested">
					        <p><?php echo apply_filters('the_content', stripcslashes($item->details)); ?></p>
					        <div class="button-group">
					        	<?php if (filter_var($item->photo, FILTER_VALIDATE_URL)) { ?>
										  <a data-open="photo-modal" data-url="<?php echo $item->photo; ?>" class="button"><i class="fa fa-image"></i> View Image</a>
										<?php } ?>
					        	<a href="/edit-item/?id=<?php echo stripcslashes($item->id); ?>" class="button"><i class="fa fa-pencil-square-o"></i> Edit Item</a>
					        </div>
					      </td>
					    </tr>
	        	<?php } ?>
	        </tbody>
				</table>

				<div class="reveal" id="photo-modal" data-reveal data-animation-in="slide-in-up" data-animation-out="slide-out-down">
					<div class="photo-modal-content"></div>
				  <button class="close-button" data-close aria-label="Close modal" type="button">
				    <span aria-hidden="true">&times;</span>
				  </button>
				</div>

			<?php } ?>
		</div>
		<footer>
			<?php
				wp_link_pages(
					array(
						'before' => '<nav id="page-nav"><p>' . __( 'Pages:', 'foundationpress' ),
						'after'  => '</p></nav>',
					)
				);
			?>
			<p><?php the_tags(); ?></p>
		</footer>
		<?php do_action( 'foundationpress_page_before_comments' ); ?>
		<?php comments_template(); ?>
		<?php do_action( 'foundationpress_page_after_comments' ); ?>
	</article>
 <?php endwhile;?>

 <?php do_action( 'foundationpress_after_content' ); ?>
 <?php get_sidebar(); ?>

 </div>

 <?php get_footer();
