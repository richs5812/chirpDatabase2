<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Referral;

class ReferralReportController extends Controller
{
    /**
     * @Route("/form/referralReport", name="referralReport")
     */
    public function referralReportAction(Request $request)
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

    	//start report queries

		//number of referrals in time period
    	$referralCountQuery = $em->createQuery(
			'SELECT COUNT(r.id)
			FROM AppBundle:Referral r
			WHERE r.date BETWEEN :date1 AND :date2');
		$referralCountQuery->setParameter('date1', $date1);
		$referralCountQuery->setParameter('date2', $date2);
		$referralCount = $referralCountQuery->getSingleScalarResult();
		        
		//number of referrals for females in time period
    	$femaleReferralCountQuery = $em->createQuery(
			'SELECT COUNT(r.id)
			FROM AppBundle:Referral r
			JOIN AppBundle:Client c
			WITH c.id = r.client
			WHERE r.date BETWEEN :date1 AND :date2
			AND c.gender = :gender');
		$femaleReferralCountQuery->setParameter('date1', $date1);
		$femaleReferralCountQuery->setParameter('date2', $date2);
		$femaleReferralCountQuery->setParameter('gender', 'F');
		$femaleReferralCount = $femaleReferralCountQuery->getSingleScalarResult();

		//number of referrals for males in time period
    	$maleReferralCountQuery = $em->createQuery(
			'SELECT COUNT(r.id)
			FROM AppBundle:Referral r
			JOIN AppBundle:Client c
			WITH c.id = r.client
			WHERE r.date BETWEEN :date1 AND :date2
			AND c.gender = :gender');
		$maleReferralCountQuery->setParameter('date1', $date1);
		$maleReferralCountQuery->setParameter('date2', $date2);
		$maleReferralCountQuery->setParameter('gender', 'M');
		$maleReferralCount = $maleReferralCountQuery->getSingleScalarResult();

		//number of referrals for null gender in time period
    	$nullGenderReferralCountQuery = $em->createQuery(
			'SELECT COUNT(r.id)
			FROM AppBundle:Referral r
			JOIN AppBundle:Client c
			WITH c.id = r.client
			WHERE r.date BETWEEN :date1 AND :date2
			AND c.gender IS NULL');
		$nullGenderReferralCountQuery->setParameter('date1', $date1);
		$nullGenderReferralCountQuery->setParameter('date2', $date2);
		$nullGenderReferralCount = $nullGenderReferralCountQuery->getSingleScalarResult();
		
		//query referral types and number of referrals for each
		$referralNamesQuery = $em->createQuery('SELECT r FROM AppBundle:ReferralName r ORDER BY r.name ASC');
		$referralNamesResults = $referralNamesQuery->getResult();
		//dump($referralNamesResult);die;
		foreach($referralNamesResults as $referralNamesResult) {
			$referralNameCountQuery = $em->createQuery(
				'SELECT COUNT(r.id)
				FROM AppBundle:Referral r
				WHERE r.date BETWEEN :date1 AND :date2
				AND r.referralName = :referralNameID');
			$referralNameCountQuery->setParameter('date1', $date1);
			$referralNameCountQuery->setParameter('date2', $date2);
			$referralNameCountQuery->setParameter('referralNameID', $referralNamesResult->getId());
			$referralNameCountResult = $referralNameCountQuery->getSingleScalarResult();		
			$referralNamesResult->setCount($referralNameCountResult);
		}

        return $this->render('default/referralReport.html.twig', array(
        	'referralCount' => $referralCount,
        	'femaleReferralCount' => $femaleReferralCount,
        	'maleReferralCount' => $maleReferralCount,
        	'nullGenderReferralCount' => $nullGenderReferralCount,
        	'referralNamesResults' => $referralNamesResults,
        	'date1' => $date1,
        	'date2' => $date2,
        ));
    }
}
