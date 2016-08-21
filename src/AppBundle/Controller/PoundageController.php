<?php

// src/AppBundle/Controller/FormController.php
namespace AppBundle\Controller;

use AppBundle\Entity\Poundage;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Collections\ArrayCollection;
use AppBundle\Form\PoundageType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
		
class PoundageController extends Controller
{
	/**
     * @Route("/form/poundage", name="poundage")
     */
    public function PoundageAction(Request $request)
    {	

		$em = $this->getDoctrine()->getManager();

		//set dates for poundage form
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

    
    	if(isset($request->query->getIterator()["UpdatePoundage"])) {
    		//dump($request->query->getIterator());die;
    		
			$poundage = $this->getDoctrine()
				->getRepository('AppBundle:Poundage')
				->findOneById($request->query->getIterator()["PoundageID"]);
			
			//dump($appointment);die;
			
			$poundageDate=date_create($request->query->getIterator()["PoundageDate"]);
			$poundage->setDate($poundageDate);
			$poundage->setPoundage($request->query->getIterator()["PoundageAmount"]);
			$poundage->setNote($request->query->getIterator()["PoundageNote"]);

			$em->persist($poundage);
			$em->flush();
			
			return $this->redirectToRoute('poundage');

    	}
    	
    	if(isset($request->query->getIterator()["DeletePoundage"])) {
			
			$poundage = $this->getDoctrine()
				->getRepository('AppBundle:Poundage')
				->findOneById($request->query->getIterator()["PoundageID"]);

			$em->remove($poundage);
			$em->flush();
			
			return $this->redirectToRoute('poundage');

		}
    
    	$poundage = new Poundage();
    	$form = $this->createForm(PoundageType::class, $poundage);
	   	$form->handleRequest($request);
	   	
		$poundageQuery = $em->createQuery(
			'SELECT p
			FROM AppBundle:Poundage p
			WHERE p.date BETWEEN :date1 AND :date2
			ORDER BY p.date ASC'
		);
		$poundageQuery->setParameter('date1', $date1);
		$poundageQuery->setParameter('date2', $date2);

		$poundages = $poundageQuery->getResult();
		
		$poundagesArray = array();
		$i = 0;
		
		foreach ($poundages as $poundage) {
			$poundagesArray[$i] = $poundage->getPoundage();
			$i++;
		}
		
		$poundageSum = array_sum($poundagesArray);
		
		if ($form->isSubmitted() && $form->isValid()) {
			// $form->getData() holds the submitted values
			// but, the original `$task` variable has also been updated
			$poundage = $form->getData();

			// ... perform some action, such as saving the task to the database
			// for example, if Task is a Doctrine entity, save it!
			 $em = $this->getDoctrine()->getManager();
			 $em->persist($poundage);
			 $em->flush();

			return $this->redirectToRoute('poundage');
		}
    	
        return $this->render('default/poundage.html.twig', array(
            'form' => $form->createView(),
            'poundages' => $poundages,
            'poundageSum' => $poundageSum,
            'date1' => $date1,
        	'date2' => $date2,
        ));

	}
}
?>
