<?php
App::uses('ContainersController', 'BazaarVoice.Controller');

/**
 * ContainersController Test Case
 *
 */
class ContainersControllerTest extends ControllerTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array();

	public function testContainer() {
		$Containers = $this->generate(
			'BazaarVoice.Containers',
			array(
				'components' => array('Security', 'Auth')
			)
		);
		$result = $this->testAction(
			'/bazaar_voice/containers/container',
			array('return' => 'vars')
		);

		$this->assertTrue(isset($this->vars['url']));
	}

}
