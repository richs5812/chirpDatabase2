<?php

namespace Tests\AppBundle\Form\Type;

use AppBundle\Form\ClientType;
use AppBundle\Entity\Client;
use Symfony\Component\Form\Test\TypeTestCase;

class GenericClientTypeTest extends TypeTestCase
{
    public function testSubmitValidData()
    {
        $formData = array(
            //'firstName' => 'foo',
            //'lastName' => 'bar',
        );

		$object = new Client();

        $form = $this->factory->create(ClientType::class);
       // var_dump($form);die;

		//$object->setFirstName('foo');      
		//$object->setLastName('bar');

        // submit the data to the form directly
        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());
        $this->assertEquals($object, $form->getData());

        $view = $form->createView();
        $children = $view->children;
/*
        foreach (array_keys($formData) as $key) {
            $this->assertArrayHasKey($key, $children);
        }*/
    }
}