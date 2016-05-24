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
			WHERE a.date BETWEEN :date1 AND :date2
			AND a.status = :status');
		$householdQuery->setParameter('date1', $date1);
		$householdQuery->setParameter('date2', $date2);
		$householdQuery->setParameter('status', 'Kept Appointment');
		$householdCount = $householdQuery->getSingleScalarResult();

		//total number of individuals served	
		//query to identify heads of household served
		$headOfHouseholdServedQuery = $em->createQuery(
			'SELECT c.id
			FROM AppBundle:Client c
			JOIN AppBundle:Appointment a
			WITH c.id = a.client
			WHERE a.date BETWEEN :date1 AND :date2
			AND a.status = :status');
		$headOfHouseholdServedQuery->setParameter('date1', $date1);
		$headOfHouseholdServedQuery->setParameter('date2', $date2);
		$headOfHouseholdServedQuery->setParameter('status', 'Kept Appointment');
		$headOfHouseholdsServed = $headOfHouseholdServedQuery->getResult();
		//dump($individualsCount);die;
		
		//count family members per head of household served
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
		//identify female heads of household served
    	$femaleHouseholdQuery = $em->createQuery(
			'SELECT COUNT(c.id)
			FROM AppBundle:Client c
			JOIN AppBundle:Appointment a
			WITH c.id = a.client
			WHERE a.date BETWEEN :date1 AND :date2
			AND a.status = :status
			AND c.gender = :gender');
		$femaleHouseholdQuery->setParameter('date1', $date1);
		$femaleHouseholdQuery->setParameter('date2', $date2);
		$femaleHouseholdQuery->setParameter('status', 'Kept Appointment');
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
			AND a.status = :status
			AND c.gender = :gender');
		$maleHouseholdQuery->setParameter('date1', $date1);
		$maleHouseholdQuery->setParameter('date2', $date2);
		$maleHouseholdQuery->setParameter('status', 'Kept Appointment');
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

		//number of people served ages 0-5
		//count family members per head of household served
		$familyMemberCount05 = array();
		$count05 = 0;
		
		foreach ($headOfHouseholdsServed as $headOfHousehold) {
			$familyMembersQuery05 = $em->createQuery(
			'SELECT COUNT(f.id)
			FROM AppBundle:FamilyMember f
			WHERE f.client = :clientID
			AND f.age BETWEEN :age1 AND :age2');
			$familyMembersQuery05->setParameter('clientID', $headOfHousehold['id']);
			$familyMembersQuery05->setParameter('age1', '0');
			$familyMembersQuery05->setParameter('age2', '5');
			$familyMemberCount05[$count05] = $familyMembersQuery05->getSingleScalarResult();
			$count05++;
		}
				
		$familyMembersServedSum05 = 0;
		foreach ($familyMemberCount05 as $familyMemberServed) {
			$familyMembersServedSum05 += $familyMemberServed;
		}
		
		//number of people served ages 6-17
		//count family members per head of household served
		$familyMemberCount617 = array();
		$count617 = 0;
		
		foreach ($headOfHouseholdsServed as $headOfHousehold) {
			$familyMembersQuery617 = $em->createQuery(
			'SELECT COUNT(f.id)
			FROM AppBundle:FamilyMember f
			WHERE f.client = :clientID
			AND f.age BETWEEN :age1 AND :age2');
			$familyMembersQuery617->setParameter('clientID', $headOfHousehold['id']);
			$familyMembersQuery617->setParameter('age1', '6');
			$familyMembersQuery617->setParameter('age2', '17');
			$familyMemberCount617[$count617] = $familyMembersQuery617->getSingleScalarResult();
			$count617++;
		}
				
		$familyMembersServedSum617 = 0;
		foreach ($familyMemberCount617 as $familyMemberServed) {
			$familyMembersServedSum617 += $familyMemberServed;
		}
		
		//number of people served ages 18-64
		
		//identify heads of household served ages 18-64
    	$householdQuery1864 = $em->createQuery(
			'SELECT COUNT(c.id)
			FROM AppBundle:Client c
			JOIN AppBundle:Appointment a
			WITH c.id = a.client
			WHERE a.date BETWEEN :date1 AND :date2
			AND a.status = :status
			AND c.age BETWEEN :age1 AND :age2');
		$householdQuery1864->setParameter('date1', $date1);
		$householdQuery1864->setParameter('date2', $date2);
		$householdQuery1864->setParameter('status', 'Kept Appointment');
		$householdQuery1864->setParameter('age1', '18');
		$householdQuery1864->setParameter('age2', '64');
		$householdCount1864 = $householdQuery1864->getSingleScalarResult();
		
		//count family members in age range per head of household served
		$familyMemberCount1864 = array();
		$count1864 = 0;
		
		foreach ($headOfHouseholdsServed as $headOfHousehold) {
			$familyMembersQuery1864 = $em->createQuery(
			'SELECT COUNT(f.id)
			FROM AppBundle:FamilyMember f
			WHERE f.client = :clientID
			AND f.age BETWEEN :age1 AND :age2');
			$familyMembersQuery1864->setParameter('clientID', $headOfHousehold['id']);
			$familyMembersQuery1864->setParameter('age1', '18');
			$familyMembersQuery1864->setParameter('age2', '64');
			$familyMemberCount1864[$count1864] = $familyMembersQuery1864->getSingleScalarResult();
			$count1864++;
		}
				
		$familyMembersServedSum1864 = 0;
		foreach ($familyMemberCount1864 as $familyMemberServed) {
			$familyMembersServedSum1864 += $familyMemberServed;
		}
		
		//add heads of household in age range + family members in age range
		$peopleServed1864 = $familyMembersServedSum1864 + $householdCount1864;
		
		//number of people served ages 65+
		
		//identify heads of household served age 65+
    	$householdQuery65 = $em->createQuery(
			'SELECT COUNT(c.id)
			FROM AppBundle:Client c
			JOIN AppBundle:Appointment a
			WITH c.id = a.client
			WHERE a.date BETWEEN :date1 AND :date2
			AND a.status = :status
			AND c.age BETWEEN :age1 AND :age2');
		$householdQuery65->setParameter('date1', $date1);
		$householdQuery65->setParameter('date2', $date2);
		$householdQuery65->setParameter('status', 'Kept Appointment');
		$householdQuery65->setParameter('age1', '18');
		$householdQuery65->setParameter('age2', '64');
		$householdCount65 = $householdQuery65->getSingleScalarResult();
		
		//count family members per head of household served
		$familyMemberCount65 = array();
		$count65 = 0;
		
		foreach ($headOfHouseholdsServed as $headOfHousehold) {
			$familyMembersQuery65 = $em->createQuery(
			'SELECT COUNT(f.id)
			FROM AppBundle:FamilyMember f
			WHERE f.client = :clientID
			AND f.age >= :age');
			$familyMembersQuery65->setParameter('clientID', $headOfHousehold['id']);
			$familyMembersQuery65->setParameter('age', '65');
			$familyMemberCount65[$count65] = $familyMembersQuery65->getSingleScalarResult();
			$count65++;
		}
				
		$familyMembersServedSum65 = 0;
		foreach ($familyMemberCount65 as $familyMemberServed) {
			$familyMembersServedSum65 += $familyMemberServed;
		}

		//add heads of household in age range + family members in age range
		$peopleServed65 = $familyMembersServedSum65 + $householdCount65;
		
		//new households    	
		$newHouseholdQuery = $em->createQuery(
			'SELECT COUNT(c.id)
			FROM AppBundle:Client c
			JOIN AppBundle:Appointment a
			WITH c.id = a.client
			WHERE a.date BETWEEN :date1 AND :date2
			AND a.status = :status
			AND c.enrollmentDate BETWEEN :date1 AND :date2');
		$newHouseholdQuery->setParameter('date1', $date1);
		$newHouseholdQuery->setParameter('date2', $date2);
		$newHouseholdQuery->setParameter('status', 'Kept Appointment');
		$newHouseholdCount = $newHouseholdQuery->getSingleScalarResult();
		
		//new households with children ages 0-5
		//query to identify new heads of household served
		$newHeadOfHouseholdServedQuery = $em->createQuery(
			'SELECT c.id
			FROM AppBundle:Client c
			JOIN AppBundle:Appointment a
			WITH c.id = a.client
			WHERE a.date BETWEEN :date1 AND :date2
			AND a.status = :status
			AND c.enrollmentDate BETWEEN :date1 AND :date2');
		$newHeadOfHouseholdServedQuery->setParameter('date1', $date1);
		$newHeadOfHouseholdServedQuery->setParameter('date2', $date2);
		$newHeadOfHouseholdServedQuery->setParameter('status', 'Kept Appointment');
		$newHeadsOfHouseholdServed = $newHeadOfHouseholdServedQuery->getResult();
		
		$newHouseholds05 = 0;
		
		foreach ($newHeadsOfHouseholdServed as $newHeadOfHouseholdServed) {
			$new05Query = $em->createQuery(
			'SELECT COUNT(f.id)
			FROM AppBundle:FamilyMember f
			WHERE f.client = :clientID
			AND f.age BETWEEN :age1 AND :age2');
			$new05Query->setParameter('clientID', $newHeadOfHouseholdServed['id']);
			$new05Query->setParameter('age1', '0');
			$new05Query->setParameter('age2', '5');
			$new05QueryResult = $new05Query->getSingleScalarResult();
			dump($new05QueryResult);
			if ($new05QueryResult > 0) {
				$newHouseholds05++;
			}
			
		}
		
    
        return $this->render('default/reports.html.twig', array(
        	'householdCount' => $householdCount,
        	'individualsCount' => $individualsServed,
        	'femalesCount' => $femalesServed,
        	'malesCount' => $malesServed,
        	'newHouseholdCount' => $newHouseholdCount,
        	'newHouseholdCount05' => $newHouseholds05,
        	'familyMembers05' => $familyMembersServedSum05,
        	'familyMembers617' => $familyMembersServedSum617,
        	'peopleServed1864' => $peopleServed1864,
        	'peopleServed65' => $peopleServed65,
        	'date1' => $date1,
        	'date2' => $date2,
        ));
    }
}
