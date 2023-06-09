	</main>

	<?php FCT\Sections\print_section( 'aside-bottom', '<aside class="sidebar-bottom gutenberg-container">', '</aside>' ) ?>

	<?php if ( function_exists('vvab_ymyl_author_print') ) { vvab_ymyl_author_print(); } ?>

	<footer class="site-footer gutenberg-container" id="footer">
        <?php FCT\Sections\print_section( 'footer', '<h2 class="screen-reader-text">Footer</h2>' ) ?>
	</footer>

<?php wp_footer(); ?>

</body>
</html>
