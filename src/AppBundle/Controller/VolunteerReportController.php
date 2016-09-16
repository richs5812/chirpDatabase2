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
		
// 		dump($donorVolunteers);die;
		
		foreach($donorVolunteers as $donorVolunteer) {
			$volunteerHoursQuery = $em->createQuery(
				'SELECT SUM(s.hours)
				FROM AppBundle:VolunteerSession s
				WHERE s.donorVolunteer = :volunteer');
			$volunteerHoursQuery->setParameter('volunteer', $donorVolunteer);
			$volunteerHoursResult = $volunteerHoursQuery->getSingleScalarResult();		
			$donorVolunteer->setTotalHours($volunteerHoursResult);
		}
		
		foreach($donorVolunteers as $donorVolunteer) {
			$volunteerDonationsQuery = $em->createQuery(
				'SELECT SUM(d.amount)
				FROM AppBundle:Donation d
				WHERE d.donorVolunteer = :donor');
			$volunteerDonationsQuery->setParameter('donor', $donorVolunteer);
			$volunteerDonationsResult = $volunteerDonationsQuery->getSingleScalarResult();		
			$donorVolunteer->setTotalDonations($volunteerDonationsResult);
		}
		

        return $this->render('default/volunteerReport.html.twig', array(
        	'donorVolunteers' => $donorVolunteers,
        ));
    }
}
