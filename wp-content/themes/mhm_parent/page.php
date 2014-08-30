<?php
get_header();

if (have_posts()):

	if (is_category()):
		echo '<header class="summary"><h3>';
		single_cat_title('');
		echo '</h3>'.category_description().'</header>';

	elseif(is_tag()):

		echo '<header class="summary"><h3>' .sprintf(__('Posts marked with the tag “%s”',THEME),single_tag_title('')). '</h3>';
		echo tag_description();
		echo '<aside><p>'.__('Tags are keywords, which I use to group together items relating to the same or similar subjects. By navigating using tags, you can find references to information much more accurately than by simply browsing categories.',THEME).'</p></aside>';
		echo '</header>';

	elseif(is_month()):

		echo '<header class="summary"><h3>' .sprintf(__('All articles from %s',THEME),get_the_time('F, Y')). '</h3>';
		echo '<p>' .__('To select posts from a different month or category, try using the search or menu options.',THEME). '</p></header>';

	elseif(is_year()):
		echo '<header class="summary"><h3>' .sprintf(__('All articles from %s',THEME),get_the_time('Y')). '</h3>';
		echo '<p>' .__('To select posts from a specific month or category, try using the search or menu options.',THEME). '</p></header>';

	elseif(is_tag()):
		echo '<header class="summary"><h3>' .sprintf(__('Posts marked with the tag “%s”',THEME),single_tag_title('',false)). '</h3>';
		echo tag_description();
		echo '<aside><p>' .__('Tags are keywords, which are used to group items relating to the same or similar subjects. By navigating using tags, you can find references to information much more accurately than by simply browsing categories.',THEME). '</p></aside>';
		echo '</header>';

	elseif(is_search()):
		global $wp_query;
		$total_results = $wp_query->found_posts;
		echo '<header class="summary"><h3>' .__('Search results',THEME). '</h3>'.
			'<p>'.sprintf(__('You just ran a search for "%1s", a term which can be found within %2s pages, galleries and articles.',THEME), '<a href="/?s='.$_GET["s"].'">'.$_GET["s"].'</a>', $total_results).'</p>'.
		'</header>';

	endif;

		?>


	<section class="content" role="main">

	<?php while (have_posts()):
		the_post();

		$permalink=get_permalink();
	?>
		<article class="clearfix post post<?=$post->ID?>">

		<?php
		$show_titles = get_post_meta($post->ID,'show_titles',true);
		if($show_titles!='Nein'):?>
			<header>
				<h1><a href="<?=$permalink?>"><?php echo $cms->page_title(array(
					'showparent' => false,
					'wrap' => ''
					)); ?></a></h1>
				<?php if($post->post_type=='page'):
					$subtitle = get_post_meta($post->ID,'subtitle',true);
					if($subtitle!=''):
						echo '<h2>' .$subtitle. '</h2>';
					endif;

				endif;?>
			</header>
		<?php endif;?>


		<?php
			$lead = apply_filters('the_content',get_post_meta($post->ID,'lead',true));
			if($lead!=''):
				echo '<section class="lead">' .$lead. '</section>';
			endif;
			the_content(__('More »',THEME));
		?>

		</article>

	<?php endwhile;?>

	</section>

<?php
endif;
get_footer(); ?>