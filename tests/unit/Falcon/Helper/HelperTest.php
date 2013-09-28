<?php

namespace Falcon\Helper;

class HelperTest extends \PHPUnit_Framework_TestCase
{
    public function testUUIDGenerator()
    {
        $uuid = Helper::generateUuid();
        $this->assertRegExp('/^[a-zA-Z0-9]{8}-[a-zA-Z0-9]{4}-[a-zA-Z0-9]{4}-[a-zA-Z0-9]{4}-[a-zA-Z0-9]{12}$/i', $uuid);
    }
}