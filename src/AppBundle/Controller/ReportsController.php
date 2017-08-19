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
		} else {
    		$date1 = date_create('first day of this month');
    	}
		
		if(isset($request->query->getIterator()["formDatePicker2"])){
			$date2=date_create($request->query->getIterator()["formDatePicker2"]);
		} else {
    		$date2 = date_create('last day of this month');
    	}

    	//start report queries

		//get households served
		$headOfHouseholdServedQuery = $em->createQuery(
			'SELECT c
			FROM AppBundle:Client c
			JOIN AppBundle:Appointment a
			WITH c.id = a.client
			WHERE a.date BETWEEN :date1 AND :date2
			AND a.status = :status');
		$headOfHouseholdServedQuery->setParameter('date1', $date1);
		$headOfHouseholdServedQuery->setParameter('date2', $date2);
		$headOfHouseholdServedQuery->setParameter('status', 'Kept Appointment');
		$headOfHouseholdsServed = $headOfHouseholdServedQuery->getResult();

		$householdCount = count($headOfHouseholdsServed);

		$walkInHouseholdQuery = $em->createQuery(
			'SELECT w
			FROM AppBundle:WalkIn w
			WHERE w.date BETWEEN :date1 AND :date2'
		);
		$walkInHouseholdQuery->setParameter('date1', $date1);
		$walkInHouseholdQuery->setParameter('date2', $date2);
		$walkInHouseholds = $walkInHouseholdQuery->getResult();
		
		$walkInHouseholdCount = count($walkInHouseholds);
		
		if ($householdCount == 0 and $walkInHouseholdCount == 0) {
			return $this->render('default/reportsNoResults.html.twig', array(
				'date1' => $date1,
				'date2' => $date2,
			));
        }
		
		$familyMemberCounter = 0;
		$femaleCounter = 0;
		$maleCounter = 0;
		$nullGenderCounter = 0;
		$headOfHouseholdNullGender = array();		
		$familyMembersNullGender = array();		
		$counter05 = 0;
		$counter617 = 0;
		$counter1864 = 0;
		$counter65 = 0;
		$nullAgeCounter = 0;
		$headOfHouseholdNullAge = array();
		$familyMembersNullAgeArray = array();
		$newHouseholdCounter = 0;
		$newHousehold05Counter = 0;
		$pregnantCounter = 0;
		foreach ($headOfHouseholdsServed as $headOfHousehold) {
		
			//total family members served count
			$familyMemberCounter += count($headOfHousehold->getFamilyMembers());

			//gender counters
			//count heads of household served by gender
			if ($headOfHousehold->getGender() == 'F') {
				$femaleCounter++;
			} else if ($headOfHousehold->getGender() == 'M') {
				$maleCounter++;
			} else if ($headOfHousehold->getGender() === null) {
				$nullGenderCounter++;
				$headOfHouseholdNullGender[] = $headOfHousehold;
			}
						
			//family members served by gender			
			$females = $headOfHousehold->getFamilyMembers()->filter(function(FamilyMember $familyMember) {
				return $familyMember->getGender() == 'F';
			});
			
			$males = $headOfHousehold->getFamilyMembers()->filter(function(FamilyMember $familyMember) {
				return $familyMember->getGender() == 'M';
			});			
			
			$nullGenders = $headOfHousehold->getFamilyMembers()->filter(function(FamilyMember $familyMember) {
				return $familyMember->getGender() === null;
			});
			
			foreach ($nullGenders as $familyMember) {
				$familyMembersNullGender[] = $familyMember;
			}	
			
			$femaleCounter += count($females);
			$maleCounter += count($males);
			$nullGenderCounter += count($nullGenders);

			//age counters
			//count heads of household served by age
			if ($headOfHousehold->getAge() === null) {
				$nullAgeCounter++;
				$headOfHouseholdNullAge[] = $headOfHousehold;
			} else if ($headOfHousehold->getAge() >= 0 and $headOfHousehold->getAge() <= 5) {
				$counter05++;
			} else if ($headOfHousehold->getAge() >= 6 and $headOfHousehold->getAge() <= 17) {
				$counter617++;
			} else if ($headOfHousehold->getAge() >= 18 and $headOfHousehold->getAge() <= 64) {
				$counter1864++;
			} else if ($headOfHousehold->getAge() >= 65) {
				$counter65++;
			}
						
			//family members count by age
			$familyMembers05 = $headOfHousehold->getFamilyMembers()->filter(function(FamilyMember $familyMember) {
				return $familyMember->getAge() >= 0 and $familyMember->getAge() <= 5 and $familyMember->getAge() !== null;
			});
			
			$familyMembers617 = $headOfHousehold->getFamilyMembers()->filter(function(FamilyMember $familyMember) {
				return $familyMember->getAge() >= 6 and $familyMember->getAge() <= 17;
			});
			
			$familyMembers1864 = $headOfHousehold->getFamilyMembers()->filter(function(FamilyMember $familyMember) {
				return $familyMember->getAge() >= 18 and $familyMember->getAge() <= 64;
			});
			
			$familyMembers65 = $headOfHousehold->getFamilyMembers()->filter(function(FamilyMember $familyMember) {
				return $familyMember->getAge() >= 65;
			});
			
			$familyMembersNullAge = $headOfHousehold->getFamilyMembers()->filter(function(FamilyMember $familyMember) {
				return $familyMember->getAge() === null;
			});
			

			
			foreach ($familyMembersNullAge as $familyMember) {
				$familyMembersNullAgeArray[] = $familyMember;
			}				
			
			$counter05 += count($familyMembers05);
			$counter617 += count($familyMembers617);
			$counter1864 += count($familyMembers1864);			
			$counter65 += count($familyMembers65);
			$nullAgeCounter += count($familyMembersNullAge);

			//new households
			if ($headOfHousehold->getEnrollmentDate() >= $date1 and $headOfHousehold->getEnrollmentDate() <= $date2) {
				$newHouseholdCounter++;
				
				if (count($familyMembers05) > 0) {
					$newHousehold05Counter++;
				}
			}
			
			//pregnant women served
			if ($headOfHousehold->getIsPregnant() == true) {
				$pregnantCounter++;
			}

		}	

//		start walk-in counters		
		$walkInFamilyMemberCounter = 0;
		$walkInFemaleCounter = 0;
		$walkInMaleCounter = 0;
		$walkInNullGenderCounter = 0;
		$walkInNullGender = array();		
		$walkInFamilyMembersNullGender = array();		
		$walkInCounter05 = 0;
		$walkInCounter617 = 0;
		$walkInCounter1864 = 0;
		$walkInCounter65 = 0;
		$walkInNullAgeCounter = 0;
		$walkInHeadOfHouseholdNullAge = array();
		$walkInFamilyMembersNullAgeArray = array();
		foreach ($walkInHouseholds as $walkIn) {
			//total family members served count
			$walkInFamilyMemberCounter += count($walkIn->getWalkInFamilyMembers());

			//gender counters
			//count heads of household served by gender
			if ($walkIn->getGender() == 'F') {
				$walkInFemaleCounter++;
			} else if ($walkIn->getGender() == 'M') {
				$walkInMaleCounter++;
			} else if ($walkIn->getGender() === null) {
				$walkInNullGenderCounter++;
				$walkInNullGender[] = $walkIn;
			}
			
			//family members served by gender			
			$females = $walkIn->getWalkInFamilyMembers()->filter(function(WalkInFamilyMember $familyMember) {
				return $familyMember->getGender() == 'F';
			});
			
			$males = $walkIn->getWalkInFamilyMembers()->filter(function(WalkInFamilyMember $familyMember) {
				return $familyMember->getGender() == 'M';
			});			
			
			$nullGenders = $walkIn->getWalkInFamilyMembers()->filter(function(WalkInFamilyMember $familyMember) {
				return $familyMember->getGender() === null;
			});
			
			
			foreach ($nullGenders as $familyMember) {
				$walkInFamilyMembersNullGender[] = $familyMember;
			}	


			$walkInFemaleCounter += count($females);
			$walkInMaleCounter += count($males);
			$walkInNullGenderCounter += count($nullGenders);
			
			//age counters
			//count heads of household served by age
			 if ($walkIn->getAge() === null) {
				$walkInNullAgeCounter++;
				$walkInHeadOfHouseholdNullAge[] = $walkIn;
			} else if ($walkIn->getAge() >= 0 and $walkIn->getAge() <= 5) {
				$walkInCounter05++;
			} else if ($walkIn->getAge() >= 6 and $walkIn->getAge() <= 17) {
				$walkInCounter617++;
			} else if ($walkIn->getAge() >= 18 and $walkIn->getAge() <= 64) {
				$walkInCounter1864++;
			} else if ($walkIn->getAge() >= 65) {
				$walkInCounter65++;
			}
												
			//family members count by age
			$familyMembers05 = $walkIn->getWalkInFamilyMembers()->filter(function(WalkInFamilyMember $familyMember) {
				return $familyMember->getAge() >= 0 and $familyMember->getAge() <= 5 and $familyMember->getAge() !== null;
			});
			
			$familyMembers617 = $walkIn->getWalkInFamilyMembers()->filter(function(WalkInFamilyMember $familyMember) {
				return $familyMember->getAge() >= 6 and $familyMember->getAge() <= 17;
			});
			
			$familyMembers1864 = $walkIn->getWalkInFamilyMembers()->filter(function(WalkInFamilyMember $familyMember) {
				return $familyMember->getAge() >= 18 and $familyMember->getAge() <= 64;
			});
			
			$familyMembers65 = $walkIn->getWalkInFamilyMembers()->filter(function(WalkInFamilyMember $familyMember) {
				return $familyMember->getAge() >= 65;
			});

			$familyMembersNullAge = $walkIn->getWalkInFamilyMembers()->filter(function(WalkInFamilyMember $familyMember) {
				return $familyMember->getAge() === null;
			});
			
			foreach ($familyMembersNullAge as $familyMember) {
				$walkInFamilyMembersNullAgeArray[] = $familyMember;
			}				
			
			$walkInCounter05 += count($familyMembers05);
			$walkInCounter617 += count($familyMembers617);
			$walkInCounter1864 += count($familyMembers1864);			
			$walkInCounter65 += count($familyMembers65);
			$walkInNullAgeCounter += count($familyMembersNullAge);
		}
		
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
        	'individualsCount' => $householdCount + $familyMemberCounter,
        	'walkInIndividualsServed' => $walkInHouseholdCount + $walkInFamilyMemberCounter,
	       	'femalesCount' => $femaleCounter,
	       	'walkInFemalesCount' => $walkInFemaleCounter,
        	'malesCount' => $maleCounter,
        	'walkInMalesCount' => $walkInMaleCounter,
        	'newHouseholdCount' => $newHouseholdCounter,
        	'newHouseholdCount05' => $newHousehold05Counter,
        	'peopleServed05' => $counter05,
        	'walkInPeopleServed05' => $walkInCounter05,
        	'peopleServed617' => $counter617,
        	'walkInPeopleServed617' => $walkInCounter617,
        	'peopleServed1864' => $counter1864,
        	'walkInPeopleServed1864' => $walkInCounter1864,
        	'peopleServed65' => $counter65,
        	'walkInPeopleServed65' => $walkInCounter65,
        	'headOfHouseholdNullAge' => $headOfHouseholdNullAge,
        	'walkInNullAge' => $walkInHeadOfHouseholdNullAge,
        	'walkInNullFamilyAge' => $walkInFamilyMembersNullAgeArray,
        	'walkInNullGender' => $walkInNullGender,
        	'walkInNullFamilyGender' => $walkInFamilyMembersNullGender,
        	'familyMemberNullAge' => $familyMembersNullAgeArray,
        	'headOfHouseholdNullGender' => $headOfHouseholdNullGender,
        	'familyMemberNullGender' => $familyMembersNullGender,
        	'nullAgeCount' => $nullAgeCounter,
        	'walkInNullAgeCount' => $walkInNullAgeCounter,
        	'nullGenderCount' => $nullGenderCounter,
        	'walkInNullGenderCount' => $walkInNullGenderCounter,
        	'pregnantCount' => $pregnantCounter,
        	'poundageSum' => $poundageSum,
        	'date1' => $date1,
        	'date2' => $date2,
        ));
    }
}
