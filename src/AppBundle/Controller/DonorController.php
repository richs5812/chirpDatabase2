<?php

namespace AppBundle\Controller;

use AppBundle\Entity\DonorVolunteer;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Form\DonorType;
use Doctrine\Common\Collections\ArrayCollection;

class DonorController extends Controller
{
	/**
     * @Route("/form/donor/{id}", name="donorForm", defaults={"id" = "new donor"})
     */
    public function donorFormAction(Request $request, $id)
    {	
// 	dump($request);die;
		$em = $this->getDoctrine()->getManager();

		if ($id == "new donor")
			{
				$donor = new DonorVolunteer();
			} else {
				$donorQuery = $em->createQuery(
					'SELECT d
					FROM AppBundle:DonorVolunteer d
					WHERE d.id = :donorVolunteerID'
				)->setParameter('donorVolunteerID', $id);

				$donor = $donorQuery->getResult()[0];
			}	
			
		$allDonorVolunteersQuery = $em->createQuery('SELECT d FROM AppBundle:DonorVolunteer d ORDER BY d.lastName ASC');
		$allDonorVolunteers = $allDonorVolunteersQuery->getResult();
		
		//arrays for removal functions
		$originalDonations = new ArrayCollection();

// 		Create an ArrayCollection of the current donation objects in the database
		foreach ($donor->getDonations() as $donation) {
			$originalDonations->add($donation);
		} 
				
		$form = $this->createForm(DonorType::class, $donor);
		
		$form->handleRequest($request);

		$errors = null;
		
 		if ($form->isSubmitted() && !$form->get('save')->isClicked()) {

			//get id from dropdown menu
			$id = $request->request->getIterator()->current();
			return $this->redirectToRoute('donorForm', array('id'=> $id));
			
			} else if ($form->isSubmitted() && $form->isValid()){		
				$em->persist($donor);
				
				$donations = $donor->getDonations();
				
				//save current and new donations
				foreach ($donations as $donation){
					$em->persist($donation);
				}
				// remove the relationship between the donation and the donor
				foreach ($originalDonations as $originalDonation) {
					if (false === $donor->getDonations()->contains($originalDonation)) {
						// delete the Donation
						$em->remove($originalDonation);
					}
				}
								
				$em->flush();
				$id = $donor->getID();
				return $this->redirectToRoute('donorForm', array('id'=> $id));
			}	else if ($form->isSubmitted() && !$form->isValid()){
				$errors = 'not valid';
			}

	    return $this->render('default/donorForm.html.twig', array(
	        'form' => $form->createView(),
	        'allDonorVolunteers' => $allDonorVolunteers,
	        'id' => $id,
	        'errors' => $errors,
	    ));
	}
}
?>
