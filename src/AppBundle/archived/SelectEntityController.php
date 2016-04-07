<?php

// src/AppBundle/Controller/FormController.php
namespace AppBundle\Controller;

use AppBundle\Entity\Client;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Form\SelectEntityType;

class SelectEntityController extends Controller
{

    public function selectEntityAction(Request $request)
    {
    	//get clients
		$repository = $this->getDoctrine()
			->getRepository('AppBundle:Client');
			
		//get clients for drop down menu
		//$allClients = $repository->findAll();
		
		$em = $this->getDoctrine()->getManager();

		$allClientsQuery = $em->createQuery('SELECT c FROM AppBundle:Client c ORDER BY c.lastName ASC');
		$allClients = $allClientsQuery->getResult();
    
		$form = $this->createForm(SelectEntityType::class);
		
		$form->handleRequest($request);
		
 		if ($form->isSubmitted() && $form->isValid()) {
			//get id from dropdown menu
			$id = $request->request->getIterator()->current();
			
			return $this->redirectToRoute('form', array('id'=> $id));
		}
		
        return $this->render('default/selectEntity.html.twig', array(
            'form' => $form->createView(),
	        'allClients' => $allClients,
        ));
	}
}
?>
