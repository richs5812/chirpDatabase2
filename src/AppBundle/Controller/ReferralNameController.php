<?php

namespace AppBundle\Controller;

use AppBundle\Entity\ReferralName;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Form\ReferralNameType;
		
class ReferralNameController extends Controller
{
	/**
     * @Route("/form/referralName", name="referralName")
     */
    public function ReferralNameAction(Request $request)
    {	

		$em = $this->getDoctrine()->getManager();
    
    	if(isset($request->query->getIterator()["UpdateReferralName"])) {
    		//dump($request->query->getIterator());die;
    		
			$referralName = $this->getDoctrine()
				->getRepository('AppBundle:ReferralName')
				->findOneById($request->query->getIterator()["ReferralNameID"]);
				
			$originalName = $referralName->getName();
			$updatedName = $request->query->getIterator()["ReferralName"];
			
			$referralName->setName($updatedName);

			$em->persist($referralName);
			$em->flush();
			
			return $this->redirectToRoute('referralName');

    	}
    	
    	if(isset($request->query->getIterator()["DeleteReferralName"])) {
			
			$referralName = $this->getDoctrine()
				->getRepository('AppBundle:ReferralName')
				->findOneById($request->query->getIterator()["ReferralNameID"]);
				
			$em->remove($referralName);
			$em->flush();
			
			return $this->redirectToRoute('referralName');

		}
    
    	$referralName = new ReferralName();
    	$form = $this->createForm(ReferralNameType::class, $referralName);
	   	$form->handleRequest($request);
	   	
		$referralNameQuery = $em->createQuery(
			'SELECT r
			FROM AppBundle:ReferralName r
			ORDER BY r.name ASC'
		);

		$referralNames = $referralNameQuery->getResult();
		
		if ($form->isSubmitted() && $form->isValid()) {
			// $form->getData() holds the submitted values
			// but, the original `$task` variable has also been updated
			$referralName = $form->getData();

			// ... perform some action, such as saving the task to the database
			// for example, if Task is a Doctrine entity, save it!
			 $em = $this->getDoctrine()->getManager();
			 $em->persist($referralName);
			 $em->flush();

			return $this->redirectToRoute('referralName');
		}
    	
        return $this->render('default/referralName.html.twig', array(
            'form' => $form->createView(),
            'referralNames' => $referralNames,
        ));

	}
}
?>
