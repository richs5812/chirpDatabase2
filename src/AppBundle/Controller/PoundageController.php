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
    
    	if(isset($request->query->getIterator()["UpdatePoundage"])) {
    		//dump($request->query->getIterator());die;
    		
			$poundage = $this->getDoctrine()
				->getRepository('AppBundle:Poundage')
				->findOneById($request->query->getIterator()["PoundageID"]);
			
			//dump($appointment);die;
			
			//$poundageDate=date_create($request->query->getIterator()["PoundageDate"]);
			//$poundage->setDate($poundageDate);
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
	   	
		$query = $em->createQuery(
			'SELECT p
			FROM AppBundle:Poundage p
			ORDER BY p.date ASC'
		);

		$poundages = $query->getResult();

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
        ));

	}
}
?>
