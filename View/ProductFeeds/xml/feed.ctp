<?php echo '<?xml version="1.0" encoding="utf-8"?>' ?>
<Feed xmlns="http://www.bazaarvoice.com/xs/PRR/ProductFeed/5.6" name="<?php echo $feed['Feed']['name'] ?>" incremental="<?php echo $feed['Feed']['incremental'] ?>" extractDate="<?php echo $feed['Feed']['extracted'] ?>">
	<Brands>
	<?php foreach ($brands as $brand): ?>
		<Brand>
			<ExternalId><?php echo $brand['Brand']['external_id']; ?></ExternalId>
			<Name><?php echo $brand['Brand']['name']; ?></Name>
		</Brand>
	<?php endforeach; ?>
	</Brands>
	<Categories>
	<?php foreach ($categories as $category): ?>
		<Category>
			<ExternalId><?php echo $category['Category']['external_id']; ?></ExternalId>
			<Name><?php echo $category['Category']['name']; ?></Name>
			<CategoryPageUrl><?php echo $category['Category']['page_url']; ?></CategoryPageUrl>
		</Category>
	<?php endforeach; ?>
	</Categories>
	<Products>
	<?php foreach ($products as $product): ?>
		<Product>
			<ExternalId><?php echo $product['Product']['external_id']; ?></ExternalId>
			<Name><?php echo $product['Product']['name']; ?></Name>
			<Description><?php echo h($product['Product']['description']); ?></Description>
			<CategoryExternalId><?php echo $product['Product']['category_external_id']; ?></CategoryExternalId>
			<ProductPageUrl><?php echo $product['Product']['page_url']; ?></ProductPageUrl>
			<ImageUrl><?php echo $product['Product']['image_url']; ?></ImageUrl>
			<ManufacturerPartNumbers>
				<ManufacturerPartNumber><?php echo $product['Product']['item_code']; ?></ManufacturerPartNumber>
			</ManufacturerPartNumbers>
			<UPCs>
				<UPC><?php echo $product['Product']['upc']; ?></UPC>
			</UPCs>
			<ModelNumbers>
				<ModelNumber><?php echo $product['Product']['model_number']; ?></ModelNumber>
			</ModelNumbers>
			<BrandExternalId><?php echo $product['Product']['brand_external_id']; ?></BrandExternalId>
		</Product>
	<?php endforeach; ?>
	</Products>
</Feed>
