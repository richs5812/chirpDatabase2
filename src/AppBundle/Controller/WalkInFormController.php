<?php
// src/AppBundle/Controller/WalkInFormController.php
namespace AppBundle\Controller;
use AppBundle\Entity\WalkIn;
use AppBundle\Entity\WalkInFamilyMember;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Form\WalkInType;
use AppBundle\Form\WalkInFamilyMemberType;
use Doctrine\Common\Collections\ArrayCollection;

class WalkInFormController extends Controller
{
	/**
     * @Route("/form/walkIn/{id}", name="walkInForm", defaults={"id" = "new walkIn"})
     */
    public function walkInFormAction(Request $request, $id)
    {	
		$em = $this->getDoctrine()->getManager();
		
		if ($id == "new walkIn")
			{
				$walkIn = new WalkIn();
				$walkIn->setDate(date_create(date("Y-m-d")));
// 				dump($walkIn);die;
			} else {
				// query by the primary key (usually "id")
				$walkInQuery = $em->createQuery(
					'SELECT w
					FROM AppBundle:WalkIn w
					WHERE w.id = :walkInID'
				)->setParameter('walkInID', $id);
				$walkIn = $walkInQuery->getResult()[0];
			}
								
		//arrays for removal functions
		$originalFamilyMembers = new ArrayCollection();

		// Create an ArrayCollection of the current FamilyMember objects in the database
		foreach ($walkIn->getWalkInFamilyMembers() as $familyMember) {
			$originalFamilyMembers->add($familyMember);
		}
				
		$form = $this->createForm(WalkInType::class, $walkIn);
		
		$form->handleRequest($request);
		$errors = null;
		
		if ($form->isSubmitted() && $form->isValid()){				
			$em->persist($walkIn);
			
			$familyMembers = $walkIn->getWalkInFamilyMembers();
			//save current and new familyMembers
			foreach ($familyMembers as $familyMember){
				$em->persist($familyMember);
			}
			// remove the relationship between the familyMember and the Client
			foreach ($originalFamilyMembers as $originalFamilyMember) {
				if (false === $walkIn->getWalkInFamilyMembers()->contains($originalFamilyMember)) {
					// delete the FamilyMember
					$em->remove($originalFamilyMember);
				}
			}

			$em->flush();
			$id = $walkIn->getID();
			return $this->redirectToRoute('walkInForm', array('id'=> $id));
		} else if ($form->isSubmitted() && !$form->isValid()){
			$errors = $form->getErrors(true)->current();
		}	
	    return $this->render('default/walkInForm.html.twig', array(
	        'form' => $form->createView(),
// 	        'date' => $date,
	        'errors' => $errors,
	    ));
	}
}
?>
