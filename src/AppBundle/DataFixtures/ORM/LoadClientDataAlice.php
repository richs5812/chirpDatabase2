<?php

namespace AppBundle\DataFixtures\ORM;

use Hautelook\AliceBundle\Doctrine\DataFixtures\AbstractLoader;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;

class LoadClientDataAlice extends AbstractLoader implements OrderedFixtureInterface
{
    /**
     * {@inheritdoc}
     */
    public function getFixtures()
    {
        return [
			__DIR__.'/Client.yml',
            '@AppBundle/DataFixtures/ORM/Client.yml',
        ];
    }
    
    public function getOrder()
    {
        // the order in which fixtures will be loaded
        // the lower the number, the sooner that this fixture is loaded
        return 1;
    }
}