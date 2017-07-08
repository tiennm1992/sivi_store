<?php
App::uses('Slide', 'Model');

/**
 * Slide Test Case
 *
 */
class SlideTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.slide'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->Slide = ClassRegistry::init('Slide');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Slide);

		parent::tearDown();
	}

}
