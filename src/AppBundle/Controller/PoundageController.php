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
    
    	$poundage = new Poundage();
    	$form = $this->createForm(PoundageType::class, $poundage);
    	
	   $form->handleRequest($request);

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
        ));

	}
}
?>
