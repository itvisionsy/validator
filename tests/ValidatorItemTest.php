<?php

namespace ItvisionSy\Validator\tests;

use ItvisionSy\Validator;
use \PHPUnit_Framework_TestCase;

/**
 * Description of ValidatorItemTest
 *
 * @author muhannad
 */
class ValidatorItemTest extends PHPUnit_Framework_TestCase {

    public function testInitialization() {
        
    }

    public function testMakeInitialization() {
        $this->assertInstanceOf('\\ItvisionSy\\Validator\\ValidatorItem', Validator\ValidatorItem::make(123));
    }

    public function testQuickInitialization() {
        
    }

}
