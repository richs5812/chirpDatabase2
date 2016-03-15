<?php

// src/AppBundle/Controller/FormController.php
namespace AppBundle\Controller;

use AppBundle\Entity\Client;
use AppBundle\Entity\FamilyMember;
use AppBundle\Entity\Referral;
use AppBundle\Entity\Appointment;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Form\ClientType;
use AppBundle\Form\ReferralType;
use Doctrine\Common\Collections\ArrayCollection;

class FormController extends Controller
{
	/**
     * @Route("/form/{id}", name="form", defaults={"id" = "new client"})
     */

    public function formAction(Request $request, $id)
    {
		//get client
		$repository = $this->getDoctrine()
			->getRepository('AppBundle:Client');
			
		if ($id == "new client")
			{
				$client = new Client();
			} else {
				// query by the primary key (usually "id")
				$client = $repository->find($id);
			}	

		if (!$client) {
				throw $this->createNotFoundException('No client found for id '.$id);
			}
		
		//get clients for drop down menu
		//$allClients = $repository->findAll();
		
		$em = $this->getDoctrine()->getManager();

		$allClientsQuery = $em->createQuery('SELECT c FROM AppBundle:Client c ORDER BY c.lastName ASC');
		$allClients = $allClientsQuery->getResult();

		//arrays for removal functions
		$originalFamilyMembers = new ArrayCollection();
		$originalReferrals = new ArrayCollection();
		$originalAppointments = new ArrayCollection();

		// Create an ArrayCollection of the current FamilyMember objects in the database
		foreach ($client->getFamilyMembers() as $familyMember) {
			$originalFamilyMembers->add($familyMember);
		}
		
		// Create an ArrayCollection of the current Referral objects in the database
		foreach ($client->getReferrals() as $referral) {
			$originalReferrals->add($referral);
		}
		
		// Create an ArrayCollection of the current Appointment objects in the database
		foreach ($client->getAppointments() as $appointment) {
			$originalAppointments->add($appointment);
		}
		
		$form = $this->createForm(ClientType::class, $client);
		
		$form->handleRequest($request);
		
		$errors = null;
		
 		if ($form->isSubmitted() && !$form->get('save')->isClicked()) {

			//get id from dropdown menu
			$id = $request->request->getIterator()->current();
			return $this->redirectToRoute('form', array('id'=> $id));
			
			} else if ($form->isSubmitted() && $form->isValid()){
				$em = $this->getDoctrine()->getManager();
				$em->persist($client);
				
				$familyMembers = $client->getFamilyMembers();
				//save current and new familyMembers
				foreach ($familyMembers as $familyMember){
					$em->persist($familyMember);
				}
				// remove the relationship between the familyMember and the Client
				foreach ($originalFamilyMembers as $originalFamilyMember) {
					if (false === $client->getFamilyMembers()->contains($originalFamilyMember)) {
						// delete the FamilyMember
						$em->remove($originalFamilyMember);
					}
				}

				$referrals = $client->getReferrals();
				//save current and new referrals
				foreach ($referrals as $referral){
					$em->persist($referral);
				}
				// remove the relationship between the referral and the Client
				foreach ($originalReferrals as $originalReferral) {
					if (false === $client->getReferrals()->contains($originalReferral)) {
						// delete the Referral
						$em->remove($originalReferral);
					}
				}
				
				$appointments = $client->getAppointments();
				//save current and new appointments
				foreach ($appointments as $appointment){
					$em->persist($appointment);
				}
				// remove the relationship between the appointment and the Client
				foreach ($originalAppointments as $originalAppointment) {
					if (false === $client->getAppointments()->contains($originalAppointment)) {
						// delete the Appointment
						$em->remove($originalAppointment);
					}
				}


				$em->flush();
				$id = $client->getID();
				return $this->redirectToRoute('form', array('id'=> $id));
			} else if ($form->isSubmitted() && !$form->isValid()){
				$errors = 'not valid';
			}	

	    return $this->render('default/form.html.twig', array(
	        'form' => $form->createView(),
			'dropDownForm' => $form->createView(),
	        'allClients' => $allClients,
	        'id' => $id,
	        'errors' => $errors,
	    ));
	}
}
?>
