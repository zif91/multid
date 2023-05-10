<script>
	jQuery(document).ready(function(){
		jQuery('head').append('<link rel="stylesheet" rel="nofollow" href="[+baseUrl+]assets/tvs/morephoto/style.css?v=1" type="text/css"/>');
		jQuery.getScript('[+baseUrl+]assets/tvs/morephoto/jquery.multiphotos.js?v=1', function() {
			jQuery('.morephoto').multiphotos({'baseUrl': '[+baseUrl+]'});
		});
		//console.log('morephoto loaded');
	});
</script>