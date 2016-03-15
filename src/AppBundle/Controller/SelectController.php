<?php

// src/AppBundle/Controller/FormController.php
namespace AppBundle\Controller;

use AppBundle\Entity\Client;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Form\SelectClientType;

class SelectController extends Controller
{
	/**
     * @Route("/select", name="select")
     */

    public function selectAction(Request $request)
    {

		$form = $this->createForm(SelectClientType::class);
		
		$form->handleRequest($request);

 		if ($form->isSubmitted() && $form->isValid()) {
			//var_dump($form->getData());die;
			
			$data = $form->getData();
			$id=$data->getLastName()->getID();

			return $this->redirectToRoute('form', array('id'=> $id));
		}

	    return $this->render('default/new.html.twig', array(
	        'form' => $form->createView(),
	    ));
	}
}
?>
