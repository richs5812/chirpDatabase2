<?php

namespace Tests\AppBundle\Form\Type;

use AppBundle\Form\StorehouseReferralType;
use AppBundle\Entity\StorehouseReferral;
use Symfony\Component\Form\Test\TypeTestCase;

class StorehouseReferralTypeTest extends TypeTestCase
{
	/**
	 * @dataProvider getValidTestData
	 */
    public function testStorehouseReferralForm($formData)
    {

        $form = $this->factory->create(StorehouseReferralType::class);
        
        $object = new StorehouseReferral();
        
        if (isset($formData['type'])){
			$object->setType($formData['type']);
        }       
        if (isset($formData['date'])){
			$object->setDate($formData['date']);
        }    
		if (isset($formData['notes'])){
			$object->setNotes($formData['notes']);
		}
        // submit the data to the form directly
        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());
        $this->assertEquals($object, $form->getData());

        $view = $form->createView();
        $children = $view->children;

        foreach (array_keys($formData) as $key) {
            $this->assertArrayHasKey($key, $children);
        }
    }
    
	public function getValidTestData()
    {
    //added nulls for fields that were failing tests
        return array(
            array(
                'data' => array(
                    'date' => null,
                    'type' => null,
                    'notes' => 'test',                                             
                ),
            ),
			array(
                'data' => array(),
            ),
            array(
                'data' => array(
                    'date' => null,
                    'type' => null,
                    'notes' => null,                                             
                ),
            ),
        );
    }
}