<?php

get_header();

?>


<div class="container">

		<div class="post-content">
			<div class="wrap-width" itemprop="text">
				<header class="entry-header">
					<h1 class="entry-title" itemprop="headline">
						<?php _e( 'Page not found', 'fct' ) ?>
					</h1>
				</header>
				<div class="entry-content">
					<p><?php _e( 'The content, you are looking for, is not found :(', 'fct' ) ?></p>
					<p style="font-size:70px;">404</p>
				</div>
			</div>
		</div>

</div>

<?php

get_footer();

?>
