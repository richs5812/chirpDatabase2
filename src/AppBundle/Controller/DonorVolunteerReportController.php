<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\DonorVolunteer;

class DonorVolunteerReportController extends Controller
{
    /**
     * @Route("/form/donorVolunteerReport", name="donorVolunteerReport")
     */
    public function donorVolunteerReportAction(Request $request)
    {
    	$em = $this->getDoctrine()->getManager();

    	//start volunteer queries
    	
    	//find total volunteer hours in time period per volunteer
		$donorVolunteersQuery = $em->createQuery(
			'SELECT d
			FROM AppBundle:DonorVolunteer d
			ORDER BY d.lastName ASC');
		$donorVolunteers = $donorVolunteersQuery->getResult();		
		
		foreach($donorVolunteers as $donorVolunteer) {
			$recentVolunteerDateQuery = $em->createQuery(
				'SELECT s.date
				FROM AppBundle:VolunteerSession s
				WHERE s.donorVolunteer = :volunteer
				ORDER BY s.date DESC');
			$recentVolunteerDateQuery->setParameter('volunteer', $donorVolunteer);
			$recentVolunteerDateResult = $recentVolunteerDateQuery->setMaxResults(1)->getOneOrNullResult();		
			$donorVolunteer->setMostRecentVolunteerDate($recentVolunteerDateResult);
		}
		
		foreach($donorVolunteers as $donorVolunteer) {
			$recentDonationQuery = $em->createQuery(
				'SELECT d.date
				FROM AppBundle:Donation d
				WHERE d.donorVolunteer = :volunteer
				ORDER BY d.date DESC');
			$recentDonationQuery->setParameter('volunteer', $donorVolunteer);
			$recentDonationResult = $recentDonationQuery->setMaxResults(1)->getOneOrNullResult();		
			$donorVolunteer->setMostRecentDonationDate($recentDonationResult);
		}
		
		

        return $this->render('default/donorVolunteerReport.html.twig', array(
        	'donorVolunteers' => $donorVolunteers,
        ));
    }
}
