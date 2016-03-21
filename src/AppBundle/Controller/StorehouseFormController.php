<?php

// src/AppBundle/Controller/StorehouseFormController.php
namespace AppBundle\Controller;

use AppBundle\Entity\StorehouseClient;
use AppBundle\Entity\StorehouseFamilyMember;
use AppBundle\Entity\StorehouseReferral;
use AppBundle\Entity\StorehouseAppointment;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Form\StorehouseClientType;
use Doctrine\Common\Collections\ArrayCollection;

class StorehouseFormController extends Controller
{
	/**
     * @Route("/form/storehouseClient/{id}", name="storehouseForm", defaults={"id" = "new client"})
     */

    public function storehouseFormAction(Request $request, $id)
    {
		//get client
		$repository = $this->getDoctrine()
			->getRepository('AppBundle:StorehouseClient');
			
		if ($id == "new client")
			{
				$client = new StorehouseClient();
			} else {
				// query by the primary key (usually "id")
				$client = $repository->find($id);
			}	

		if (!$client) {
				throw $this->createNotFoundException('No client found for id '.$id);
			}
		
		$em = $this->getDoctrine()->getManager();

		$allClientsQuery = $em->createQuery('SELECT c FROM AppBundle:StorehouseClient c ORDER BY c.lastName ASC');
		$allClients = $allClientsQuery->getResult();

		//arrays for removal functions
		$originalFamilyMembers = new ArrayCollection();
		$originalReferrals = new ArrayCollection();
		$originalAppointments = new ArrayCollection();

		// Create an ArrayCollection of the current FamilyMember objects in the database
		foreach ($client->getStorehouseFamilyMembers() as $familyMember) {
			$originalFamilyMembers->add($familyMember);
		}
		
		// Create an ArrayCollection of the current Referral objects in the database
		foreach ($client->getStorehouseReferrals() as $referral) {
			$originalReferrals->add($referral);
		}
		
		// Create an ArrayCollection of the current Appointment objects in the database
		foreach ($client->getStorehouseAppointments() as $appointment) {
			$originalAppointments->add($appointment);
		}
		
		$form = $this->createForm(StorehouseClientType::class, $client);
		
		$form->handleRequest($request);
		
		$errors = null;
		
 		if ($form->isSubmitted() && !$form->get('save')->isClicked()) {

			//get id from dropdown menu
			$id = $request->request->getIterator()->current();
			return $this->redirectToRoute('storehouseForm', array('id'=> $id));
			
			} else if ($form->isSubmitted() && $form->isValid()){
				$em = $this->getDoctrine()->getManager();
				$em->persist($client);
				
				$familyMembers = $client->getStorehouseFamilyMembers();
				//save current and new familyMembers
				foreach ($familyMembers as $familyMember){
					$em->persist($familyMember);
				}
				// remove the relationship between the familyMember and the Client
				foreach ($originalFamilyMembers as $originalFamilyMember) {
					if (false === $client->getStorehouseFamilyMembers()->contains($originalFamilyMember)) {
						// delete the FamilyMember
						$em->remove($originalFamilyMember);
					}
				}

				$referrals = $client->getStorehouseReferrals();
				//save current and new referrals
				foreach ($referrals as $referral){
					$em->persist($referral);
				}
				// remove the relationship between the referral and the Client
				foreach ($originalReferrals as $originalReferral) {
					if (false === $client->getStorehouseReferrals()->contains($originalReferral)) {
						// delete the Referral
						$em->remove($originalReferral);
					}
				}
				
				$appointments = $client->getStorehouseAppointments();
				//save current and new appointments
				foreach ($appointments as $appointment){
					$em->persist($appointment);
				}
				// remove the relationship between the appointment and the Client
				foreach ($originalAppointments as $originalAppointment) {
					if (false === $client->getStorehouseAppointments()->contains($originalAppointment)) {
						// delete the Appointment
						$em->remove($originalAppointment);
					}
				}


				$em->flush();
				$id = $client->getID();
				return $this->redirectToRoute('storehouseForm', array('id'=> $id));
			} else if ($form->isSubmitted() && !$form->isValid()){
				$errors = 'not valid';
			}	

	    return $this->render('default/storehouseForm.html.twig', array(
	        'form' => $form->createView(),
			'dropDownForm' => $form->createView(),
	        'allClients' => $allClients,
	        'id' => $id,
	        'errors' => $errors,
	    ));
	}
}
?>
