<?php

namespace AppBundle\Controller;

use AppBundle\Entity\VolunteerCategory;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Collections\ArrayCollection;
use AppBundle\Form\EditVolunteerCategoryType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
		
class VolunteerCategoryController extends Controller
{
	/**
     * @Route("/form/volunteerCategory", name="volunteerCategory")
     */
    public function VolunteerCategoryAction(Request $request)
    {	

		$em = $this->getDoctrine()->getManager();
    
    	if(isset($request->query->getIterator()["UpdateVolunteerCategory"])) {
    		//dump($request->query->getIterator());die;
    		
			$volunteerCategory = $this->getDoctrine()
				->getRepository('AppBundle:VolunteerCategory')
				->findOneById($request->query->getIterator()["VolunteerCategoryID"]);
				
			$originalCategory = $volunteerCategory->getCategory();
			$updatedCategory = $request->query->getIterator()["VolunteerCategory"];
			
			$volunteerCategory->setCategory($updatedCategory);

			$em->persist($volunteerCategory);
			$em->flush();
			
			return $this->redirectToRoute('volunteerCategory');

    	}
    	
    	if(isset($request->query->getIterator()["DeleteVolunteerCategory"])) {
			
			$volunteerCategory = $this->getDoctrine()
				->getRepository('AppBundle:VolunteerCategory')
				->findOneById($request->query->getIterator()["VolunteerCategoryID"]);
				
			$em->remove($volunteerCategory);
			$em->flush();
			
			return $this->redirectToRoute('volunteerCategory');

		}
    
    	$volunteerCategory = new VolunteerCategory();
    	$form = $this->createForm(EditVolunteerCategoryType::class, $volunteerCategory);
	   	$form->handleRequest($request);
	   	
		$volunteerCategoryQuery = $em->createQuery(
			'SELECT v
			FROM AppBundle:VolunteerCategory v
			ORDER BY v.category ASC'
		);

		$volunteerCategories = $volunteerCategoryQuery->getResult();
		
		if ($form->isSubmitted() && $form->isValid()) {
			// $form->getData() holds the submitted values
			// but, the original `$task` variable has also been updated
			$volunteerCategory = $form->getData();

			// ... perform some action, such as saving the task to the database
			// for example, if Task is a Doctrine entity, save it!
			 $em = $this->getDoctrine()->getManager();
			 $em->persist($volunteerCategory);
			 $em->flush();

			return $this->redirectToRoute('volunteerCategory');
		}
    	
        return $this->render('default/editVolunteerCategory.html.twig', array(
            'form' => $form->createView(),
            'volunteerCategories' => $volunteerCategories,
        ));

	}
}
?>
