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

		//number of households served
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

		//total number of individuals served	
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
		//dump($individualsCount);die;
		
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
		
		//identify heads of household served ages 18-64
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
		
		//identify heads of household served ages 18-64
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
				if ($familyMemberServed->getAge() == null and $familyMemberServed->getAge()!= 0) {
					$familyMemberNullAge[$fmAgeNullCount] = $familyMemberServed;
					$fmAgeNullCount++;
				}
			}
		}	
		
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
		  
        return $this->render('default/reports.html.twig', array(
        	'householdCount' => $householdCount,
        	'individualsCount' => $individualsServed,
        	'femalesCount' => $femalesServed,
        	'malesCount' => $malesServed,
        	'newHouseholdCount' => $newHouseholdCount,
        	'newHouseholdCount05' => $newHouseholds05,
        	'peopleServed05' => $peopleServed05,
        	'peopleServed617' => $peopleServed617,
        	'peopleServed1864' => $peopleServed1864,
        	'peopleServed65' => $peopleServed65,
        	'headOfHouseholdNullAge' => $headOfHouseholdNullAge,
        	'familyMemberNullAge' => $familyMemberNullAge,
        	'headOfHouseholdNullGender' => $headOfHouseholdNullGender,
        	'familyMemberNullGender' => $familyMemberNullGender,
        	'nullAgeCount' => $nullAgeCount,
        	'nullGenderCount' => $nullGenderCount,
        	'date1' => $date1,
        	'date2' => $date2,
        ));
    }
}
