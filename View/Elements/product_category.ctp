<?php
/* product category
 *
 * @param mixed $productId This is your product id to pass to BazaarVoice API. This must match your product feed's ExternalId.
 */
echo $this->element('BazaarVoice.load_script');
?>
<script>
	var productList = <?php echo json_encode($productArray) ?>;
	$BV.ui( 'rr', 'inline_ratings', {
	 productIds : productList,
	   containerPrefix : 'BVRRInlineRating'
	});
</script>