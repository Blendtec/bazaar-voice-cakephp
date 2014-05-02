<?php
/* Questions container
 *
 * @param mixed $productId This is your product id. It must match your external product id in the xml feed.
 */
?>
<div id="BVQAContainer">
	<?php
	require_once(CakePlugin::path('BazaarVoice') . DS . 'Vendor' . DS . 'bvseosdk.php');

	$BVQA = new BV(
		array(
			'deployment_zone_id' => Configure::read('BazaarVoice.deployment_zone_id'),
			'product_id' => $productId,
			'cloud_key' => Configure::read('BazaarVoice.cloud_key'),
			'staging' => Configure::read('BazaarVoice.staging'),
		)
	);

	echo $BVQA->questions->renderSeo();
	?>
</div>