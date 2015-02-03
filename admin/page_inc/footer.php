	<script>
		jQuery(document).ready(function(){
			var doc_height = jQuery( document ).height();
			var header_height = jQuery( 'header-table' ).height();
			jQuery( "#main-table" ).css( 'height', doc_height - header_height );
		});
	</script>
	</body>
</html>