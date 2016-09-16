<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\DonorVolunteer;

class VolunteerReportController extends Controller
{
    /**
     * @Route("/form/volunteerReport", name="volunteerReport")
     */
    public function volunteerReportAction(Request $request)
    {
    	$em = $this->getDoctrine()->getManager();

		if(isset($request->query->getIterator()["formDatePicker1"])){
			$date1=date_create($request->query->getIterator()["formDatePicker1"]);
			$date1=date_format($date1,"Y-m-d");
		} else {
    		$date1 = date_create('first day of this month');
    		$date1 = date_format($date1,"Y-m-d");
    	}
		
		if(isset($request->query->getIterator()["formDatePicker2"])){
			$date2=date_create($request->query->getIterator()["formDatePicker2"]);
			$date2=date_format($date2,"Y-m-d");
		} else {
    		$date2 = date_create('last day of this month');
    		$date2 = date_format($date2,"Y-m-d");
    	}

    	//start volunteer queries
    	
    	//find total volunteer hours in time period per volunteer
		$volunteerRepository = $this->getDoctrine()->getRepository('AppBundle:DonorVolunteer');
		$volunteers = $volunteerRepository->findAll();
		
		foreach($volunteers as $volunteer) {
			$volunteerHoursQuery = $em->createQuery(
				'SELECT SUM(s.hours)
				FROM AppBundle:VolunteerSession s
				WHERE s.date BETWEEN :date1 AND :date2
				AND s.donorVolunteer = :volunteer');
			$volunteerHoursQuery->setParameter('date1', $date1);
			$volunteerHoursQuery->setParameter('date2', $date2);
			$volunteerHoursQuery->setParameter('volunteer', $volunteer);
			$volunteerHoursResult = $volunteerHoursQuery->getSingleScalarResult();		
			$volunteer->setTotalHours($volunteerHoursResult);
		}
		
// 		dump($volunteers);die;

        return $this->render('default/volunteerReport.html.twig', array(
        	'volunteers' => $volunteers,
        	'date1' => $date1,
        	'date2' => $date2,
        ));
    }
}
