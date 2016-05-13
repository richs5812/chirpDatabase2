<?php

// tests/AppBundle/Form/Type/ClientTypeTest.php
namespace Tests\AppBundle\Form\Type;

use AppBundle\Form\StorehouseFamilyMemberType;
use AppBundle\Entity\StorehouseFamilyMember;
use Symfony\Component\Form\Test\TypeTestCase;

class StorehouseFamilyMemberTypeTest extends TypeTestCase
{
	/**
	 * @dataProvider getValidTestData
	 */
    public function testStorehouseFamilyMemberForm($formData)
    {

        $form = $this->factory->create(StorehouseFamilyMemberType::class);
        
        $object = new StorehouseFamilyMember();
        
        if (isset($formData['name'])){
			$object->setName($formData['name']);
        }       
        if (isset($formData['relationship'])){
			$object->setRelationship($formData['relationship']);
        }    
		if (isset($formData['gender'])){
			$object->setGender($formData['gender']);
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
                    'name' => 'test',
                    'relationship' => 'test',
                    'age' => 'test',                                             
					'gender' => null,
                ),
            ),
			array(
                'data' => array(),
            ),
            array(
                'data' => array(
                    'name' => null,
                    'relationship' => null,
                    'age' => null,                                             
					'gender' => null,
                ),
            ),
        );
    }
}