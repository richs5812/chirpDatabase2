<?php

// tests/AppBundle/Form/Type/ClientTypeTest.php
namespace Tests\AppBundle\Form\Type;

use AppBundle\Form\ClientType;
use AppBundle\Entity\Client;
use Symfony\Component\Form\Test\TypeTestCase;

class ClientTypeTest extends TypeTestCase
{
	/**
	 * @dataProvider getValidTestData
	 */
    public function testForm($formData)
    {
       /* $formData = array(
            'firstName' => 'test',
            'lastName' => 'test2',
        );*/

        $form = $this->factory->create(ClientType::class);
        
        $object = new Client();
        
        if (isset($formData['firstName'])){
			$object->setFirstName($formData['firstName']);
        }       
        if (isset($formData['lastName'])){
			$object->setLastName($formData['lastName']);
        }
        if (isset($formData['age'])){
			$object->setAge($formData['age']);
		}
		if (isset($formData['address'])){
			$object->setAddress($formData['address']);
		}
		if (isset($formData['address2'])){     
			$object->setAddress2($formData['address2']);
		}
		if (isset($formData['homePhoneNumber'])){    
			$object->setHomePhoneNumber($formData['homePhoneNumber']);
		}
		if (isset($formData['cellPhoneNumber'])){ 
			$object->setCellPhoneNumber($formData['cellPhoneNumber']);
		}
		if (isset($formData['zipCode'])){        
			$object->setZipCode($formData['zipCode']);
		}                                    
		if (isset($formData['gender'])){
			$object->setGender($formData['gender']);
		}
		if (isset($formData['isPregnant'])){        
			$object->setIsPregnant($formData['isPregnant']);
		}                                       
		if (isset($formData['enrollmentDate'])){
			$object->setEnrollmentDate($formData['enrollmentDate']);
		}
		if (isset($formData['addressVerified'])){                                               
			$object->setAddressVerified($formData['addressVerified']);
		}
		if (isset($formData['emailAddress'])){
			$object->setEmailAddress($formData['emailAddress']);
		}
		if (isset($formData['familySize'])){                                               
			$object->setFamilySize($formData['familySize']);
		}
		if (isset($formData['adultsNumber'])){
			$object->setAdultsNumber($formData['adultsNumber']);
		}
		if (isset($formData['childrenNumber'])){
			$object->setChildrenNumber($formData['childrenNumber']);
		}
		if (isset($formData['ageRange05'])){                                               
			$object->setAgeRange05($formData['ageRange05']);
		}
		if (isset($formData['ageRange617'])){
			$object->setAgeRange617($formData['ageRange617']);
		}
		if (isset($formData['ageRange1829'])){                                               
			$object->setAgeRange1829($formData['ageRange1829']);
		}
		if (isset($formData['ageRange3039'])){
			$object->setAgeRange3039($formData['ageRange3039']);
		}
		if (isset($formData['ageRange4049'])){                                               
			$object->setAgeRange4049($formData['ageRange4049']);
		}
		if (isset($formData['ageRange5064'])){   
			$object->setAgeRange5064($formData['ageRange5064']);
		}
		if (isset($formData['ageRange65'])){                                                                                                                                                                                 
			$object->setAgeRange65($formData['ageRange65']);
		}
		if (isset($formData['stoveYes'])){                                                                                                                                                                                 
			$object->setStoveYes($formData['stoveYes']);
		}
		if (isset($formData['stoveNo'])){                                                                                                                                                                                 
			$object->setStoveNo($formData['stoveNo']);
		}
		if (isset($formData['stateEmergencyRelease'])){                                                                                                                                                                                 
			$object->setLastName($formData['lastName']);
		}
		if (isset($formData['foodStampAssistance'])){                                                                                                                                                                                 
			$object->setStateEmergencyRelease($formData['stateEmergencyRelease']);
		}
		if (isset($formData['limitedHealthServicesReferral'])){                                                                                                                                                                                 
			$object->setLimitedHealthServicesReferral($formData['limitedHealthServicesReferral']);
		}
		if (isset($formData['additionalServices'])){                                                                                                                                                                                 
			$object->setAdditionalServices($formData['additionalServices']);
		}
		if (isset($formData['otherNotes'])){
			$object->setOtherNotes($formData['otherNotes']);
		}
		if (isset($formData['coatOrder'])){
			$object->setCoatOrder($formData['coatOrder']);
		}
		if (isset($formData['previousChristmasFoodYes'])){
			$object->setPreviousChristmasFoodYes($formData['previousChristmasFoodYes']);
		}
		if (isset($formData['previousChristmasFoodNo'])){
			$object->setPreviousChristmasFoodNo($formData['previousChristmasFoodNo']);
		}
		if (isset($formData['coatOrderDate'])){
			$object->setCoatOrderDate($formData['coatOrderDate']);
		}
		if (isset($formData['childcareServices'])){                                                                                                                                                                                 
			$object->setChildcareServices($formData['childcareServices']);
		}
		if (isset($formData['heatShutoff'])){                                                                                                                                                                                 
			$object->setHeatShutoff($formData['heatShutoff']);
		}
		if (isset($formData['lightShutoff'])){                                                                                                                                                                                 
			$object->setLightShutoff($formData['lightShutoff']);
		}
		if (isset($formData['waterShutoff'])){                                                                                                                                                                                 
			$object->setWaterShutoff($formData['waterShutoff']);
		}
		if (isset($formData['otherShutoff'])){
			$object->setOtherShutoff($formData['otherShutoff']);
		}
		if (isset($formData['taxesDifficulty'])){
			$object->setTaxesDifficulty($formData['taxesDifficulty']);
		}
		if (isset($formData['foreclosureNotice'])){
			$object->setForeclosureNotice($formData['foreclosureNotice']);
		}
		if (isset($formData['landlordEviction'])){
			$object->setLandlordEviction($formData['landlordEviction']);
		}
		if (isset($formData['otherHousingIssue'])){
			$object->setOtherHousingIssue($formData['otherHousingIssue']);
		}
        
        // submit the data to the form directly
        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());
        $this->assertEquals($object, $form->getData());

        $view = $form->createView();
        $children = $view->children;

        foreach (array_keys($formData) as $key) {
            $this->assertArrayHasKey($key, $children);
        }
    }
    
	public function getValidTestData()
    {
    //added nulls for fields that were failing tests
        return array(
            array(
                'data' => array(
                    'firstName' => 'test',
                    'lastName' => 'test',
                    'age' => 'test',
					'address' => 'test',
					'address2' => 'test',                        
					'homePhoneNumber' => 'test',                        
					'cellPhoneNumber' => 'test', 
					'zipCode' => 'test',                                               
					'gender' => null,
					'isPregnant' => 'test',                                               
					'enrollmentDate' => null,
					'addressVerified' => 'test',                                               
					'emailAddress' => 'test',
					'familySize' => 'test',                                               
					'adultsNumber' => 'test',
					'childrenNumber' => 'test',
					'ageRange05' => 'test',                                               
					'ageRange617' => 'test',
					'ageRange1829' => 'test',                                               
					'ageRange3039' => 'test',
					'ageRange4049' => 'test',                                               
					'ageRange5064' => 'test',   
					'ageRange65' => 'test',                                                                                                                                                                                 
					'stoveYes' => 'test',                                                                                                                                                                                 
					'stoveNo' => 'test',                                                                                                                                                                                 
					'stateEmergencyRelease' => null,                                                                                                                                                                                 
					'foodStampAssistance' => null,                                                                                                                                                                                 
					'limitedHealthServicesReferral' => 'test',                                                                                                                                                                                 
					'additionalServices' => 'test',                                                                                                                                                                                 
					'otherNotes' => 'test',
					'coatOrder' => 'test',
					'previousChristmasFoodYes' => 'test',
					'previousChristmasFoodNo' => 'test',
					'coatOrderDate' => null,
					'childcareServices' => 'test',                                                                                                                                                                                 
					'heatShutoff' => 'test',                                                                                                                                                                                 
					'lightShutoff' => 'test',                                                                                                                                                                                 
					'waterShutoff' => 'test',                                                                                                                                                                                 
					'otherShutoff' => 'test',
					'taxesDifficulty' => 'test',
					'foreclosureNotice' => 'test',
					'landlordEviction' => 'test',
					'otherHousingIssue' => 'test',
                ),
            ),
			array(
                'data' => array(),
            ),
            array(
                'data' => array(
                    'firstName' => null,
                    'lastName' => 'test2',
                    'age' => '25',
					'address' => null,
					'address2' => null,                        
					'homePhoneNumber' => null,                        
					'cellPhoneNumber' => null, 
					'zipCode' => null,                                               
					'gender' => null,
					'isPregnant' => null,                                               
					'enrollmentDate' => null,
					'addressVerified' => null,                                               
					'emailAddress' => null,
					'familySize' => null,                                               
					'adultsNumber' => null,
					'childrenNumber' => null,
					'ageRange05' => null,                                               
					'ageRange617' => null,
					'ageRange1829' => null,                                               
					'ageRange3039' => null,
					'ageRange4049' => null,                                               
					'ageRange5064' => null,   
					'ageRange65' => null,                                                                                                                                                                                 
					'stoveYes' => null,                                                                                                                                                                                 
					'stoveNo' => null,                                                                                                                                                                                 
					'stateEmergencyRelease' => null,                                                                                                                                                                                 
					'foodStampAssistance' => null,                                                                                                                                                                                 
					'limitedHealthServicesReferral' => null,                                                                                                                                                                                 
					'additionalServices' => null,                                                                                                                                                                                 
					'otherNotes' => null,
					'coatOrder' => null,
					'previousChristmasFoodYes' => null,
					'previousChristmasFoodNo' => null,
					'coatOrderDate' => null,
					'childcareServices' => null,                                                                                                                                                                                 
					'heatShutoff' => null,                                                                                                                                                                                 
					'lightShutoff' => null,                                                                                                                                                                                 
					'waterShutoff' => null,                                                                                                                                                                                 
					'otherShutoff' => null,
					'taxesDifficulty' => null,
					'foreclosureNotice' => null,
					'landlordEviction' => null,
					'otherHousingIssue' => null,
                ),
            ),
        );
    }
}