<?php

namespace AppBundle\Controller;

use AppBundle\Entity\FocusGroupName;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Collections\ArrayCollection;
use AppBundle\Form\FocusGroupNameType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
		
class FocusGroupNameController extends Controller
{
	/**
     * @Route("/form/focusGroupName", name="focusGroupName")
     */
    public function FocusGroupNameAction(Request $request)
    {	

		$em = $this->getDoctrine()->getManager();
    
    	if(isset($request->query->getIterator()["UpdateFocusGroupName"])) {
    		//dump($request->query->getIterator());die;
    		
			$focusGroupName = $this->getDoctrine()
				->getRepository('AppBundle:FocusGroupName')
				->findOneById($request->query->getIterator()["FocusGroupNameID"]);
			
			//dump($appointment);die;
			
			$focusGroupName->setGroupName($request->query->getIterator()["FocusGroupName"]);
			//$focusGroupName->setNote($request->query->getIterator()["FocusGroupNameNote"]);

			$em->persist($focusGroupName);
			$em->flush();
			
			return $this->redirectToRoute('focusGroupName');

    	}
    	
    	if(isset($request->query->getIterator()["DeleteFocusGroupName"])) {
			
			$focusGroupName = $this->getDoctrine()
				->getRepository('AppBundle:FocusGroupName')
				->findOneById($request->query->getIterator()["FocusGroupNameID"]);

			$em->remove($focusGroupName);
			$em->flush();
			
			return $this->redirectToRoute('focusGroupName');

		}
    
    	$focusGroupName = new FocusGroupName();
    	$form = $this->createForm(FocusGroupNameType::class, $focusGroupName);
	   	$form->handleRequest($request);
	   	
		$focusGroupNameQuery = $em->createQuery(
			'SELECT f
			FROM AppBundle:FocusGroupName f
			ORDER BY f.groupName ASC'
		);

		$focusGroupNames = $focusGroupNameQuery->getResult();
		
		if ($form->isSubmitted() && $form->isValid()) {
			// $form->getData() holds the submitted values
			// but, the original `$task` variable has also been updated
			$focusGroupName = $form->getData();

			// ... perform some action, such as saving the task to the database
			// for example, if Task is a Doctrine entity, save it!
			 $em = $this->getDoctrine()->getManager();
			 $em->persist($focusGroupName);
			 $em->flush();

			return $this->redirectToRoute('focusGroupName');
		}
    	
        return $this->render('default/focusGroupName.html.twig', array(
            'form' => $form->createView(),
            'focusGroupNames' => $focusGroupNames,
        ));

	}
}
?>
