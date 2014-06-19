<?php

namespace Ars\RefTrackerBundle\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Class SimpleRefTrackerTest
 *
 * Simplest test for ref tracker
 *
 * @package Ars\RefTrackerBundle\Tests
 */
class SimpleRefTrackerTest extends WebTestCase
{
    public function testTracker()
    {
        $client = static::createClient();

        $url = "/ref-tracker/dashboard?ref=";
        $validCode = "522a49";
        $invalidCode = "NonExistingRef";

        $client->request('GET', $url . $validCode);

        $this->assertEquals($client->getResponse()->getStatusCode(), 301);

        $this->assertEquals($client->getResponse()->headers->get('location'), '/ref-tracker/dashboard');

        $client->request('GET', $url . $invalidCode);

        $this->assertNotEquals($client->getResponse()->getStatusCode(), 301);

    }
}
