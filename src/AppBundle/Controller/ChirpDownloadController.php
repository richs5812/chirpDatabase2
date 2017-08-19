<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

class ChirpDownloadController extends Controller
{
    /**
     * @Route("/form/downloadChirpReport", name="chirpReportDownload")
     */
    public function chirpDownloadAction(Request $request)
    {
		if(isset($request->request->getIterator()["age1"])){
			$age1 = $request->request->getIterator()["age1"];
		} else {
    		$age1 = 0;
    	}

		if(isset($request->request->getIterator()["age2"])){
			$age2 = $request->request->getIterator()["age2"];
		} else {
    		$age2 = 10;
    	}

    	$em = $this->getDoctrine()->getManager();

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
		
		
		$list = array (
			array('*Report for households with family members between age ' .$age1. ' and ' . $age2 .'*'),
			array('Last Name', 'First Name', 'Home Phone', 'Cell Phone', 'Most Recent Appointment')
		);
		
		$recentAppt = '';	
		$householdArray = array();
		foreach ($households as $household) {
			$appts = $household->getAppointments();
			if ($appts->count() != 0) {
				$recentAppt = $appts->last()->getDate()->format('Y-m-d');
			} else {
				$recentAppt = '';	
			}
		
			array_push(
				$list,
				array($household->getLastName(), $household->getFirstName(), $household->getHomePhoneNumber(), $household->getCellPhoneNumber(), $recentAppt)
			);
		}
		
			
		$fiveMBs = 5 * 1024 * 1024;
		$fp = fopen("php://temp/maxmemory:$fiveMBs", 'r+');

		foreach ($list as $fields) {
			fputcsv($fp, $fields);
		}		
		// Read what we have written.
		rewind($fp);
		
		$fileContent = stream_get_contents($fp); // the generated file content
		$response = new Response($fileContent);

		$disposition = $response->headers->makeDisposition(
			ResponseHeaderBag::DISPOSITION_ATTACHMENT,
			'chirp-report.csv'
		);

		$response->headers->set('Content-Disposition', $disposition);
		$response->headers->set('Content-Type', 'text/csv');

        return $response;
    }
}
