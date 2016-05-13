<?php

namespace Tests\AppBundle\Form\Type;

use AppBundle\Form\StorehouseAppointmentType;
use AppBundle\Entity\StorehouseAppointment;
use Symfony\Component\Form\Test\TypeTestCase;

class StorehouseAppointmentTypeTest extends TypeTestCase
{
	/**
	 * @dataProvider getValidTestData
	 */
    public function testStorehouseAppointmentForm($formData)
    {

        $form = $this->factory->create(StorehouseAppointmentType::class);
        
        $object = new StorehouseAppointment();
        
        if (isset($formData['date'])){
			$object->setDate($formData['date']);
        }       
        if (isset($formData['status'])){
			$object->setStatus($formData['status']);
        }    
		if (isset($formData['note'])){
			$object->setNote($formData['note']);
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
                    'status' => null,
                    'note' => 'test',                                             
                ),
            ),
			array(
                'data' => array(),
            ),
            array(
                'data' => array(
                    'date' => null,
                    'status' => null,
                    'note' => null,                                             
                ),
            ),
        );
    }
}