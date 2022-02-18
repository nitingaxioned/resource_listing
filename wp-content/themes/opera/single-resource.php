<?php  
get_header();
if (has_post_thumbnail()) {
	$img_url = get_the_post_thumbnail_url();
	$alt = get_post_meta( get_post_thumbnail_id(), '_wp_attachment_image_alt', true);
}
$title = get_the_title();  
$excerpt = get_the_excerpt();
$disc = get_field('discription');
$current_post_id = get_the_ID();
$iam = get_the_terms( $current_post_id , "resource-cat-iam");
$lookingfor = get_the_terms( $current_post_id , "resource-cat-looking");
?>
<main>
	<div class="wrapper">
		<div class="full-post">
			<?php
			if ( $title ) {?>
				<h2><?php echo $title; ?></h2>
			<?php } 
			if ( $iam ) {?>
				<h4>I am :</h4>
				<?php 
				show_taxonomy($iam);
			} 
			if ( $lookingfor ) {?>
				<h4>looking For :</h4>
				<?php 
				show_taxonomy($lookingfor);
			} 
			if ( $excerpt ) {?>
				<p><?php echo $excerpt; ?></p>
			<?php } 
			if (has_post_thumbnail()) {?>
				<div class="img-box">
					<img src='<?php echo $img_url; ?>' alt='<?php echo $alt; ?>'>
				</div>
			<?php } 
			if ( $disc ) {?>
				<p><?php echo $disc; ?></p>
			<?php } ?>
		</div>
		<!-- also read section -->
		<div class="also-read">
			<?php
			$cat_arr = [];
			foreach($iam as $val) {
				array_push($cat_arr, $val->term_id);
			}
			$queryArr = array(
				'post_type' => 'resource',
				'posts_per_page' => 10,
				'post_status' => array('publish'),
				'post__not_in' => array($current_post_id),
				'tax_query' => array(
					array(
						'taxonomy' => 'resource-cat-iam',
						'field'    => 'term_id',
						'terms'    => $cat_arr,
					),
				),
			);
			$res = new wp_Query($queryArr);
			if ( $res->have_posts() ) {
				?>
				<h3>Also Checkout :</h3>
				<ul class="resource-list">
				<?php
					while ( $res->have_posts() ) { 
						$res->the_post(); 
						$title = get_the_title();
						$excerpt = get_the_excerpt();
						$link = get_permalink();
						$current_post_id = get_the_ID();
						$iam = get_the_terms( $current_post_id , "resource-cat-iam");
						$lookingfor = get_the_terms( $current_post_id , "resource-cat-looking");
						if (has_post_thumbnail()) {
							$img_url = get_the_post_thumbnail_url();
							$alt = get_post_meta( get_post_thumbnail_id(), '_wp_attachment_image_alt', true);
						}
						?>
						<li><?php
							if ( $title ) {?>
								<div class="max-width">
									<h3><?php echo $title; ?></h3>
									<?php 
									if ( $excerpt ) {?>
										<p><?php echo $excerpt; ?></p>
									<?php } 
									if ( $iam ) {?>
										<p>I am :</p>
										<?php 
										show_taxonomy($iam);
									} 
									if ( $lookingfor ) {?>
										<p>looking For :</p>
										<?php 
										show_taxonomy($lookingfor);
									} 
									?>
								</div>
							<?php } 
							if (has_post_thumbnail()) {?>
								<img src='<?php echo $img_url; ?>' class="min-width" alt='<?php echo $alt; ?>'>
							<?php } ?>
							<a title="Read More" href="<?php echo $link; ?>"><button class='btn'>Read More</button></a>
						</li>
					<?php
					}
				?>
				</ul>
			<?php } ?>
		</div>
	</div>
</main>
<?php
get_footer();