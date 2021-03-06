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
     * @Route("/form/client/{id}", name="form", defaults={"id" = "new client"})
     */
    public function formAction(Request $request, $id)
    {	
// 		dump($request->request->getIterator()["client"]["newFocusGroup"]);die;
		
		$em = $this->getDoctrine()->getManager();
		$clientFocusGroups = array();
		
		if ($id == "new client")
			{
				$client = new Client();
			} else {
				// query by the primary key (usually "id")
				$clientQuery = $em->createQuery(
					'SELECT c
					FROM AppBundle:Client c
					WHERE c.id = :clientID'
				)->setParameter('clientID', $id);
				$client = $clientQuery->getResult()[0];
				
				//get focus groups for select list
// 				dump($client->getFocusGroups()->getIterator());
				$clientFocusGroups = $client->getFocusGroups()->getIterator();
			}	
			
		if (!$client) {
				throw $this->createNotFoundException('No client found for id '.$id);
			}
		$allClientsQuery = $em->createQuery('SELECT c FROM AppBundle:Client c ORDER BY c.lastName ASC, c.firstName ASC');
		$allClients = $allClientsQuery->getResult();
		
		$allFocusGroupsQuery = $em->createQuery('SELECT f FROM AppBundle:FocusGroup f ORDER BY f.groupName ASC');
		$allFocusGroups = $allFocusGroupsQuery->getResult();
		
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
				
				//add new focus group
				if ($request->request->getIterator()["newFocusGroup"] != '') {
					$newFocusGroupId = $request->request->getIterator()["newFocusGroup"];
					$focusGroupRepository = $this->getDoctrine()->getRepository('AppBundle:FocusGroup');
					$newFocusGroup = $focusGroupRepository->find($newFocusGroupId);
					$client->setNewFocusGroup($newFocusGroup);
				}
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
// 				$errors = 'not valid';
				$errors = $form->getErrors(true)->current();
			}	
	    return $this->render('default/form.html.twig', array(
	        'form' => $form->createView(),
	        'allClients' => $allClients,
	        'allFocusGroups' => $allFocusGroups,
	        'clientFocusGroups' => $clientFocusGroups,
	        'id' => $id,
	        'errors' => $errors,
	    ));
	}
}
?>
