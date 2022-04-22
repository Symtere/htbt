<div class="item-one">
	<div class="content-one">
		<div class="image">
			<img src="<?php echo get_the_post_thumbnail_url($post->ID); ?>" alt="">
		</div>
		<div class="title">
			<?php echo get_the_title($post->ID); ?>
		</div>
		<div class="excerpt">
			<?php echo get_the_excerpt($post->ID); ?>
		</div>
		<div class="btn">
			<a href="<?php echo get_the_permalink($post->ID); ?>"><span>Voir l'article</span><span><i class="fal fa-long-arrow-right"></i></span></a>
		</div>
	</div>
</div>
