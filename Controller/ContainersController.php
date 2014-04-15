<?php
App::uses('BazaarVoiceAppController', 'BazaarVoice.Controller');
/**
 * Containers Controller
 *
 */
class ContainersController extends BazaarVoiceAppController {

	public function beforeFilter() {
		$this->Auth->allow();
	}

	public function container() {
		$this->set(
			array(
				'url' => Configure::read('BazaarVoice.url')
			)
		);
		$this->layout = "BazaarVoice.container";
	}

}
