<?php

namespace AppBundle\Controller;

use AppBundle\Entity\DonorVolunteer;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Form\VolunteerType;
use Doctrine\Common\Collections\ArrayCollection;

class VolunteerController extends Controller
{
	/**
     * @Route("/form/volunteer/{id}", name="volunteerForm", defaults={"id" = "new volunteer"})
     */
    public function formAction(Request $request, $id)
    {	
		$em = $this->getDoctrine()->getManager();
						
		if ($id == "new volunteer")
			{
				$volunteer = new DonorVolunteer();
			} else {
				$volunteerQuery = $em->createQuery(
					'SELECT v
					FROM AppBundle:DonorVolunteer v
					WHERE v.id = :donorVolunteerID'
				)->setParameter('donorVolunteerID', $id);

				$volunteer = $volunteerQuery->getResult()[0];
			}	
			
		if (!$volunteer) {
				throw $this->createNotFoundException('No client found for id '.$id);
			}

		$allDonorVolunteersQuery = $em->createQuery('SELECT d FROM AppBundle:DonorVolunteer d ORDER BY d.lastName ASC');
		$allDonorVolunteers = $allDonorVolunteersQuery->getResult();
		
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
		
		$form = $this->createForm(VolunteerType::class, $volunteer);
		
		$form->handleRequest($request);

		$errors = null;
		
//  		if ($form->isSubmitted() && !$form->get('save')->isClicked()) {
// 
// 			//get id from dropdown menu
// 			$id = $request->request->getIterator()->current();
// 			return $this->redirectToRoute('form', array('id'=> $id));
// 			
// 			} else if ($form->isSubmitted() && $form->isValid()){
			if ($form->isSubmitted() && $form->isValid()) {

				$em->persist($volunteer);
				
				// $familyMembers = $client->getFamilyMembers();
// 				//save current and new familyMembers
// 				foreach ($familyMembers as $familyMember){
// 					$em->persist($familyMember);
// 				}
// 				// remove the relationship between the familyMember and the Client
// 				foreach ($originalFamilyMembers as $originalFamilyMember) {
// 					if (false === $client->getFamilyMembers()->contains($originalFamilyMember)) {
// 						// delete the FamilyMember
// 						$em->remove($originalFamilyMember);
// 					}
// 				}
// 
// 				$referrals = $client->getReferrals();
// 				//save current and new referrals
// 				foreach ($referrals as $referral){
// 					$em->persist($referral);
// 				}
// 				// remove the relationship between the referral and the Client
// 				foreach ($originalReferrals as $originalReferral) {
// 					if (false === $client->getReferrals()->contains($originalReferral)) {
// 						// delete the Referral
// 						$em->remove($originalReferral);
// 					}
// 				}
// 				
// 			//	dump($client->getAppointments());
// 				
// 				$appointments = $client->getAppointments();
// 				//save current and new appointments
// 				foreach ($appointments as $appointment){
// 					$em->persist($appointment);
// 				}
// 				// remove the relationship between the appointment and the Client
// 				foreach ($originalAppointments as $originalAppointment) {
// 					if (false === $client->getAppointments()->contains($originalAppointment)) {
// 						// delete the Appointment
// 						$em->remove($originalAppointment);
// 					}
// 				}

				$em->flush();
				$id = $volunteer->getID();
				return $this->redirectToRoute('volunteerForm', array('id'=> $id));
			} else if ($form->isSubmitted() && !$form->isValid()){
				$errors = 'not valid';
			}	

	    return $this->render('default/volunteerForm.html.twig', array(
	        'form' => $form->createView(),
	        'id' => $id,
	        'errors' => $errors,
	    ));
	}
}
?>
