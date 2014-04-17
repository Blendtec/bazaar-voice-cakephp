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

Product Feed Generation
--------------------------------

The Bazaarvoice product feed constsists of your products and their associated categories. It facilities the mapping of reviews and dimensions toe the appropriate products and categories.

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


NOTE: This feature requires the PHP bvseosdk.php provided by BazaarVoice at [https://github.com/bazaarvoice/HostedUIResources/blob/master/SEOIntegration/examples/php/bvseosdk.php](https://github.com/bazaarvoice/HostedUIResources/blob/master/SEOIntegration/examples/php/bvseosdk.php). This file is included in the *Vendor* directory. If this file is out of date please report an issue to us on Github, fix it yourself and do a pull request.