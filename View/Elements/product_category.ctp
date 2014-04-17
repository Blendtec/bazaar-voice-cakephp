<?php
/* product category
 *
 * @param mixed $productArray, this is an array of product IDs from a product category
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