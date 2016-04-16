<?php

// src/AppBundle/DataFixtures/ORM/LoadClientData.php

namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
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
}
