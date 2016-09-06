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
// 	dump($request);die;
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
			
		$allDonorVolunteersQuery = $em->createQuery('SELECT d FROM AppBundle:DonorVolunteer d ORDER BY d.lastName ASC');
		$allDonorVolunteers = $allDonorVolunteersQuery->getResult();
		
		//arrays for removal functions
		$originalVolunteerSessions = new ArrayCollection();

		// Create an ArrayCollection of the current FamilyMember objects in the database
		foreach ($volunteer->getVolunteerSessions() as $volunteerSession) {
			$originalVolunteerSessions->add($volunteerSession);
		}
				
		$form = $this->createForm(VolunteerType::class, $volunteer);
		
		$form->handleRequest($request);

		$errors = null;
		
 		if ($form->isSubmitted() && !$form->get('save')->isClicked()) {

			//get id from dropdown menu
			$id = $request->request->getIterator()->current();
			return $this->redirectToRoute('volunteerForm', array('id'=> $id));
			
			} else if ($form->isSubmitted() && $form->isValid()){
			if ($form->isSubmitted() && $form->isValid()) {

				$em->persist($volunteer);
				
				$volunteerSessions = $volunteer->getVolunteerSessions();
				//save current and new volunteerSessions
				foreach ($volunteerSessions as $volunteerSession){
					$em->persist($volunteerSession);
// 					dump($volunteerSession);die;
				}
				// remove the relationship between the volunteerSession and the volunteer
				foreach ($originalVolunteerSessions as $originalVolunteerSession) {
					if (false === $volunteer->getVolunteerSessions()->contains($originalVolunteerSession)) {
						// delete the VolunteerSession
						$em->remove($originalVolunteerSession);
					}
				}

				$em->flush();
				$id = $volunteer->getID();
				return $this->redirectToRoute('volunteerForm', array('id'=> $id));
			} else if ($form->isSubmitted() && !$form->isValid()){
				$errors = 'not valid';
			}
		}	

	    return $this->render('default/volunteerForm.html.twig', array(
	        'form' => $form->createView(),
	        'allDonorVolunteers' => $allDonorVolunteers,
	        'id' => $id,
	        'errors' => $errors,
	    ));
	}
}
?>
