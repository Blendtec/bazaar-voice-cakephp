bazaar-voice-cakephp
====================

a Bazaar Voice CakePHP 2.x plugin


Setup
=================

Container
-----------

To use the container, you need to specific a BazaarVoice.url in your configuration. e.g.

    Configure::write(
    	'BazaarVoice.url',
    	'//display.ugc.bazaarvoice.com/static/<client_name>/en_US/bvapi.js'
	);

Please remember to autoswitch between staging and production urls.

You will also need to specific a route for the container (The plugin will pick up your route and set the canonical url accordingly, according to BazaarVoice documentation). e.g.

    Router::connect(
    	'/bv-container',
    	array(
    		'plugin' => 'bazaar_voice',
    		'controller' => 'containers',
    		'action' => 'container'
		)
	);


Load Script
--------------------

An element to load the script for you is provided. Use it like so:

    echo $this->element('BazaarVoice.load_script');


Product Page Initialization
--------------------------------

A product_top element has been provided to load the script and initialize BazaarVoice with the product id. Make sure that you pass in a `$productId` that matches the External Product Id in your Bazaar Voice XML Feed. Use it like so:

    echo $this->element(
		'BazaarVoice.product_top',
		array(
			'productId' => $product['Product']['external_product_id']
		)
	);


Product Review SEO Integration
--------------------------------------

BazaarVoice requires a div with id `BVRRContainer` to display reviews on your product page. If you don't want SEO integration this is an empty tag. e.g.

    <div id="BVRRContainer"></div>

But for SEO integration, the SEO integration code goes in that tag. `review_seo_container` element has been provided with that code already set up inside of the `<div id="BVRRContainer"></div>`. Please provide these **required** configuration values:

- `deployment_zone_id` - This is provided for you by Bazaar Voice
- `cloud_key` - This is provided for you by Bazaar Voice
- `staging` - Whether to use the Production or Staging environments. This should be the same algorithm as switching the URL above.

Just call it with the `$productId` same as above e.g.

    echo $this->element(
        'BazaarVoice.review_seo_container',
        array(
            'productId' => $product['Product']['external_product_id']
        )
    );

NOTE: This feature requires the PHP bvseosdk.php provided by BazaarVoice at [https://github.com/bazaarvoice/HostedUIResources/blob/master/SEOIntegration/examples/php/bvseosdk.php](https://github.com/bazaarvoice/HostedUIResources/blob/master/SEOIntegration/examples/php/bvseosdk.php). This file is included in the *Vendor* directory. If this file is out of date please report an issue to us on Github, or fix it yourself and do a pull request.

Product Questions and Answers SEO integration
---------------------------------------------------------------

Use this exactly like the Product Review SEO Integration. Please read that Section above. The same configuration is required. The element is `questions_seo_container`.  Again this provides the div for you: `<div id="BVQAContainer"></div>`.


Inline Ratings Integration
--------------------------------
BazaarVoice requires that you use a container to initialize a place for the ratings to reside. The default container is:

     <div id="BVRRInlineRating"></div>

However, to associate and rating with a specific product, that product will need an ID associated with BazaarVoice. So within a category page, for each product you will need to reference each ID within the inline ratings container ID. An example would be:

    `<div id="BVRRInlineRating-<?php echo $product['id']; ?>">`

Along with the IDs being unique to the products containers, you will need to register each product ID within BazaarVoice. You can do this by passing each ID to an array, then passing it to BazaarVoice. A product_category element has been provided to load the script and add ratings to products within a category page. 

Once you have an array of product IDs you can pass it to the plugin element like so:

    echo $this->element(
        'BazaarVoice.product_category', 
        array(
            'productArray' => $productArray
        )
    );
    
Product Feed Generation
--------------------------------

The Bazaarvoice product feed constsists of your products and their associated categories. It facilities the mapping of reviews and dimensions to the appropriate products and categories.

Controller

    public function admin_bazaar_feed() {
        if (!isset($this->request->params['ext']) || $this->request->params['ext'] !== 'xml') {
            throw new BadRequestException();
        }
        $this->set($this->Product->bazaarFeed('blendtec.com'));
        $this->render('BazaarVoice.ProductFeeds/xml/feed');
    }

Model

    public function bazaar_feed() {
        $feed = array (
                'Feed' => array (
                    'name' => 'blendtec',
                    'extracted' => $date->format('Y-m-d\TH:i:s.m'),
                    'incremental' => 'false'
                )
            );
        $brands = array (
            (int)0 => array (
                'Brand' => array (
                    'external_id' => 'blendtec',
                    'name' => 'Blendtec'
                )
            )
        );
        $products = array (
            (int)0 => array (
                'Product' => array (
                    'external_id' => '123456',
                    'name' => 'Total Blender',
                    'description' => 'Blender Description',
                    'category_external_id' => 'blenders',
                    'page_url' => 'http://blendtec.com',
                    'image_url' => 'http://blendtec.com/blenders.jpg',
                    'upc' => '000000000000',
                    'model_number' => '123456',
                    'brand_external_id' => 'blendtec'
                )
            ),
            (int)1 => array (
                'Product' => array (
                    'external_id' => '123457',
                    'name' => 'Twister',
                    'description' => 'Jar Description',
                    'category_external_id' => 'jars',
                    'page_url' => 'http://blendtec.com',
                    'image_url' => 'http://blendtec.com/jars.jpg',
                    'upc' => '000000000000',
                    'model_number' => '123457',
                    'brand_external_id' => 'blendtec'
                )
            ),
            (int)2 => array (
                'Product' => array (
                    'external_id' => '123458',
                    'name' => 'Kitchen Mill',
                    'description' => 'Mill Description',
                    'category_external_id' => 'mixers',
                    'page_url' => 'http://blendtec.com',
                    'image_url' => 'http://blendtec.com/mixers.jpg',
                    'upc' => '000000000000',
                    'model_number' => '123457',
                    'brand_external_id' => 'blendtec'
                )
            ),
        );
        $categories = array (
            (int)0 => array (
                'Category' => array (
                    'external_id' => 'blenders',
                    'name' => 'Blenders',
                    'page_url' => 'http://blendtec.com/blenders',
                )
            ),
            (int)1 => array (
                'Category' => array (
                    'external_id' => 'jars',
                    'name' => 'Jars',
                    'page_url' => 'http://blendtec.com/jars',
                )
            ),
            (int)2 => array (
                'Category' => array (
                    'external_id' => 'mills',
                    'name' => 'Mills',
                    'page_url' => 'http://blendtec.com/mills',
                )
            ),
        );
        return compact('feed', 'brands', 'products', 'categories');
    }

ROI Beacon - Order Confirmation
-----------------------------------

An `roi-beacon-order` element has been provided to ease implementing the ROI beacon on your Order Confirmation page. You must prep the data and then pass it to the element. Remember:

- product id/sku must match the external product id that you provided in the product feed
- Category should be the same category name that you provided in the feed as well.
- Order id/number should be the same one you send in your order confirmation emails.

Example:
(items should be dynamic instead of static, but that's just for the sake of example.)

    echo $this->element(
        'BazaarVoice.roi-beacon-order',
        array(
            'order' => array(
                'id' => $order['Order']['id'],
                'tax' => $order['Order']['tax'],
                'shipping' => $order['Order']['shipping'],
                'total' => $order['Order']['total'],
                'city' => $order['Order']['shipping_city'],
                'state' => $order['Order']['shipping_state'],
                'country' => $order['Order']['shipping_country'],
                'currency' => 'USD',
                'items' => array(
                    'sku' => $order['OrderItem'][0]['Product']['external_id'],
                    'name' => $order['OrderItem'][0]['Product']['name'],
                    'category' => $order['OrderItem'][0]['Product']['Category']['name'],
                    'price' => $order['OrderItem'][0]['price'],
                    'quantity' => $order['OrderItem'][0]['quantity']
                ),
                'email' => $order['Order']['email'],
                'first_name' => $order['Order']['shipping_first_name'],
                'last_name' => $order['Order']['shipping_last_name']
            )
        )
    );