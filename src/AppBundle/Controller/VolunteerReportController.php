<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\DonorVolunteer;
use AppBundle\Entity\VolunteerCategory;

class VolunteerReportController extends Controller
{
    /**
     * @Route("/form/volunteerReport/{category}", name="volunteerReport", defaults={"category" = "all"})
     */
    public function volunteerReportAction(Request $request, $category)
    {
    	$em = $this->getDoctrine()->getManager();

		$volunteerCategoriesQuery = $em->createQuery(
			"SELECT c 
			FROM AppBundle:VolunteerCategory c
			ORDER BY c.category ASC");
		$volunteerCategories = $volunteerCategoriesQuery->getResult();

		if ($category == "all") {
			$volunteersQuery = $em->createQuery("SELECT v FROM AppBundle:DonorVolunteer v JOIN v.volunteerCategories c");
			$volunteers = $volunteersQuery->getResult();
		} else {
			$volunteersQuery = $em->createQuery("SELECT v FROM AppBundle:DonorVolunteer v JOIN v.volunteerCategories c WHERE c.category = :category");
			$volunteersQuery->setParameter('category', $category);
			$volunteers = $volunteersQuery->getResult();			
		}
		
		foreach($volunteers as $volunteer) {
			$recentVolunteerDateQuery = $em->createQuery(
				'SELECT s.date
				FROM AppBundle:VolunteerSession s
				WHERE s.donorVolunteer = :volunteer
				ORDER BY s.date DESC');
			$recentVolunteerDateQuery->setParameter('volunteer', $volunteer);
			$recentVolunteerDateResult = $recentVolunteerDateQuery->setMaxResults(1)->getOneOrNullResult();		
			$volunteer->setMostRecentVolunteerDate($recentVolunteerDateResult);
		}

        return $this->render('default/volunteerReport.html.twig', array(
        	'category' => $category,
        	'volunteers' => $volunteers,
        	'volunteerCategories' => $volunteerCategories,
        ));
    }
}
