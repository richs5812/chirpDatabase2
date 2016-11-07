<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Client;
use AppBundle\Entity\WalkIn;
use AppBundle\Entity\Appointment;
use AppBundle\Entity\FamilyMember;
use AppBundle\Entity\WalkInFamilyMember;
use AppBundle\Entity\Poundage;

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
    		//$date1='2016-05-01';
    		$date1 = date_create('first day of this month');
    		$date1 = date_format($date1,"Y-m-d");
    	}
		
		if(isset($request->query->getIterator()["formDatePicker2"])){
			$date2=date_create($request->query->getIterator()["formDatePicker2"]);
			$date2=date_format($date2,"Y-m-d");
			//dump($date1);die;
		} else {
    		//$date2='2016-05-31';
    		$date2 = date_create('last day of this month');
    		$date2 = date_format($date2,"Y-m-d");
    	}

    	//start report queries

		//unique number of households served
    	$householdQuery = $em->createQuery(
			'SELECT COUNT(DISTINCT c.id)
			FROM AppBundle:Client c
			JOIN AppBundle:Appointment a
			WITH c.id = a.client
			WHERE a.date BETWEEN :date1 AND :date2
			AND a.status = :status');
		$householdQuery->setParameter('date1', $date1);
		$householdQuery->setParameter('date2', $date2);
		$householdQuery->setParameter('status', 'Kept Appointment');
		$householdCount = $householdQuery->getSingleScalarResult();
		
		if ($householdCount == 0) {
			return $this->render('default/reportsNoResults.html.twig', array(
				'date1' => $date1,
				'date2' => $date2,
			));
        }
        
        /*
        //total number of households served
    	$total_householdQuery = $em->createQuery(
			'SELECT COUNT(c.id)
			FROM AppBundle:Client c
			JOIN AppBundle:Appointment a
			WITH c.id = a.client
			WHERE a.date BETWEEN :date1 AND :date2
			AND a.status = :status');
		$total_householdQuery->setParameter('date1', $date1);
		$total_householdQuery->setParameter('date2', $date2);
		$total_householdQuery->setParameter('status', 'Kept Appointment');
		$total_householdCount = $total_householdQuery->getSingleScalarResult();*/
		
		/*
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
		$total_headOfHouseholdsServed = $headOfHouseholdServedQuery->getResult();
		
		//count family members per head of household served
		$total_familyMembersServedCount = array();
		$i = 0;
		
		foreach ($total_headOfHouseholdsServed as $headOfHousehold) {
			$familyMembersServedQuery = $em->createQuery(
			'SELECT COUNT(f.id)
			FROM AppBundle:FamilyMember f
			JOIN AppBundle:Client c
			WITH f.client = c.id
			WHERE c.id = :clientID');
			$familyMembersServedQuery->setParameter('clientID', $headOfHousehold['id']);
			$total_familyMembersServedCount[$i] = $familyMembersServedQuery->getSingleScalarResult();
			$i++;
		}
				
		$total_familyMembersServedSum = 0;
		foreach ($total_familyMembersServedCount as $familyMemberServed) {
			$total_familyMembersServedSum += $familyMemberServed;
		}

		//add number of households served (= heads of household number)
		$total_individualsServed = $total_householdCount + $total_familyMembersServedSum;*/

		// number of unique individuals served	
		//query to identify heads of household served
		$headOfHouseholdServedQuery = $em->createQuery(
			'SELECT DISTINCT c.id
			FROM AppBundle:Client c
			JOIN AppBundle:Appointment a
			WITH c.id = a.client
			WHERE a.date BETWEEN :date1 AND :date2
			AND a.status = :status');
		$headOfHouseholdServedQuery->setParameter('date1', $date1);
		$headOfHouseholdServedQuery->setParameter('date2', $date2);
		$headOfHouseholdServedQuery->setParameter('status', 'Kept Appointment');
		$headOfHouseholdsServed = $headOfHouseholdServedQuery->getResult();
		
		//count family members per head of household served
		$familyMembersServedCount = array();
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
		/*dump($individualsServed);
		dump($householdCount);
		dump($familyMembersServedSum);*/
		
		
		//females served count
		//identify female heads of household served
    	$femaleHouseholdQuery = $em->createQuery(
			'SELECT COUNT(DISTINCT c.id)
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
			'SELECT COUNT(DISTINCT c.id)
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
		$maleFamilyMembersServedCount = array();
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
		//identify heads of household served ages 0-5
    	$householdQuery05 = $em->createQuery(
			'SELECT COUNT(DISTINCT c.id)
			FROM AppBundle:Client c
			JOIN AppBundle:Appointment a
			WITH c.id = a.client
			WHERE a.date BETWEEN :date1 AND :date2
			AND a.status = :status
			AND c.age BETWEEN :age1 AND :age2');
		$householdQuery05->setParameter('date1', $date1);
		$householdQuery05->setParameter('date2', $date2);
		$householdQuery05->setParameter('status', 'Kept Appointment');
		$householdQuery05->setParameter('age1', '0');
		$householdQuery05->setParameter('age2', '5');
		$householdCount05 = $householdQuery05->getSingleScalarResult();
		
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

		//add heads of household in age range + family members in age range
		$peopleServed05 = $familyMembersServedSum05 + $householdCount05;
		
		//number of people served ages 6-17
		//identify heads of household served ages 6-17
    	$householdQuery617 = $em->createQuery(
			'SELECT COUNT(DISTINCT c.id)
			FROM AppBundle:Client c
			JOIN AppBundle:Appointment a
			WITH c.id = a.client
			WHERE a.date BETWEEN :date1 AND :date2
			AND a.status = :status
			AND c.age BETWEEN :age1 AND :age2');
		$householdQuery617->setParameter('date1', $date1);
		$householdQuery617->setParameter('date2', $date2);
		$householdQuery617->setParameter('status', 'Kept Appointment');
		$householdQuery617->setParameter('age1', '6');
		$householdQuery617->setParameter('age2', '17');
		$householdCount617 = $householdQuery617->getSingleScalarResult();
		
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
		
		//add heads of household in age range + family members in age range
		$peopleServed617 = $familyMembersServedSum617 + $householdCount617;
		
		//number of people served ages 0-17
		//identify heads of household served ages 0-17
    	$householdQuery017 = $em->createQuery(
			'SELECT COUNT(DISTINCT c.id)
			FROM AppBundle:Client c
			JOIN AppBundle:Appointment a
			WITH c.id = a.client
			WHERE a.date BETWEEN :date1 AND :date2
			AND a.status = :status
			AND c.age BETWEEN :age1 AND :age2');
		$householdQuery017->setParameter('date1', $date1);
		$householdQuery017->setParameter('date2', $date2);
		$householdQuery017->setParameter('status', 'Kept Appointment');
		$householdQuery017->setParameter('age1', '0');
		$householdQuery017->setParameter('age2', '17');
		$householdCount017 = $householdQuery017->getSingleScalarResult();
		
		//count family members per head of household served
		$familyMemberCount017 = array();
		$count017 = 0;
		
		foreach ($headOfHouseholdsServed as $headOfHousehold) {
			$familyMembersQuery017 = $em->createQuery(
			'SELECT COUNT(f.id)
			FROM AppBundle:FamilyMember f
			WHERE f.client = :clientID
			AND f.age BETWEEN :age1 AND :age2');
			$familyMembersQuery017->setParameter('clientID', $headOfHousehold['id']);
			$familyMembersQuery017->setParameter('age1', '0');
			$familyMembersQuery017->setParameter('age2', '17');
			$familyMemberCount017[$count017] = $familyMembersQuery017->getSingleScalarResult();
			$count017++;
		}
				
		$familyMembersServedSum017 = 0;
		foreach ($familyMemberCount017 as $familyMemberServed) {
			$familyMembersServedSum017 += $familyMemberServed;
		}
		
		//add heads of household in age range + family members in age range
		$peopleServed017 = $familyMembersServedSum017 + $householdCount017;
		
		//number of people served ages 18-64
		//identify heads of household served ages 18-64
    	$householdQuery1864 = $em->createQuery(
			'SELECT COUNT(DISTINCT c.id)
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
			'SELECT COUNT(DISTINCT c.id)
			FROM AppBundle:Client c
			JOIN AppBundle:Appointment a
			WITH c.id = a.client
			WHERE a.date BETWEEN :date1 AND :date2
			AND a.status = :status
			AND c.age >= :age');
		$householdQuery65->setParameter('date1', $date1);
		$householdQuery65->setParameter('date2', $date2);
		$householdQuery65->setParameter('status', 'Kept Appointment');
		$householdQuery65->setParameter('age', '65');
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
			'SELECT COUNT(DISTINCT c.id)
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
			//dump($new05QueryResult);
			if ($new05QueryResult > 0) {
				$newHouseholds05++;
			}
			
		}
		
		//list people without ages entered
		//identify heads of household served without age assigned
		$headOfHouseholdNullAgeQuery = $em->createQuery(
			'SELECT c
			FROM AppBundle:Client c
			JOIN AppBundle:Appointment a
			WITH c.id = a.client
			WHERE a.date BETWEEN :date1 AND :date2
			AND a.status = :status
			AND c.age IS NULL
			ORDER BY c.lastName');
		$headOfHouseholdNullAgeQuery->setParameter('date1', $date1);
		$headOfHouseholdNullAgeQuery->setParameter('date2', $date2);
		$headOfHouseholdNullAgeQuery->setParameter('status', 'Kept Appointment');
		$headOfHouseholdNullAge = $headOfHouseholdNullAgeQuery->getResult();
		
		$familyMemberNullAge = array();
		$fmAgeNullCount = 0;
		
		//find family members with no age entered
		foreach ($headOfHouseholdsServed as $headOfHousehold) {
			$familyMembersServedQuery = $em->createQuery(
			'SELECT f
			FROM AppBundle:FamilyMember f
			JOIN AppBundle:Client c
			WITH f.client = c.id
			WHERE c.id = :clientID');
			$familyMembersServedQuery->setParameter('clientID', $headOfHousehold['id']);
			$familyMembersServedResult = $familyMembersServedQuery->getResult();
						
			foreach ($familyMembersServedResult as $familyMemberServed) {
				if ($familyMemberServed->getAge() == null) {
					$familyMemberNullAge[$fmAgeNullCount] = $familyMemberServed;
					$fmAgeNullCount++;
				}
			}
		}
		
		//dump($familyMemberNullAge);	
		
		//get count of people served with null age
    	$householdQueryNullCount = $em->createQuery(
			'SELECT COUNT(DISTINCT c.id)
			FROM AppBundle:Client c
			JOIN AppBundle:Appointment a
			WITH c.id = a.client
			WHERE a.date BETWEEN :date1 AND :date2
			AND a.status = :status
			AND c.age IS NULL');
		$householdQueryNullCount->setParameter('date1', $date1);
		$householdQueryNullCount->setParameter('date2', $date2);
		$householdQueryNullCount->setParameter('status', 'Kept Appointment');
		$householdQueryNullCountResult = $householdQueryNullCount->getSingleScalarResult();
		//dump($householdQueryNullCountResult);
		
		//count family members per head of household served with null age
		$familyMemberCountNull = array();
		$countFamilyMemberAgeNull = 0;
		
		foreach ($headOfHouseholdsServed as $headOfHousehold) {
			$familyMembersQueryNull = $em->createQuery(
			'SELECT COUNT(f.id)
			FROM AppBundle:FamilyMember f
			WHERE f.client = :clientID
			AND f.age IS NULL');
			$familyMembersQueryNull->setParameter('clientID', $headOfHousehold['id']);
			$familyMemberCountNull[$countFamilyMemberAgeNull] = $familyMembersQueryNull->getSingleScalarResult();
			$countFamilyMemberAgeNull++;
		}
		
		$familyMembersServedSumNULL = 0;
		foreach ($familyMemberCountNull as $familyMemberServed) {
			$familyMembersServedSumNULL += $familyMemberServed;
		}
				
		$nullAgeCount = $familyMembersServedSumNULL + $householdQueryNullCountResult;
		
		//get count of people served with null gender
		$householdQueryNullGenderCount = $em->createQuery(
			'SELECT COUNT(DISTINCT c.id)
			FROM AppBundle:Client c
			JOIN AppBundle:Appointment a
			WITH c.id = a.client
			WHERE a.date BETWEEN :date1 AND :date2
			AND a.status = :status
			AND c.gender IS NULL');
		$householdQueryNullGenderCount->setParameter('date1', $date1);
		$householdQueryNullGenderCount->setParameter('date2', $date2);
		$householdQueryNullGenderCount->setParameter('status', 'Kept Appointment');
		$householdQueryNullGenderCountResult = $householdQueryNullGenderCount->getSingleScalarResult();
		//dump($householdQueryNullGenderCountResult);
		
		//count family members per head of household served with null gender
		$familyMemberGenderCountNull = array();
		$countFamilyMemberGenderNull = 0;
		
		foreach ($headOfHouseholdsServed as $headOfHousehold) {
			$familyMembersQueryGenderNull = $em->createQuery(
			'SELECT COUNT(f.id)
			FROM AppBundle:FamilyMember f
			WHERE f.client = :clientID
			AND f.gender IS NULL');
			$familyMembersQueryGenderNull->setParameter('clientID', $headOfHousehold['id']);
			$familyMemberGenderCountNull[$countFamilyMemberGenderNull] = $familyMembersQueryGenderNull->getSingleScalarResult();
			$countFamilyMemberGenderNull++;
		}
		
		$familyMembersServedSumGenderNULL = 0;
		foreach ($familyMemberGenderCountNull as $familyMemberServed) {
			$familyMembersServedSumGenderNULL += $familyMemberServed;
		}
				
		$nullGenderCount = $familyMembersServedSumGenderNULL + $householdQueryNullGenderCountResult;
		//dump($nullGenderCount);
		//dump($familyMembersServedSumGenderNULL);
		
		//list people without gender entered
		//identify heads of household served without age assigned
		$headOfHouseholdNullGenderQuery = $em->createQuery(
			'SELECT c
			FROM AppBundle:Client c
			JOIN AppBundle:Appointment a
			WITH c.id = a.client
			WHERE a.date BETWEEN :date1 AND :date2
			AND a.status = :status
			AND c.gender IS NULL
			ORDER BY c.lastName');
		$headOfHouseholdNullGenderQuery->setParameter('date1', $date1);
		$headOfHouseholdNullGenderQuery->setParameter('date2', $date2);
		$headOfHouseholdNullGenderQuery->setParameter('status', 'Kept Appointment');
		$headOfHouseholdNullGender = $headOfHouseholdNullGenderQuery->getResult();
		
		$familyMemberNullGender = array();
		$fmGenderNullCount = 0;
		
		//find family members with no gender entered
		foreach ($headOfHouseholdsServed as $headOfHousehold) {
			$familyMembersServedNullGenderQuery = $em->createQuery(
			'SELECT f
			FROM AppBundle:FamilyMember f
			JOIN AppBundle:Client c
			WITH f.client = c.id
			WHERE c.id = :clientID');
			$familyMembersServedNullGenderQuery->setParameter('clientID', $headOfHousehold['id']);
			$familyMembersServedNullGenderResult = $familyMembersServedNullGenderQuery->getResult();
						
			foreach ($familyMembersServedNullGenderResult as $familyMemberServed) {
				if ($familyMemberServed->getGender() == null) {
					$familyMemberNullGender[$fmGenderNullCount] = $familyMemberServed;
					$fmGenderNullCount++;
				}
			}
		}
		
		//number of pregnant women served
    	$pregnantQuery = $em->createQuery(
			'SELECT COUNT(DISTINCT c.id)
			FROM AppBundle:Client c
			JOIN AppBundle:Appointment a
			WITH c.id = a.client
			WHERE a.date BETWEEN :date1 AND :date2
			AND a.status = :status
			AND c.isPregnant = :pregnant');
		$pregnantQuery->setParameter('date1', $date1);
		$pregnantQuery->setParameter('date2', $date2);
		$pregnantQuery->setParameter('status', 'Kept Appointment');
		$pregnantQuery->setParameter('pregnant', '1');
		$pregnantCount = $pregnantQuery->getSingleScalarResult();

//     	start walk-in report queries (based on walk-in checkbox)//////////////////////////////////////
// 
// 		unique number of households served
//     	$householdQuery = $em->createQuery(
// 			'SELECT COUNT(DISTINCT c.id)
// 			FROM AppBundle:Client c
// 			JOIN AppBundle:Appointment a
// 			WITH c.id = a.client
// 			WHERE a.date BETWEEN :date1 AND :date2
// 			AND a.status = :status
// 			AND c.walkIn = :walkIn');
// 		$householdQuery->setParameter('date1', $date1);
// 		$householdQuery->setParameter('date2', $date2);
// 		$householdQuery->setParameter('status', 'Kept Appointment');
// 		$householdQuery->setParameter('walkIn', '1');
// 		$walkInHouseholdCount = $householdQuery->getSingleScalarResult();
// 
// 		number of unique individuals served	
// 		query to identify heads of household served
// 		$headOfHouseholdServedQuery = $em->createQuery(
// 			'SELECT DISTINCT c.id
// 			FROM AppBundle:Client c
// 			JOIN AppBundle:Appointment a
// 			WITH c.id = a.client
// 			WHERE a.date BETWEEN :date1 AND :date2
// 			AND a.status = :status
// 			AND c.walkIn = :walkIn');
// 		$headOfHouseholdServedQuery->setParameter('date1', $date1);
// 		$headOfHouseholdServedQuery->setParameter('date2', $date2);
// 		$headOfHouseholdServedQuery->setParameter('status', 'Kept Appointment');
// 		$headOfHouseholdServedQuery->setParameter('walkIn', '1');
// 		$walkInHeadOfHouseholdsServed = $headOfHouseholdServedQuery->getResult();
// 		
// 		count family members per head of household served
// 		$walkInFamilyMembersServedCount = array();
// 		$i = 0;
// 		
// 		foreach ($walkInHeadOfHouseholdsServed as $headOfHousehold) {
// 			$familyMembersServedQuery = $em->createQuery(
// 			'SELECT COUNT(f.id)
// 			FROM AppBundle:FamilyMember f
// 			JOIN AppBundle:Client c
// 			WITH f.client = c.id
// 			WHERE c.id = :clientID');
// 			$familyMembersServedQuery->setParameter('clientID', $headOfHousehold['id']);
// 			$walkInFamilyMembersServedCount[$i] = $familyMembersServedQuery->getSingleScalarResult();
// 			$i++;
// 		}
// 				
// 		$walkInFamilyMembersServedSum = 0;
// 		foreach ($walkInFamilyMembersServedCount as $familyMemberServed) {
// 			$walkInFamilyMembersServedSum += $familyMemberServed;
// 		}
// 
// 		add number of households served (= heads of household number)
// 		$walkInIndividualsServed = $walkInHouseholdCount + $walkInFamilyMembersServedSum;		
// 		
// 		females served count
// 		identify female heads of household served
//     	$femaleHouseholdQuery = $em->createQuery(
// 			'SELECT COUNT(DISTINCT c.id)
// 			FROM AppBundle:Client c
// 			JOIN AppBundle:Appointment a
// 			WITH c.id = a.client
// 			WHERE a.date BETWEEN :date1 AND :date2
// 			AND a.status = :status
// 			AND c.gender = :gender
// 			AND c.walkIn = :walkIn');
// 		$femaleHouseholdQuery->setParameter('date1', $date1);
// 		$femaleHouseholdQuery->setParameter('date2', $date2);
// 		$femaleHouseholdQuery->setParameter('status', 'Kept Appointment');
// 		$femaleHouseholdQuery->setParameter('gender', 'F');
// 		$femaleHouseholdQuery->setParameter('walkIn', '1');
// 		$walkInFemaleHouseholdCount = $femaleHouseholdQuery->getSingleScalarResult();		
// 		
// 		familyMembers
// 		$walkInFemaleFamilyMembersServedCount = array();
// 		$k = 0;
// 
// 		foreach ($walkInHeadOfHouseholdsServed as $headOfHousehold) {
// 			$femaleFamilyMembersServedQuery = $em->createQuery(
// 			'SELECT COUNT(f.id)
// 			FROM AppBundle:FamilyMember f
// 			WHERE f.client = :clientID
// 			AND f.gender = :gender');
// 			$femaleFamilyMembersServedQuery->setParameter('clientID', $headOfHousehold['id']);
// 			$femaleFamilyMembersServedQuery->setParameter('gender', 'F');
// 			$walkInFemaleFamilyMembersServedCount[$k] = $femaleFamilyMembersServedQuery->getSingleScalarResult();
// 			$k++;
// 		}
// 				
// 		$walkInFemaleFamilyMembersServedSum = 0;
// 		foreach ($walkInFemaleFamilyMembersServedCount as $femaleFamilyMemberServed) {
// 			$walkInFemaleFamilyMembersServedSum += $femaleFamilyMemberServed;
// 		}
// 		
// 		add number of households served (= heads of household number)
// 		$walkInFemalesServed = $walkInFemaleHouseholdCount + $walkInFemaleFamilyMembersServedSum;
// 
// 		males served count
// 		heads of household
//     	$maleHouseholdQuery = $em->createQuery(
// 			'SELECT COUNT(DISTINCT c.id)
// 			FROM AppBundle:Client c
// 			JOIN AppBundle:Appointment a
// 			WITH c.id = a.client
// 			WHERE a.date BETWEEN :date1 AND :date2
// 			AND a.status = :status
// 			AND c.gender = :gender
// 			AND c.walkIn = :walkIn');
// 		$maleHouseholdQuery->setParameter('date1', $date1);
// 		$maleHouseholdQuery->setParameter('date2', $date2);
// 		$maleHouseholdQuery->setParameter('status', 'Kept Appointment');
// 		$maleHouseholdQuery->setParameter('gender', 'M');
// 		$maleHouseholdQuery->setParameter('walkIn', '1');
// 		$walkInMaleHouseholdCount = $maleHouseholdQuery->getSingleScalarResult();		
// 		
// 		familyMembers
// 		$walkInMaleFamilyMembersServedCount = array();
// 		$m = 0;
// 		
// 		foreach ($walkInHeadOfHouseholdsServed as $headOfHousehold) {
// 			$maleFamilyMembersServedQuery = $em->createQuery(
// 			'SELECT COUNT(f.id)
// 			FROM AppBundle:FamilyMember f
// 			WHERE f.client = :clientID
// 			AND f.gender = :gender');
// 			$maleFamilyMembersServedQuery->setParameter('clientID', $headOfHousehold['id']);
// 			$maleFamilyMembersServedQuery->setParameter('gender', 'M');
// 			$walkInMaleFamilyMembersServedCount[$m] = $maleFamilyMembersServedQuery->getSingleScalarResult();
// 			$m++;
// 		}
// 				
// 		$walkInMaleFamilyMembersServedSum = 0;
// 		foreach ($walkInMaleFamilyMembersServedCount as $maleFamilyMemberServed) {
// 			$walkInMaleFamilyMembersServedSum += $maleFamilyMemberServed;
// 		}
// 		
// 		add number of households served (= heads of household number)
// 		$walkInMalesServed = $walkInMaleHouseholdCount + $walkInMaleFamilyMembersServedSum;
// 
// 		number of people served ages 0-5
// 		identify heads of household served ages 0-5
//     	$householdQuery05 = $em->createQuery(
// 			'SELECT COUNT(DISTINCT c.id)
// 			FROM AppBundle:Client c
// 			JOIN AppBundle:Appointment a
// 			WITH c.id = a.client
// 			WHERE a.date BETWEEN :date1 AND :date2
// 			AND a.status = :status
// 			AND c.age BETWEEN :age1 AND :age2
// 			AND c.walkIn = :walkIn');
// 		$householdQuery05->setParameter('date1', $date1);
// 		$householdQuery05->setParameter('date2', $date2);
// 		$householdQuery05->setParameter('status', 'Kept Appointment');
// 		$householdQuery05->setParameter('age1', '0');
// 		$householdQuery05->setParameter('age2', '5');
// 		$householdQuery05->setParameter('walkIn', '1');
// 		$walkInHouseholdCount05 = $householdQuery05->getSingleScalarResult();
// 		
// 		count family members per head of household served
// 		$walkInFamilyMemberCount05 = array();
// 		$count05 = 0;
// 		
// 		foreach ($walkInHeadOfHouseholdsServed as $headOfHousehold) {
// 			$familyMembersQuery05 = $em->createQuery(
// 			'SELECT COUNT(f.id)
// 			FROM AppBundle:FamilyMember f
// 			WHERE f.client = :clientID
// 			AND f.age BETWEEN :age1 AND :age2');
// 			$familyMembersQuery05->setParameter('clientID', $headOfHousehold['id']);
// 			$familyMembersQuery05->setParameter('age1', '0');
// 			$familyMembersQuery05->setParameter('age2', '5');
// 			$walkInFamilyMemberCount05[$count05] = $familyMembersQuery05->getSingleScalarResult();
// 			$count05++;
// 		}
// 				
// 		$walkInFamilyMembersServedSum05 = 0;
// 		foreach ($walkInFamilyMemberCount05 as $familyMemberServed) {
// 			$walkInFamilyMembersServedSum05 += $familyMemberServed;
// 		}
// 
// 		add heads of household in age range + family members in age range
// 		$walkInPeopleServed05 = $walkInFamilyMembersServedSum05 + $walkInHouseholdCount05;
// 		
// 		number of people served ages 6-17
// 		identify heads of household served ages 6-17
//     	$householdQuery617 = $em->createQuery(
// 			'SELECT COUNT(DISTINCT c.id)
// 			FROM AppBundle:Client c
// 			JOIN AppBundle:Appointment a
// 			WITH c.id = a.client
// 			WHERE a.date BETWEEN :date1 AND :date2
// 			AND a.status = :status
// 			AND c.age BETWEEN :age1 AND :age2
// 			AND c.walkIn = :walkIn');
// 		$householdQuery617->setParameter('date1', $date1);
// 		$householdQuery617->setParameter('date2', $date2);
// 		$householdQuery617->setParameter('status', 'Kept Appointment');
// 		$householdQuery617->setParameter('age1', '6');
// 		$householdQuery617->setParameter('age2', '17');
// 		$householdQuery617->setParameter('walkIn', '1');
// 		$walkInHouseholdCount617 = $householdQuery617->getSingleScalarResult();
// 		
// 		count family members per head of household served
// 		$walkInFamilyMemberCount617 = array();
// 		$count617 = 0;
// 		
// 		foreach ($walkInHeadOfHouseholdsServed as $headOfHousehold) {
// 			$familyMembersQuery617 = $em->createQuery(
// 			'SELECT COUNT(f.id)
// 			FROM AppBundle:FamilyMember f
// 			WHERE f.client = :clientID
// 			AND f.age BETWEEN :age1 AND :age2');
// 			$familyMembersQuery617->setParameter('clientID', $headOfHousehold['id']);
// 			$familyMembersQuery617->setParameter('age1', '6');
// 			$familyMembersQuery617->setParameter('age2', '17');
// 			$walkInFamilyMemberCount617[$count617] = $familyMembersQuery617->getSingleScalarResult();
// 			$count617++;
// 		}
// 				
// 		$walkInFamilyMembersServedSum617 = 0;
// 		foreach ($walkInFamilyMemberCount617 as $familyMemberServed) {
// 			$walkInFamilyMembersServedSum617 += $familyMemberServed;
// 		}
// 
// 		add heads of household in age range + family members in age range
// 		$walkInPeopleServed617 = $walkInFamilyMembersServedSum617 + $walkInHouseholdCount617;
// 		
// 		number of people served ages 18-64
// 		identify heads of household served ages 18-64
//     	$householdQuery1864 = $em->createQuery(
// 			'SELECT COUNT(DISTINCT c.id)
// 			FROM AppBundle:Client c
// 			JOIN AppBundle:Appointment a
// 			WITH c.id = a.client
// 			WHERE a.date BETWEEN :date1 AND :date2
// 			AND a.status = :status
// 			AND c.age BETWEEN :age1 AND :age2
// 			AND c.walkIn = :walkIn');
// 		$householdQuery1864->setParameter('date1', $date1);
// 		$householdQuery1864->setParameter('date2', $date2);
// 		$householdQuery1864->setParameter('status', 'Kept Appointment');
// 		$householdQuery1864->setParameter('age1', '18');
// 		$householdQuery1864->setParameter('age2', '64');
// 		$householdQuery1864->setParameter('walkIn', '1');
// 		$walkInHouseholdCount1864 = $householdQuery1864->getSingleScalarResult();
// 		
// 		count family members per head of household served
// 		$walkInFamilyMemberCount1864 = array();
// 		$count1864 = 0;
// 		
// 		foreach ($walkInHeadOfHouseholdsServed as $headOfHousehold) {
// 			$familyMembersQuery1864 = $em->createQuery(
// 			'SELECT COUNT(f.id)
// 			FROM AppBundle:FamilyMember f
// 			WHERE f.client = :clientID
// 			AND f.age BETWEEN :age1 AND :age2');
// 			$familyMembersQuery1864->setParameter('clientID', $headOfHousehold['id']);
// 			$familyMembersQuery1864->setParameter('age1', '18');
// 			$familyMembersQuery1864->setParameter('age2', '64');
// 			$walkInFamilyMemberCount1864[$count1864] = $familyMembersQuery1864->getSingleScalarResult();
// 			$count1864++;
// 		}
// 				
// 		$walkInFamilyMembersServedSum1864 = 0;
// 		foreach ($walkInFamilyMemberCount1864 as $familyMemberServed) {
// 			$walkInFamilyMembersServedSum1864 += $familyMemberServed;
// 		}
// 
// 		add heads of household in age range + family members in age range
// 		$walkInPeopleServed1864 = $walkInFamilyMembersServedSum1864 + $walkInHouseholdCount1864;
// 		
// 		
// 		number of people served ages 65+
// 		
// 		identify heads of household served age 65+
//     	$householdQuery65 = $em->createQuery(
// 			'SELECT COUNT(DISTINCT c.id)
// 			FROM AppBundle:Client c
// 			JOIN AppBundle:Appointment a
// 			WITH c.id = a.client
// 			WHERE a.date BETWEEN :date1 AND :date2
// 			AND a.status = :status
// 			AND c.age >= :age
// 			AND c.walkIn = :walkIn');
// 		$householdQuery65->setParameter('date1', $date1);
// 		$householdQuery65->setParameter('date2', $date2);
// 		$householdQuery65->setParameter('status', 'Kept Appointment');
// 		$householdQuery65->setParameter('age', '65');
// 		$householdQuery65->setParameter('walkIn', '1');
// 		$walkInHouseholdCount65 = $householdQuery65->getSingleScalarResult();
// 		
// 		count family members per head of household served
// 		$walkInFamilyMemberCount65 = array();
// 		$count65 = 0;
// 		
// 		foreach ($walkInHeadOfHouseholdsServed as $headOfHousehold) {
// 			$familyMembersQuery65 = $em->createQuery(
// 			'SELECT COUNT(f.id)
// 			FROM AppBundle:FamilyMember f
// 			WHERE f.client = :clientID
// 			AND f.age >= :age');
// 			$familyMembersQuery65->setParameter('clientID', $headOfHousehold['id']);
// 			$familyMembersQuery65->setParameter('age', '65');
// 			$walkInFamilyMemberCount65[$count65] = $familyMembersQuery65->getSingleScalarResult();
// 			$count65++;
// 		}
// 				
// 		$walkInFamilyMembersServedSum65 = 0;
// 		foreach ($walkInFamilyMemberCount65 as $familyMemberServed) {
// 			$walkInFamilyMembersServedSum65 += $familyMemberServed;
// 		}
// 
// 		add heads of household in age range + family members in age range
// 		$walkInPeopleServed65 = $walkInFamilyMembersServedSum65 + $walkInHouseholdCount65;
// 		
// 		new households    	
// 		$newHouseholdQuery = $em->createQuery(
// 			'SELECT COUNT(DISTINCT c.id)
// 			FROM AppBundle:Client c
// 			JOIN AppBundle:Appointment a
// 			WITH c.id = a.client
// 			WHERE a.date BETWEEN :date1 AND :date2
// 			AND a.status = :status
// 			AND c.enrollmentDate BETWEEN :date1 AND :date2
// 			AND c.walkIn = :walkIn');
// 		$newHouseholdQuery->setParameter('date1', $date1);
// 		$newHouseholdQuery->setParameter('date2', $date2);
// 		$newHouseholdQuery->setParameter('status', 'Kept Appointment');
// 		$newHouseholdQuery->setParameter('walkIn', '1');
// 		$walkInNewHouseholdCount = $newHouseholdQuery->getSingleScalarResult();
// 		
// 		new households with children ages 0-5
// 		query to identify new heads of household served
// 		$newHeadOfHouseholdServedQuery = $em->createQuery(
// 			'SELECT c.id
// 			FROM AppBundle:Client c
// 			JOIN AppBundle:Appointment a
// 			WITH c.id = a.client
// 			WHERE a.date BETWEEN :date1 AND :date2
// 			AND a.status = :status
// 			AND c.enrollmentDate BETWEEN :date1 AND :date2
// 			AND c.walkIn = :walkIn');
// 		$newHeadOfHouseholdServedQuery->setParameter('date1', $date1);
// 		$newHeadOfHouseholdServedQuery->setParameter('date2', $date2);
// 		$newHeadOfHouseholdServedQuery->setParameter('status', 'Kept Appointment');
// 		$newHeadOfHouseholdServedQuery->setParameter('walkIn', '1');
// 		$walkInNewHeadsOfHouseholdServed = $newHeadOfHouseholdServedQuery->getResult();
// 		
// 		$walkInNewHouseholds05 = 0;
// 		
// 		foreach ($walkInNewHeadsOfHouseholdServed as $newHeadOfHouseholdServed) {
// 			$new05Query = $em->createQuery(
// 			'SELECT COUNT(f.id)
// 			FROM AppBundle:FamilyMember f
// 			WHERE f.client = :clientID
// 			AND f.age BETWEEN :age1 AND :age2');
// 			$new05Query->setParameter('clientID', $newHeadOfHouseholdServed['id']);
// 			$new05Query->setParameter('age1', '0');
// 			$new05Query->setParameter('age2', '5');
// 			$walkInNew05QueryResult = $new05Query->getSingleScalarResult();
// 			dump($new05QueryResult);
// 			if ($walkInNew05QueryResult > 0) {
// 				$walkInNewHouseholds05++;
// 			}
// 		}
// 		
// 		get count of people served with null age
//     	$walkInHouseholdQueryNullCount = $em->createQuery(
// 			'SELECT COUNT(DISTINCT c.id)
// 			FROM AppBundle:Client c
// 			JOIN AppBundle:Appointment a
// 			WITH c.id = a.client
// 			WHERE a.date BETWEEN :date1 AND :date2
// 			AND a.status = :status
// 			AND c.age IS NULL
// 			AND c.walkIn = :walkIn');
// 		$walkInHouseholdQueryNullCount->setParameter('date1', $date1);
// 		$walkInHouseholdQueryNullCount->setParameter('date2', $date2);
// 		$walkInHouseholdQueryNullCount->setParameter('status', 'Kept Appointment');
// 		$walkInHouseholdQueryNullCount->setParameter('walkIn', '1');
// 		$walkInHouseholdQueryNullCountResult = $walkInHouseholdQueryNullCount->getSingleScalarResult();
// 		
// 		count family members per head of household served with null age
// 		$walkInFamilyMemberCountNull = array();
// 		$countFamilyMemberAgeNull = 0;
// 		
// 		foreach ($walkInHeadOfHouseholdsServed as $headOfHousehold) {
// 			$familyMembersQueryNull = $em->createQuery(
// 			'SELECT COUNT(f.id)
// 			FROM AppBundle:FamilyMember f
// 			WHERE f.client = :clientID
// 			AND f.age IS NULL');
// 			$familyMembersQueryNull->setParameter('clientID', $headOfHousehold['id']);
// 			$walkInFamilyMemberCountNull[$countFamilyMemberAgeNull] = $familyMembersQueryNull->getSingleScalarResult();
// 			$countFamilyMemberAgeNull++;
// 		}
// 		
// 		$walkInFamilyMembersServedSumNULL = 0;
// 		foreach ($walkInFamilyMemberCountNull as $familyMemberServed) {
// 			$walkInFamilyMembersServedSumNULL += $familyMemberServed;
// 		}
// 				
// 		$walkInNullAgeCount = $walkInFamilyMembersServedSumNULL + $walkInHouseholdQueryNullCountResult;
// 		
// 		get count of people served with null gender
// 		$walkInHouseholdQueryNullGenderCount = $em->createQuery(
// 			'SELECT COUNT(DISTINCT c.id)
// 			FROM AppBundle:Client c
// 			JOIN AppBundle:Appointment a
// 			WITH c.id = a.client
// 			WHERE a.date BETWEEN :date1 AND :date2
// 			AND a.status = :status
// 			AND c.gender IS NULL
// 			AND c.walkIn = :walkIn');
// 		$walkInHouseholdQueryNullGenderCount->setParameter('date1', $date1);
// 		$walkInHouseholdQueryNullGenderCount->setParameter('date2', $date2);
// 		$walkInHouseholdQueryNullGenderCount->setParameter('status', 'Kept Appointment');
// 		$walkInHouseholdQueryNullGenderCount->setParameter('walkIn', '1');
// 		$walkInHouseholdQueryNullGenderCountResult = $walkInHouseholdQueryNullGenderCount->getSingleScalarResult();
// 		
// 		count family members per head of household served with null gender
// 		$walkInFamilyMemberGenderCountNull = array();
// 		$countFamilyMemberGenderNull = 0;
// 		
// 		foreach ($walkInHeadOfHouseholdsServed as $headOfHousehold) {
// 			$familyMembersQueryGenderNull = $em->createQuery(
// 			'SELECT COUNT(f.id)
// 			FROM AppBundle:FamilyMember f
// 			WHERE f.client = :clientID
// 			AND f.gender IS NULL');
// 			$familyMembersQueryGenderNull->setParameter('clientID', $headOfHousehold['id']);
// 			$walkInFamilyMemberGenderCountNull[$countFamilyMemberGenderNull] = $familyMembersQueryGenderNull->getSingleScalarResult();
// 			$countFamilyMemberGenderNull++;
// 		}
// 		
// 		$walkInFamilyMembersServedSumGenderNULL = 0;
// 		foreach ($walkInFamilyMemberGenderCountNull as $familyMemberServed) {
// 			$walkInFamilyMembersServedSumGenderNULL += $familyMemberServed;
// 		}
// 				
// 		$walkInNullGenderCount = $walkInFamilyMembersServedSumGenderNULL + $walkInHouseholdQueryNullGenderCountResult;
// 		
// 		number of pregnant women served
//     	$pregnantQuery = $em->createQuery(
// 			'SELECT COUNT(DISTINCT c.id)
// 			FROM AppBundle:Client c
// 			JOIN AppBundle:Appointment a
// 			WITH c.id = a.client
// 			WHERE a.date BETWEEN :date1 AND :date2
// 			AND a.status = :status
// 			AND c.isPregnant = :pregnant
// 			AND c.walkIn = :walkIn');
// 		$pregnantQuery->setParameter('date1', $date1);
// 		$pregnantQuery->setParameter('date2', $date2);
// 		$pregnantQuery->setParameter('status', 'Kept Appointment');
// 		$pregnantQuery->setParameter('pregnant', '1');
// 		$pregnantQuery->setParameter('walkIn', '1');
// 		$walkInPregnantCount = $pregnantQuery->getSingleScalarResult();

//		start walk-in queries 

		$walkInHouseholdQuery = $em->createQuery(
			'SELECT COUNT(DISTINCT w.id)
			FROM AppBundle:WalkIn w
			WHERE w.date BETWEEN :date1 AND :date2'
		);
		$walkInHouseholdQuery->setParameter('date1', $date1);
		$walkInHouseholdQuery->setParameter('date2', $date2);
		$walkInHouseholdCount = $walkInHouseholdQuery->getSingleScalarResult();
				
		$walkInFamilyMembersServedQuery = $em->createQuery(
		'SELECT COUNT(f.id)
		FROM AppBundle:WalkInFamilyMember f
		JOIN AppBundle:WalkIn w
		WITH f.walkIn = w.id
		AND w.date BETWEEN :date1 AND :date2');
		$walkInFamilyMembersServedQuery->setParameter('date1', $date1);
		$walkInFamilyMembersServedQuery->setParameter('date2', $date2);
		$walkInFamilyMembersCount = $walkInFamilyMembersServedQuery->getSingleScalarResult();
		
// 		add number of households served (= heads of household number)
		$walkInIndividualsServed = $walkInHouseholdCount + $walkInFamilyMembersCount;		

// 		walk-in female queries
		$walkInFemaleClientQuery = $em->createQuery(
			'SELECT COUNT(DISTINCT w.id)
			FROM AppBundle:WalkIn w
			WHERE w.date BETWEEN :date1 AND :date2
			AND w.gender = :gender'
		);
		$walkInFemaleClientQuery->setParameter('date1', $date1);
		$walkInFemaleClientQuery->setParameter('date2', $date2);
		$walkInFemaleClientQuery->setParameter('gender', 'F');
		$walkInFemaleClientCount = $walkInFemaleClientQuery->getSingleScalarResult();
						
		$walkInFemaleFamilyMembersQuery = $em->createQuery(
		'SELECT COUNT(f.id)
		FROM AppBundle:WalkInFamilyMember f
		JOIN AppBundle:WalkIn w
		WITH f.walkIn = w.id
		AND f.gender = :gender
		AND w.date BETWEEN :date1 AND :date2');
		$walkInFemaleFamilyMembersQuery->setParameter('date1', $date1);
		$walkInFemaleFamilyMembersQuery->setParameter('date2', $date2);
		$walkInFemaleFamilyMembersQuery->setParameter('gender', 'F');
		$walkInFemaleFamilyMembersCount = $walkInFemaleFamilyMembersQuery->getSingleScalarResult();
		
// 		add number of households served (= heads of household number)
		$walkInFemalesServed = $walkInFemaleClientCount + $walkInFemaleFamilyMembersCount;		

// 		walk-in male queries
		$walkInMaleClientQuery = $em->createQuery(
			'SELECT COUNT(DISTINCT w.id)
			FROM AppBundle:WalkIn w
			WHERE w.date BETWEEN :date1 AND :date2
			AND w.gender = :gender'
		);
		$walkInMaleClientQuery->setParameter('date1', $date1);
		$walkInMaleClientQuery->setParameter('date2', $date2);
		$walkInMaleClientQuery->setParameter('gender', 'M');
		$walkInMaleClientCount = $walkInMaleClientQuery->getSingleScalarResult();
						
		$walkInMaleFamilyMembersQuery = $em->createQuery(
		'SELECT COUNT(f.id)
		FROM AppBundle:WalkInFamilyMember f
		JOIN AppBundle:WalkIn w
		WITH f.walkIn = w.id
		AND f.gender = :gender
		AND w.date BETWEEN :date1 AND :date2');
		$walkInMaleFamilyMembersQuery->setParameter('date1', $date1);
		$walkInMaleFamilyMembersQuery->setParameter('date2', $date2);
		$walkInMaleFamilyMembersQuery->setParameter('gender', 'M');
		$walkInMaleFamilyMembersCount = $walkInMaleFamilyMembersQuery->getSingleScalarResult();
		
// 		add number of households served (= heads of household number)
		$walkInMalesServed = $walkInMaleClientCount + $walkInMaleFamilyMembersCount;		

// 		walk-in null gender queries
		$walkInNullGenderClientQuery = $em->createQuery(
			'SELECT COUNT(DISTINCT w.id)
			FROM AppBundle:WalkIn w
			WHERE w.date BETWEEN :date1 AND :date2
			AND w.gender IS NULL'
		);
		$walkInNullGenderClientQuery->setParameter('date1', $date1);
		$walkInNullGenderClientQuery->setParameter('date2', $date2);
		$walkInNullGenderClientCount = $walkInNullGenderClientQuery->getSingleScalarResult();
						
		$walkInNullGenderFamilyMembersQuery = $em->createQuery(
		'SELECT COUNT(f.id)
		FROM AppBundle:WalkInFamilyMember f
		JOIN AppBundle:WalkIn w
		WITH f.walkIn = w.id
		AND f.gender IS NULL
		AND w.date BETWEEN :date1 AND :date2');
		$walkInNullGenderFamilyMembersQuery->setParameter('date1', $date1);
		$walkInNullGenderFamilyMembersQuery->setParameter('date2', $date2);
		$walkInNullGenderFamilyMembersCount = $walkInNullGenderFamilyMembersQuery->getSingleScalarResult();
		
// 		add number of households served (= heads of household number)
		$walkInNullGenderCount = $walkInNullGenderClientCount + $walkInNullGenderFamilyMembersCount;

// 		walk-in family members age 0-5
		$walkIn05ClientQuery = $em->createQuery(
			'SELECT COUNT(DISTINCT w.id)
			FROM AppBundle:WalkIn w
			WHERE w.date BETWEEN :date1 AND :date2
			AND w.age BETWEEN :age1 AND :age2'
		);
		$walkIn05ClientQuery->setParameter('date1', $date1);
		$walkIn05ClientQuery->setParameter('date2', $date2);
		$walkIn05ClientQuery->setParameter('age1', '0');
		$walkIn05ClientQuery->setParameter('age2', '5');
		$walkIn05ClientCount = $walkIn05ClientQuery->getSingleScalarResult();

		$walkIn05FamilyMembersQuery = $em->createQuery(
		'SELECT COUNT(f.id)
		FROM AppBundle:WalkInFamilyMember f
		JOIN AppBundle:WalkIn w
		WITH f.walkIn = w.id
		AND f.age BETWEEN :age1 AND :age2
		AND w.date BETWEEN :date1 AND :date2');
		$walkIn05FamilyMembersQuery->setParameter('date1', $date1);
		$walkIn05FamilyMembersQuery->setParameter('date2', $date2);
		$walkIn05FamilyMembersQuery->setParameter('age1', '0');
		$walkIn05FamilyMembersQuery->setParameter('age2', '5');
		$walkIn05FamilyMembersCount = $walkIn05FamilyMembersQuery->getSingleScalarResult();
		
// 		add number of households served (= heads of household number)
		$walkInPeopleServed05 = $walkIn05ClientCount + $walkIn05FamilyMembersCount;

// 		walk-in family members age 6-17
		$walkIn617ClientQuery = $em->createQuery(
			'SELECT COUNT(DISTINCT w.id)
			FROM AppBundle:WalkIn w
			WHERE w.date BETWEEN :date1 AND :date2
			AND w.age BETWEEN :age1 AND :age2'
		);
		$walkIn617ClientQuery->setParameter('date1', $date1);
		$walkIn617ClientQuery->setParameter('date2', $date2);
		$walkIn617ClientQuery->setParameter('age1', '6');
		$walkIn617ClientQuery->setParameter('age2', '17');
		$walkIn617ClientCount = $walkIn617ClientQuery->getSingleScalarResult();

		$walkIn617FamilyMembersQuery = $em->createQuery(
		'SELECT COUNT(f.id)
		FROM AppBundle:WalkInFamilyMember f
		JOIN AppBundle:WalkIn w
		WITH f.walkIn = w.id
		AND f.age BETWEEN :age1 AND :age2
		AND w.date BETWEEN :date1 AND :date2');
		$walkIn617FamilyMembersQuery->setParameter('date1', $date1);
		$walkIn617FamilyMembersQuery->setParameter('date2', $date2);
		$walkIn617FamilyMembersQuery->setParameter('age1', '6');
		$walkIn617FamilyMembersQuery->setParameter('age2', '17');
		$walkIn617FamilyMembersCount = $walkIn617FamilyMembersQuery->getSingleScalarResult();
		
// 		add number of households served (= heads of household number)
		$walkInPeopleServed617 = $walkIn617ClientCount + $walkIn617FamilyMembersCount;

// 		walk-in family members age 18-64
		$walkIn1864ClientQuery = $em->createQuery(
			'SELECT COUNT(DISTINCT w.id)
			FROM AppBundle:WalkIn w
			WHERE w.date BETWEEN :date1 AND :date2
			AND w.age BETWEEN :age1 AND :age2'
		);
		$walkIn1864ClientQuery->setParameter('date1', $date1);
		$walkIn1864ClientQuery->setParameter('date2', $date2);
		$walkIn1864ClientQuery->setParameter('age1', '18');
		$walkIn1864ClientQuery->setParameter('age2', '64');
		$walkIn1864ClientCount = $walkIn1864ClientQuery->getSingleScalarResult();

		$walkIn1864FamilyMembersQuery = $em->createQuery(
		'SELECT COUNT(f.id)
		FROM AppBundle:WalkInFamilyMember f
		JOIN AppBundle:WalkIn w
		WITH f.walkIn = w.id
		AND f.age BETWEEN :age1 AND :age2
		AND w.date BETWEEN :date1 AND :date2');
		$walkIn1864FamilyMembersQuery->setParameter('date1', $date1);
		$walkIn1864FamilyMembersQuery->setParameter('date2', $date2);
		$walkIn1864FamilyMembersQuery->setParameter('age1', '18');
		$walkIn1864FamilyMembersQuery->setParameter('age2', '64');
		$walkIn1864FamilyMembersCount = $walkIn1864FamilyMembersQuery->getSingleScalarResult();
		
// 		add number of households served (= heads of household number)
		$walkInPeopleServed1864 = $walkIn1864ClientCount + $walkIn1864FamilyMembersCount;

// 		walk-in family members age 65+
		$walkIn65ClientQuery = $em->createQuery(
			'SELECT COUNT(DISTINCT w.id)
			FROM AppBundle:WalkIn w
			WHERE w.date BETWEEN :date1 AND :date2
			AND w.age >= :age'
		);
		$walkIn65ClientQuery->setParameter('date1', $date1);
		$walkIn65ClientQuery->setParameter('date2', $date2);
		$walkIn65ClientQuery->setParameter('age', '65');
		$walkIn65ClientCount = $walkIn65ClientQuery->getSingleScalarResult();

		$walkIn65FamilyMembersQuery = $em->createQuery(
		'SELECT COUNT(f.id)
		FROM AppBundle:WalkInFamilyMember f
		JOIN AppBundle:WalkIn w
		WITH f.walkIn = w.id
		AND f.age >= :age
		AND w.date BETWEEN :date1 AND :date2');
		$walkIn65FamilyMembersQuery->setParameter('date1', $date1);
		$walkIn65FamilyMembersQuery->setParameter('date2', $date2);
		$walkIn65FamilyMembersQuery->setParameter('age', '65');
		$walkIn65FamilyMembersCount = $walkIn65FamilyMembersQuery->getSingleScalarResult();
		
// 		add number of households served (= heads of household number)
		$walkInPeopleServed65 = $walkIn65ClientCount + $walkIn65FamilyMembersCount;

// 		walk-in family members age is null
		$walkInNullAgeClientQuery = $em->createQuery(
			'SELECT COUNT(DISTINCT w.id)
			FROM AppBundle:WalkIn w
			WHERE w.date BETWEEN :date1 AND :date2
			AND w.age IS NULL'
		);
		$walkInNullAgeClientQuery->setParameter('date1', $date1);
		$walkInNullAgeClientQuery->setParameter('date2', $date2);
		$walkInNullAgeClientCount = $walkInNullAgeClientQuery->getSingleScalarResult();

		$walkInNullAgeFamilyMembersQuery = $em->createQuery(
		'SELECT COUNT(f.id)
		FROM AppBundle:WalkInFamilyMember f
		JOIN AppBundle:WalkIn w
		WITH f.walkIn = w.id
		AND f.age IS NULL
		AND w.date BETWEEN :date1 AND :date2');
		$walkInNullAgeFamilyMembersQuery->setParameter('date1', $date1);
		$walkInNullAgeFamilyMembersQuery->setParameter('date2', $date2);
		$walkInNullAgeFamilyMembersCount = $walkInNullAgeFamilyMembersQuery->getSingleScalarResult();
		
// 		add number of households served (= heads of household number)
		$walkInNullAgeCount = $walkInNullAgeClientCount + $walkInNullAgeFamilyMembersCount;
		
		//list walk-in clients with null age
		$walkInNullAgeListClientQuery = $em->createQuery(
			'SELECT w
			FROM AppBundle:WalkIn w
			WHERE w.date BETWEEN :date1 AND :date2
			AND w.age IS NULL'
		);
		$walkInNullAgeListClientQuery->setParameter('date1', $date1);
		$walkInNullAgeListClientQuery->setParameter('date2', $date2);
		$walkInNullAgeClientList = $walkInNullAgeListClientQuery->getResult();
		
		//list walk-in family members with null age
		$walkInNullAgeListFamilyQuery = $em->createQuery(
			'SELECT f
			FROM AppBundle:WalkInFamilyMember f
			JOIN AppBundle:WalkIn w
			WITH f.walkIn = w.id
			AND f.age IS NULL
			AND w.date BETWEEN :date1 AND :date2');
		$walkInNullAgeListFamilyQuery->setParameter('date1', $date1);
		$walkInNullAgeListFamilyQuery->setParameter('date2', $date2);
		$walkInNullAgeFamilyList = $walkInNullAgeListFamilyQuery->getResult();
		
		//list walk-in clients with null gender
		$walkInNullGenderListClientQuery = $em->createQuery(
			'SELECT w
			FROM AppBundle:WalkIn w
			WHERE w.date BETWEEN :date1 AND :date2
			AND w.gender IS NULL'
		);
		$walkInNullGenderListClientQuery->setParameter('date1', $date1);
		$walkInNullGenderListClientQuery->setParameter('date2', $date2);
		$walkInNullGenderClientList = $walkInNullGenderListClientQuery->getResult();
		
		//list walk-in family members with null gender
		$walkInNullGenderListFamilyQuery = $em->createQuery(
			'SELECT f
			FROM AppBundle:WalkInFamilyMember f
			JOIN AppBundle:WalkIn w
			WITH f.walkIn = w.id
			AND f.gender IS NULL
			AND w.date BETWEEN :date1 AND :date2');
		$walkInNullGenderListFamilyQuery->setParameter('date1', $date1);
		$walkInNullGenderListFamilyQuery->setParameter('date2', $date2);
		$walkInNullGenderFamilyList = $walkInNullGenderListFamilyQuery->getResult();
		
		//poundage query
		$poundageQuery = $em->createQuery(
			'SELECT p
			FROM AppBundle:Poundage p
			WHERE p.date BETWEEN :date1 AND :date2
			ORDER BY p.date ASC'
		);
		$poundageQuery->setParameter('date1', $date1);
		$poundageQuery->setParameter('date2', $date2);

		$poundages = $poundageQuery->getResult();
		
		$poundagesArray = array();
		$i = 0;
		
		foreach ($poundages as $poundage) {
			$poundagesArray[$i] = $poundage->getPoundage();
			$i++;
		}
		
		$poundageSum = array_sum($poundagesArray);
		  		  
        return $this->render('default/reports.html.twig', array(
        	'householdCount' => $householdCount,
        	'walkInHouseholdCount' => $walkInHouseholdCount,
        	'individualsCount' => $individualsServed,
        	'walkInIndividualsServed' => $walkInIndividualsServed,
	       	'femalesCount' => $femalesServed,
	       	'walkInFemalesCount' => $walkInFemalesServed,
        	'malesCount' => $malesServed,
        	'walkInMalesCount' => $walkInMalesServed,
        	'newHouseholdCount' => $newHouseholdCount,
//         	'walkInNewHouseholdCount' => $walkInNewHouseholdCount,
        	'newHouseholdCount05' => $newHouseholds05,
//         	'walkInNewHouseholdCount05' => $walkInNewHouseholds05,
        	'peopleServed05' => $peopleServed05,
        	'walkInPeopleServed05' => $walkInPeopleServed05,
        	'peopleServed617' => $peopleServed617,
        	'walkInPeopleServed617' => $walkInPeopleServed617,
        	'peopleServed017' => $peopleServed017,
        	'peopleServed1864' => $peopleServed1864,
        	'walkInPeopleServed1864' => $walkInPeopleServed1864,
        	'peopleServed65' => $peopleServed65,
        	'walkInPeopleServed65' => $walkInPeopleServed65,
        	'headOfHouseholdNullAge' => $headOfHouseholdNullAge,
        	'walkInNullAge' => $walkInNullAgeClientList,
        	'walkInNullFamilyAge' => $walkInNullAgeFamilyList,
        	'walkInNullGender' => $walkInNullGenderClientList,
        	'walkInNullFamilyGender' => $walkInNullGenderFamilyList,
        	'familyMemberNullAge' => $familyMemberNullAge,
        	'headOfHouseholdNullGender' => $headOfHouseholdNullGender,
        	'familyMemberNullGender' => $familyMemberNullGender,
        	'nullAgeCount' => $nullAgeCount,
        	'walkInNullAgeCount' => $walkInNullAgeCount,
        	'nullGenderCount' => $nullGenderCount,
        	'walkInNullGenderCount' => $walkInNullGenderCount,
        	'pregnantCount' => $pregnantCount,
//         	'walkInPregnantCount' => $walkInPregnantCount,
        	'poundageSum' => $poundageSum,
        	'date1' => $date1,
        	'date2' => $date2,
        ));
    }
}
