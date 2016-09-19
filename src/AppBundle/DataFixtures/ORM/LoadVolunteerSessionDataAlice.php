<?php

namespace AppBundle\DataFixtures\ORM;

use Hautelook\AliceBundle\Doctrine\DataFixtures\AbstractLoader;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;

class LoadVolunteerSessionDataAlice extends AbstractLoader implements OrderedFixtureInterface
{
    /**
     * {@inheritdoc}
     */
    public function getFixtures()
    {
        return [
            '@AppBundle/DataFixtures/ORM/VolunteerSession.yml',
        ];
    }
    
    public function getOrder()
    {
        // the order in which fixtures will be loaded
        // the lower the number, the sooner that this fixture is loaded
        return 11;
    }
}
