<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Client;
use AppBundle\Entity\Appointment;
use AppBundle\Entity\FamilyMember;

class ChirpReportController extends Controller
{
    /**
     * @Route("/form/chirpReport", name="chirpReport")
     */
    public function chirpReportAction(Request $request)
    {
    	$em = $this->getDoctrine()->getManager();

		if(isset($request->query->getIterator()["formAgePicker1"])){
			$age1 = $request->query->getIterator()["formAgePicker1"];
		} else {
    		$age1 = 0;
    	}

		if(isset($request->query->getIterator()["formAgePicker2"])){
			$age2 = $request->query->getIterator()["formAgePicker2"];
		} else {
    		$age2 = 10;
    	}

		//query to identify heads of household with family members in age range
		$householdsQuery = $em->createQuery(
			'SELECT DISTINCT c
			FROM AppBundle:Client c
			JOIN AppBundle:FamilyMember f
			WITH c.id = f.client
			WHERE f.age BETWEEN :age1 AND :age2
			ORDER BY c.lastName ASC');
		$householdsQuery->setParameter('age1', $age1);
		$householdsQuery->setParameter('age2', $age2);
		$households = $householdsQuery->getResult();
		
				  		  
        return $this->render('default/chirpReport.html.twig', array(
        	'households' => $households,
        	'age1' => $age1,
        	'age2' => $age2,
        ));
    }
}
