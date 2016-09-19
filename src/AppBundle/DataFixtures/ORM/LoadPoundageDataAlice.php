<?php

namespace AppBundle\DataFixtures\ORM\Dev;

use Hautelook\AliceBundle\Doctrine\DataFixtures\AbstractLoader;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;

class LoadPoundageData extends AbstractLoader implements OrderedFixtureInterface
{
    /**
     * {@inheritdoc}
     */
    public function getFixtures()
    {
        return [
            '@AppBundle/DataFixtures/ORM/Poundage.yml',
        ];
    }
    
    public function getOrder()
    {
        // the order in which fixtures will be loaded
        // the lower the number, the sooner that this fixture is loaded
        return 6;
    }
}
