<?php

use GoBrave\Fogg\Fogg;

class FoggTest extends PHPUnit_Framework_TestCase
{
  public function testConstruct() {
    $fogg = new Fogg();
    $this->assertTrue($fogg instanceof Fogg);
  }
}
