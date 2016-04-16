<?php

namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use AppBundle\Entity\FamilyMember;

class LoadFamilyMemberData implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $testFamilyMember = new FamilyMember();
        $testFamilyMember->setName('Dummy');
        $testFamilyMember->setRelationship('Data');

        $manager->persist($testFamilyMember);
        $manager->flush();
    }

    public function getOrder()
    {
        // the order in which fixtures will be loaded
        // the lower the number, the sooner that this fixture is loaded
        return 2;
    }
}
