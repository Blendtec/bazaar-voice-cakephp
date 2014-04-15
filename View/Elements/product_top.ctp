<?php
/* product top
 *
 * @param mixed $productId This is your product id to pass to BazaarVoice API. This must match your product feed's ExternalId.
 */
echo $this->element('BazaarVoice.load_script');
?>
<script type="text/javascript">
	$BV.configure('global', { productId : '<?php echo $productId;?>' });
</script>