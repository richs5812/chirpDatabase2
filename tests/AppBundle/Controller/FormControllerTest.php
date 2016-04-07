<?php

namespace Tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class FormControllerTest extends WebTestCase
{
    public function testForm()
    {
        //$client = static::createClient();
        
		$client = static::createClient(array(), array(
			'PHP_AUTH_USER' => 'username',
			'PHP_AUTH_PW'   => 'pa$$word',
		));

        $crawler = $client->request('GET', '/form/client');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }
}
