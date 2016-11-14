<?php

namespace AppBundle\Controller;

use AppBundle\Entity\WalkIn;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Collections\ArrayCollection;
use AppBundle\Form\WalkInType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
		
class WalkInListController extends Controller
{
	/**
     * @Route("/form/walkInList", name="walkInList")
     */
    public function WalkInListAction(Request $request)
    {	
		$em = $this->getDoctrine()->getManager();

		//set dates for walkIn form
		if(isset($request->query->getIterator()["formDatePicker1"])){
			$date1=date_create($request->query->getIterator()["formDatePicker1"]);
			$date1=date_format($date1,"Y-m-d");
			//dump($date1);die;
		} else {
    		//$date1='2016-05-01';
    		$date1 = date_create('first day of this month');
    		$date1 = date_format($date1,"Y-m-d");
    	}
		
		if(isset($request->query->getIterator()["formDatePicker2"])){
			$date2=date_create($request->query->getIterator()["formDatePicker2"]);
			$date2=date_format($date2,"Y-m-d");
			//dump($date1);die;
		} else {
    		//$date2='2016-05-31';
    		$date2 = date_create('last day of this month');
    		$date2 = date_format($date2,"Y-m-d");
    	}
    	
    	if(isset($request->query->getIterator()["DeleteWalkIn"])) {
			
			$walkIn = $this->getDoctrine()
				->getRepository('AppBundle:WalkIn')
				->findOneById($request->query->getIterator()["WalkInID"]);
		
			$familyMembers = $walkIn->getWalkInFamilyMembers();
			foreach ($familyMembers as $familyMember){
				$em->remove($familyMember);
			}

			$em->remove($walkIn);
			$em->flush();
			
			return $this->redirectToRoute('walkInList');

		}
    	   	
		$walkInQuery = $em->createQuery(
			'SELECT w
			FROM AppBundle:WalkIn w
			WHERE w.date BETWEEN :date1 AND :date2
			ORDER BY w.date ASC'
		);
		$walkInQuery->setParameter('date1', $date1);
		$walkInQuery->setParameter('date2', $date2);
		$walkIns = $walkInQuery->getResult();
		
		foreach ($walkIns as $walkInClient) {
			$familyCountQuery = $em->createQuery(
			'SELECT COUNT(f.id)
			FROM AppBundle:WalkInFamilyMember f
			WHERE f.walkIn = :walkInID');
			$familyCountQuery->setParameter('walkInID', $walkInClient->getId());
			$familyCount = $familyCountQuery->getSingleScalarResult();
			$walkInClient->setFamilyCount($familyCount);
		}
		
		//create form for new walk-in		
		$walkIn = new WalkIn();
		$walkIn->setDate(date_create(date("Y-m-d")));
		
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

			$em->flush();
			return $this->redirectToRoute('walkInList');
		} else if ($form->isSubmitted() && !$form->isValid()){
			$errors = $form->getErrors(true)->current();
		}	
				
        return $this->render('default/walkInList.html.twig', array(
	        'form' => $form->createView(),
	        'errors' => $errors,
            'walkIns' => $walkIns,
            'date1' => $date1,
        	'date2' => $date2,
        ));

	}
}
?>
