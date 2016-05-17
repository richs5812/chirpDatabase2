<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Client;
use AppBundle\Entity\Appointment;
use AppBundle\Entity\FamilyMember;

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
    	
    	//start report queries

		//number of households served
    	$householdQuery = $em->createQuery(
			'SELECT COUNT(c.id)
			FROM AppBundle:Client c
			JOIN AppBundle:Appointment a
			WITH c.id = a.client
			WHERE a.date BETWEEN :date1 AND :date2');
		$householdQuery->setParameter('date1', $date1);
		$householdQuery->setParameter('date2', $date2);
		$householdCount = $householdQuery->getSingleScalarResult();

		//total number of individuals served	
		//figure out total number of family members served
		$headOfHouseholdServedQuery = $em->createQuery(
			'SELECT c.id
			FROM AppBundle:Client c
			JOIN AppBundle:Appointment a
			WITH c.id = a.client
			WHERE a.date BETWEEN :date1 AND :date2');
		$headOfHouseholdServedQuery->setParameter('date1', $date1);
		$headOfHouseholdServedQuery->setParameter('date2', $date2);
		$headOfHouseholdsServed = $headOfHouseholdServedQuery->getResult();
		//dump($individualsCount);die;
		
		$familyMemberCount = array();
		$i = 0;
		
		foreach ($headOfHouseholdsServed as $headOfHousehold) {
			$familyMembersServedQuery = $em->createQuery(
			'SELECT COUNT(f.id)
			FROM AppBundle:FamilyMember f
			JOIN AppBundle:Client c
			WITH f.client = c.id
			WHERE c.id = :clientID');
			$familyMembersServedQuery->setParameter('clientID', $headOfHousehold['id']);
			$familyMembersServedCount[$i] = $familyMembersServedQuery->getSingleScalarResult();
			$i++;
		}
				
		$familyMembersServedSum = 0;
		foreach ($familyMembersServedCount as $familyMemberServed) {
			$familyMembersServedSum += $familyMemberServed;
		}

		//add number of households served (= heads of household number)
		$individualsServed = $householdCount + $familyMembersServedSum;
		
		
		//females served count
		//heads of household
    	$femaleHouseholdQuery = $em->createQuery(
			'SELECT COUNT(c.id)
			FROM AppBundle:Client c
			JOIN AppBundle:Appointment a
			WITH c.id = a.client
			WHERE a.date BETWEEN :date1 AND :date2
			AND c.gender = :gender');
		$femaleHouseholdQuery->setParameter('date1', $date1);
		$femaleHouseholdQuery->setParameter('date2', $date2);
		$femaleHouseholdQuery->setParameter('gender', 'F');
		$femaleHouseholdCount = $femaleHouseholdQuery->getSingleScalarResult();		
		
		//familyMembers
		$femaleFamilyMembersServedCount = array();
		$k = 0;

		foreach ($headOfHouseholdsServed as $headOfHousehold) {
			$femaleFamilyMembersServedQuery = $em->createQuery(
			'SELECT COUNT(f.id)
			FROM AppBundle:FamilyMember f
			WHERE f.client = :clientID
			AND f.gender = :gender');
			$femaleFamilyMembersServedQuery->setParameter('clientID', $headOfHousehold['id']);
			$femaleFamilyMembersServedQuery->setParameter('gender', 'F');
			$femaleFamilyMembersServedCount[$k] = $femaleFamilyMembersServedQuery->getSingleScalarResult();
			$k++;
		}
				
		$femaleFamilyMembersServedSum = 0;
		foreach ($femaleFamilyMembersServedCount as $femaleFamilyMemberServed) {
			$femaleFamilyMembersServedSum += $femaleFamilyMemberServed;
		}
		
		//add number of households served (= heads of household number)
		$femalesServed = $femaleHouseholdCount + $femaleFamilyMembersServedSum;

		//males served count
		//heads of household
    	$maleHouseholdQuery = $em->createQuery(
			'SELECT COUNT(c.id)
			FROM AppBundle:Client c
			JOIN AppBundle:Appointment a
			WITH c.id = a.client
			WHERE a.date BETWEEN :date1 AND :date2
			AND c.gender = :gender');
		$maleHouseholdQuery->setParameter('date1', $date1);
		$maleHouseholdQuery->setParameter('date2', $date2);
		$maleHouseholdQuery->setParameter('gender', 'M');
		$maleHouseholdCount = $maleHouseholdQuery->getSingleScalarResult();		
		
		//familyMembers
		$maleFamilyMemberCount = array();
		$m = 0;
		
		foreach ($headOfHouseholdsServed as $headOfHousehold) {
			$maleFamilyMembersServedQuery = $em->createQuery(
			'SELECT COUNT(f.id)
			FROM AppBundle:FamilyMember f
			WHERE f.client = :clientID
			AND f.gender = :gender');
			$maleFamilyMembersServedQuery->setParameter('clientID', $headOfHousehold['id']);
			$maleFamilyMembersServedQuery->setParameter('gender', 'M');
			$maleFamilyMembersServedCount[$m] = $maleFamilyMembersServedQuery->getSingleScalarResult();
			$m++;
		}
		//dump($maleFamilyMembersServedCount);die;
				
		$maleFamilyMembersServedSum = 0;
		foreach ($maleFamilyMembersServedCount as $maleFamilyMemberServed) {
			$maleFamilyMembersServedSum += $maleFamilyMemberServed;
		}
		
		//add number of households served (= heads of household number)
		$malesServed = $maleHouseholdCount + $maleFamilyMembersServedSum;		

		//new households    	
		$newHouseholdQuery = $em->createQuery(
			'SELECT COUNT(c.id)
			FROM AppBundle:Client c
			WHERE c.enrollmentDate BETWEEN :date1 AND :date2');
		$newHouseholdQuery->setParameter('date1', $date1);
		$newHouseholdQuery->setParameter('date2', $date2);
		$newHouseholdCount = $newHouseholdQuery->getSingleScalarResult();		
		
    
        return $this->render('default/reports.html.twig', array(
        	'householdCount' => $householdCount,
        	'individualsCount' => $individualsServed,
        	'femalesCount' => $femalesServed,
        	'malesCount' => $malesServed,
        	'newHouseholdCount' => $newHouseholdCount,
        	'date1' => $date1,
        	'date2' => $date2,
        ));
    }
}
