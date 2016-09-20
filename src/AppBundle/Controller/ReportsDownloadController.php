<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReportsDownloadController extends Controller
{
    /**
     * @Route("/form/downloadReport", name="reportDownload")
     */
    public function reportsDownloadAction(Request $request)
    {

		$list = array (
			array('Report Start Date', $request->request->getIterator()["date1"]),
			array('Report End Date', $request->request->getIterator()["date2"]),
			array('Households served', $request->request->getIterator()["householdCount"]),
			array('Individuals served', $request->request->getIterator()["individualsCount"]),
			array('Females served', $request->request->getIterator()["femalesCount"]),
			array('Males served', $request->request->getIterator()["malesCount"])
		);
		
		if ($request->request->getIterator()["nullGenderCount"] != 0) {
			 array_push($list,array('Individuals served with no gender assigned', $request->request->getIterator()["nullGenderCount"]));
		}
		
		array_push(
			$list,
			array('Children served in age range 0-5', $request->request->getIterator()["peopleServed05"]),
			array('Individuals served in age range 6-17', $request->request->getIterator()["peopleServed617"]),
			array('Individuals served in age range 18-64', $request->request->getIterator()["peopleServed1864"]),
			array('Individuals served ages 65+', $request->request->getIterator()["peopleServed65"])
		);
		
		if ($request->request->getIterator()["nullAgeCount"] != 0) {
			 array_push($list,array('Individuals served with no age assigned', $request->request->getIterator()["nullAgeCount"]));
		}
				
		array_push(
			$list,		
			array('New Households Served', $request->request->getIterator()["newHouseholdCount"]),
			array('New Households with Children ages 0-5 Served', $request->request->getIterator()["newHouseholdCount05"]),
			array('Pregnant women served', $request->request->getIterator()["pregnantCount"]),
			array('Poundage', $request->request->getIterator()["poundageSum"])
		);
	
		$fp = fopen('php://output', 'w+');
	
		foreach ($list as $fields) {
			fputcsv($fp, $fields);
		}

		fclose($fp);			

        // get the service container to pass to the closure
        $container = $this->container;
        $response = new StreamedResponse(function() use($container) {

        });

        $response->headers->set('Content-Type', 'application/force-download');
        $response->headers->set('Content-Disposition','attachment; filename="fisher_report.csv"');

        return $response;
    }
}
