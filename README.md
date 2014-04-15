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

