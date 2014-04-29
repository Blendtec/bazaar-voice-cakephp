<?php
/* Review container
 *
 * @param mixed $productId This is your product id. It must match your external product id in the xml feed.
 */
?>
<div id="BVRRContainer">
	<?php
	require_once(CakePlugin::path('BazaarVoice') . DS . 'Vendor' . DS . 'bvseosdk.php');

	$BVR = new BV(
		array(
			'deployment_zone_id' => Configure::read('BazaarVoice.deployment_zone_id'),
			'product_id' => $productId,
			'cloud_key' => Configure::read('BazaarVoice.cloud_key'),
			'staging' => Configure::read('BazaarVoice.staging'),
		)
	);

	echo $BVR->reviews->renderSeo();
	?>
</div>