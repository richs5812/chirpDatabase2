<?php

// src/AppBundle/DataFixtures/ORM/LoadClientData.php

namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use AppBundle\Entity\Client;

class LoadClientData implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $testClient = new Client();
        $testClient->setFirstName('Dummy');
        $testClient->setLastName('Data');

        $manager->persist($testClient);
        $manager->flush();
    }

    public function getOrder()
    {
        // the order in which fixtures will be loaded
        // the lower the number, the sooner that this fixture is loaded
        return 1;
    }
}
