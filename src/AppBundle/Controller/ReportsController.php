<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Client;

class ReportsController extends Controller
{
    /**
     * @Route("/form/reports", name="reports")
     */
    public function reportsAction(Request $request)
    {
    	$em = $this->getDoctrine()->getManager();

		if(isset($request->query->getIterator()["formDatePicker1"])){
			$date1=date_create($request->query->getIterator()["formDatePicker1"]);
			$date1=date_format($date1,"Y-m-d");
			//dump($date1);die;
		} else {
    		$date1='2016-05-01';
    	}
		
		if(isset($request->query->getIterator()["formDatePicker2"])){
			$date2=date_create($request->query->getIterator()["formDatePicker2"]);
			$date2=date_format($date2,"Y-m-d");
			//dump($date1);die;
		} else {
    		$date2='2016-05-31';
    	}
    	
		$query = $em->createQuery(
			'SELECT COUNT(c.id)
			FROM AppBundle:Client c
			WHERE c.enrollmentDate BETWEEN :date1 AND :date2');
		$query->setParameter('date1', $date1);
		$query->setParameter('date2', $date2);

		//$householdCount = $query->getResult();
		$householdCount = $query->getSingleScalarResult();
		//dump($householdCount);die;
    
        return $this->render('default/reports.html.twig', array(
        	'householdCount' => $householdCount,
        	'date1' => $date1,
        	'date2' => $date2,
        ));
    }
}
