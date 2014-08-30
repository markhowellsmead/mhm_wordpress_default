<?php get_header();

	if (is_category()):
		echo '<header><h1>';
		single_cat_title('');
		echo '</h1>'.category_description().'</header>';

	elseif(is_tag()):
		echo '
		<header>
			<h1>' .sprintf(__('Posts marked with the tag «%s»',THEME), single_tag_title('',false)). '</h1>'
			.tag_description().
		'</header>';

	elseif(is_month()):
		echo '<header><h1>' .sprintf(__('All articles from %s',THEME),get_the_time('F, Y')). '</h1></header>';

	elseif(is_year()):
		echo '<header><h1>' .sprintf(__('All articles from %s',THEME), get_the_time('Y',false)). '</h1></header>';

	elseif(is_tag()):
		echo '<header>
			<h1>' .sprintf(__('Posts marked with the tag «%s»',THEME), single_tag_title('',false)). '</h1>'.
			tag_description().
			'</header>';

	elseif(is_search()):

		global $wp_query;
		$total_results = $wp_query->found_posts;
		$searchValue = get_search_query();
		echo '
		<header>
			<h1>' .__('Search results',THEME). '</h1>'
			.($total_results>0?
				'<p>'.sprintf(__('You just ran a search for <em>%1s</em>, a term which can be found within %2s pages and articles.',THEME), '<a href="/?s='.$searchValue.'">'.$searchValue.'</a>', $total_results).'</p>'
				:'<p>'.sprintf(__('You just ran a search for <em>%1s</em>. This term could not be found anywhere on the website.',THEME), '<a href="/?s='.$searchValue.'">'.$searchValue.'</a>', $total_results).'</p>')
		.'</header>';

	endif;

if (have_posts()):
		?>


	<section class="content">


	<?php if(!is_search()){
	
		while (have_posts()):
			the_post();
			$permalink=get_permalink();
	?>
		<article class="clearfix post post<?=$post->ID?>">

			<header>
				<h1><a href="<?=$permalink?>"><?php the_title(); ?></a></h1>
				<?php if($post->post_type=='page'):
					$subtitle = get_post_meta($post->ID,'subtitle',true);
					if($subtitle!=''):
						echo '<h2>' .$subtitle. '</h2>';
					endif;
				endif;?>
			</header>

			<?php
			$thumbnail_url=$theme->getThumbnailURL(array(
				'ID'	=> $post->ID,
				'size'	=> 'g10'
			));

			if($thumbnail_url!=''):
				if(strpos($thumbnail_url,'ytimg')>-1):
					$permalink .= (strpos($permalink,'?')?'&':'?').'autoplay=1';
				endif;
				echo '<a class="figure" href="'.$permalink.'"><img class="alignnone" src="'.$thumbnail_url.'" alt="'.$post->post_title.'"></a>';
			endif;
			?>

			<?php
			$teaser=apply_filters('the_content',get_post_meta($post->ID,'teaser',true));
			if($teaser!=''):
				echo '<section class="lead">' .$teaser . '</section>';
			else:
				if(is_search()):
					if($post->post_type!='page'):
						the_excerpt();
					endif;
				else:
					the_excerpt();
				endif;

			endif;

			?>
		</article>

	<?php endwhile;


	}else{
	
		// suchergebnisse
		echo '<article>';
		$result_list = array();

		$title_options = array(
			'showparent' => false
		);

		while (have_posts()):
			the_post();
			$permalink=get_permalink();
			$result_list[] = '<li><a href="'.$permalink.'">'.$cms->page_title($title_options).'</a></li>';
		endwhile;

		if(sizeof($result_list)>0):
			echo '<ul>'.implode('',$result_list).'</ul>';
		endif;

		//include(TEMPLATEPATH . "/searchform.php"); // noch nicht in die standardpaket integriert
	endif;
	?>
	</section>

<?php
else:
	// etwas anzeigen wenn keine posts gefunden

endif;
get_footer();
?>