<?php

namespace AppBundle\Controller;

use AppBundle\Entity\DonorVolunteer;
use AppBundle\Entity\VolunteerCategory;
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
    public function volunteerFormAction(Request $request, $id)
    {	
// 	dump($request);die;
		$em = $this->getDoctrine()->getManager();
		//array for category ids for checkboxes
		$categoryIDs = array();

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

				//get volunteer categories for checkboxes
				$volunteerCategories = $volunteer->getVolunteerCategories()->getIterator();					
				$i = 0;
		
				foreach ($volunteerCategories as $volunteerCategory) {
					$categoryIDs[$i] = $volunteerCategory->getId();
					$i++;
				}
			}	
			
		$allDonorVolunteersQuery = $em->createQuery('SELECT d FROM AppBundle:DonorVolunteer d ORDER BY d.lastName ASC');
		$allDonorVolunteers = $allDonorVolunteersQuery->getResult();
		
		//arrays for removal functions
		$originalVolunteerSessions = new ArrayCollection();

		// Create an ArrayCollection of the current VolunteerSession objects in the database
		foreach ($volunteer->getVolunteerSessions() as $volunteerSession) {
			$originalVolunteerSessions->add($volunteerSession);
		} 
// 		
// 		//arrays for removal functions
// 		$originalVolunteerCategories = new ArrayCollection();
// 
// 		// Create an ArrayCollection of the current FamilyMember objects in the database
// 		foreach ($volunteer->getVolunteerCategories() as $volunteerCategory) {
// 			$originalVolunteerCategories->add($volunteerCategory);
// 		}

		//dump($categoryIDs);
				
		$form = $this->createForm(VolunteerType::class, $volunteer);
		
		$form->handleRequest($request);

		$errors = null;
		
 		if ($form->isSubmitted() && !$form->get('save')->isClicked()) {

			//get id from dropdown menu
			$id = $request->request->getIterator()->current();
			return $this->redirectToRoute('volunteerForm', array('id'=> $id));
			
			} else if ($form->isSubmitted() && $form->isValid()) {
				
				$em->persist($volunteer);
				
				$volunteerSessions = $volunteer->getVolunteerSessions();
				//save current and new volunteerSessions
				foreach ($volunteerSessions as $volunteerSession){
					$em->persist($volunteerSession);
				}
				// remove the relationship between the volunteerSession and the volunteer
				foreach ($originalVolunteerSessions as $originalVolunteerSession) {
					if (false === $volunteer->getVolunteerSessions()->contains($originalVolunteerSession)) {
						// delete the VolunteerSession
						$em->remove($originalVolunteerSession);
					}
				}
				
				// $volunteerCategories = $volunteer->getVolunteerCategories();
// 				//save current and new volunteerCategorys
// 				foreach ($volunteerCategories as $volunteerCategory){
// 					$em->persist($volunteerCategory);
// 				}
// 				// remove the relationship between the volunteerCategory and the volunteer
// 				foreach ($originalVolunteerCategories as $originalVolunteerSession) {
// 					if (false === $volunteer->getVolunteerCategories()->contains($originalVolunteerSession)) {
// 						// delete the VolunteerSession
// 						$em->remove($originalVolunteerSession);
// 					}
// 				}

				$em->flush();
				$id = $volunteer->getID();
				return $this->redirectToRoute('volunteerForm', array('id'=> $id));
			} else if ($form->isSubmitted() && !$form->isValid()){
				$errors = $form->getErrors(true)->current();
			}

	    return $this->render('default/volunteerForm.html.twig', array(
	        'form' => $form->createView(),
	        'allDonorVolunteers' => $allDonorVolunteers,
	        'categoryIDs' => $categoryIDs,
	        'id' => $id,
	        'errors' => $errors,
	    ));
	}
}
?>
