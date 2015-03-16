<?php
/**
 * The template for displaying Archive pages.
 *
 * @package ThinkUpThemes
 */

get_header(); ?>

			<?php
			if ( empty( $thinkup_blog_style ) or $thinkup_blog_style == 'option1' ) {
				$class  = array( 'blog-article');
				$layout = ' column-2';
			} else {
				$class  = array( 'blog-article', 'blog-style2' );
				$layout = ' column-1';
			} 
			?>
			
			<?php
			// SmartPost logic to add a "Edit this Category" or "Edit this Template" option for the user
			$cat_id = get_query_var('cat');
			if( current_user_can( 'edit_dashboard' ) ){
				$cat_settings_link = '<div class="sp-settings-link-container"><span class="sp-settings-icon"><a href="' . admin_url('edit-tags.php?action=edit&taxonomy=category&tag_ID=' . $cat_id .'&post_type=post') . '" target="_blank" title="Edit this category" alt="Edit this category">Edit this category</a></span></div>';
				if( defined( "SP_PLUGIN_NAME" ) && method_exists('sp_category', 'isSPCat') ){
					if(sp_category::isSPCat( get_query_var('cat') ) ){
						$cat_settings_link = '<div class="sp-settings-link-container"><span class="sp-settings-icon"><a href="' . admin_url('admin.php?page=smartpost&catID=' . $cat_id) . '" target="_blank" title="Edit this template" alt="Edit this template">Edit this template</a></span></div>';
					}
				}
				echo $cat_settings_link;
			}
			?>

			<?php 
			// SmartPost logic that adds an editable category description 
			if( defined( 'SP_PLUGIN_NAME' ) && method_exists('sp_core', 'sp_editor') && current_user_can( 'edit_dashboard' ) ) : ?>
				<div class="archive-meta">
					<div class="sp-cat-desc-editor">
					<?php
					echo sp_core::sp_editor(
						category_description(),
						null,
						false,
						'Add a category description ...',
						array( 'data-action' => 'sp_save_cat_desc_ajax', 'data-catid' => get_query_var('cat') )
					);
					?>
					</div>
				</div>
			<?php else: ?>
				<?php if ( category_description() ) : // Show an optional category description ?>
					<div class="archive-meta">
						<?php echo category_description(); ?>
					</div>
				<?php endif; ?>
			<?php endif; ?>
							
			<?php if( have_posts() ): ?>

				<div id="container" class="portfolio-wrapper">

				<?php while( have_posts() ): the_post(); ?>

					<div class="blog-grid element<?php echo $layout; ?>">

					<article id="post-<?php the_ID(); ?>" <?php post_class( $class ); ?>>

						<?php thinkup_input_blogtitle(); ?>

						<header class="entry-header">

							<?php thinkup_input_blogimage(); ?>
							<?php thinkup_input_blogformat(); ?>

						</header>

						<div class="entry-content">

							<?php thinkup_input_blogtext(); ?>
							<?php thinkup_input_readmore(); ?>

						</div>

						<?php thinkup_input_blogmeta(); ?>

					</article><!-- #post-<?php get_the_ID(); ?> -->	

					</div>

				<?php endwhile; ?>

				</div><div class="clearboth"></div>

				<?php thinkup_input_pagination(); ?>

			<?php else: ?>

				<?php get_template_part( 'no-results', 'archive' ); ?>		

			<?php endif; wp_reset_query(); ?>

<?php get_footer() ?>