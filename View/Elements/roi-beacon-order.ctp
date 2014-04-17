<?php
// Bazaar Voice ROI Beacon - Order Confirmation

echo $this->element('BazaarVoice.load_script');
$itemCount = count($order['items']);

?>
<script type="text/javascript">
	$BV.SI.trackTransactionPageView({
		"orderId" : "<?php echo $order['id']; ?>",
		"tax" : "<?php echo $order['tax']; ?>",
		"shipping" : "<?php echo $order['shipping']; ?>",
		"total" : "<?php echo $order['total']; ?>",
		"city" : "<?php echo $order['city']; ?>",
		"state" : "<?php echo $order['state']; ?>",
		"country" : "<?php echo $order['country']; ?>",
		"currency" : "USD",
		"items" : [
			<?php for ($i = 0; $i < $itemCount; $i++): ?>
				{
					"sku" : "<?php echo $order[items][$i]['sku']; ?>",
					"name" : "<?php echo $order[items][$i]['name']; ?>",
					"category" : "<?php echo $order[items][$i]['category']; ?>",
					"price" : "<?php echo $order[items][$i]['price']; ?>",
					"quantity" : "<?php echo $order[items][$i]['quantity']; ?>"
				}
				<?php
				if ($i < $itemCount - 1):
					echo ",";
				endif;
				?>

			<?php endfor; ?>
		],
		"email" : "<?php echo $order['email']; ?>",
		'first_name' : "<?php echo $order['first_name']; ?>",
		'last_name' : "<?php echo $order['last_name']; ?>"
	});
</script>