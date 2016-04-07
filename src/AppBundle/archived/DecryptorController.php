<?php

namespace AppBundle\Controller;

use AppBundle\Secret\Secret;
use AppBundle\Entity\Client;
use AppBundle\Entity\FamilyMember;
use AppBundle\Entity\Referral;
use AppBundle\Entity\Appointment;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Form\ClientType;
use AppBundle\Form\ReferralType;
use Doctrine\Common\Collections\ArrayCollection;
use DoctrineEncrypt\Subscribers\DoctrineEncryptSubscriber;
use Symfony\Component\HttpFoundation\Response;

class DecryptorController extends Controller
{

    public function decryptAction()
    {	
		$em = $this->getDoctrine()->getManager();
				
		$savedSecret = new Secret();
		$secret = $savedSecret->getSecret();
		
		$subscriber = new DoctrineEncryptSubscriber(
			new \Doctrine\Common\Annotations\AnnotationReader,
			new \DoctrineEncrypt\Encryptors\AES256Encryptor($secret)
		);

		$eventManager = $em->getEventManager();
		$eventManager->addEventSubscriber($subscriber);

		$clientQuery = $em->createQuery('SELECT c FROM AppBundle:Client c');
		$unencryptedClients = $clientQuery->getResult();

		//dump($unencryptedClients);die;
		
		$eventManager->removeEventSubscriber($subscriber);

		// query by the primary key (usually "id")
		//$client = $repository->find($id);

		//$eventManager->removeEventSubscriber($subscriber);
		foreach ($unencryptedClients as $unencryptedClient) {
			$clearClient = new Client();
			$clearClient->setFirstName($unencryptedClient->getFirstName());
			$clearClient->setLastName($unencryptedClient->getLastName());
			$clearClient->setAge($unencryptedClient->getAge());
			$clearClient->setGender($unencryptedClient->getGender());
			$clearClient->setAddress($unencryptedClient->getAddress());    
			$clearClient->setAddress2($unencryptedClient->getAddress2());    
			$clearClient->setHomePhoneNumber($unencryptedClient->getHomePhoneNumber());
			$clearClient->setCellPhoneNumber($unencryptedClient->getCellPhoneNumber());
			$clearClient->setZipCode($unencryptedClient->getZipCode());
			$clearClient->setIsPregnant($unencryptedClient->getIsPregnant());
			$clearClient->setEnrollmentDate($unencryptedClient->getEnrollmentDate());
			$clearClient->setAddressVerified($unencryptedClient->getAddressVerified());
			$clearClient->setEmailAddress($unencryptedClient->getEmailAddress());
			$clearClient->setFamilySize($unencryptedClient->getFamilySize());
			$clearClient->setAdultsNumber($unencryptedClient->getAdultsNumber());
			$clearClient->setAgeRange05($unencryptedClient->getAgeRange05());
			$clearClient->setAgeRange617($unencryptedClient->getAgeRange617());
			$clearClient->setAgeRange1829($unencryptedClient->getAgeRange1829());
			$clearClient->setAgeRange3039($unencryptedClient->getAgeRange3039());
			$clearClient->setAgeRange4049($unencryptedClient->getAgeRange4049());
			$clearClient->setAgeRange5064($unencryptedClient->getAgeRange5064());
			$clearClient->setAgeRange65($unencryptedClient->getAgeRange65());
			$clearClient->setStoveYes($unencryptedClient->getStoveYes());
			$clearClient->setStoveNo($unencryptedClient->getStoveNo());
			$clearClient->setStateEmergencyRelease($unencryptedClient->getStateEmergencyRelease());
			$clearClient->setFoodStampAssistance($unencryptedClient->getFoodStampAssistance());
			$clearClient->setLimitedHealthServicesReferral($unencryptedClient->getLimitedHealthServicesReferral());
			$clearClient->setAdditionalServices($unencryptedClient->getAdditionalServices());
			$clearClient->setOtherNotes($unencryptedClient->getOtherNotes());
			$clearClient->setCoatOrder($unencryptedClient->getCoatOrder());
			$clearClient->setPreviousChristmasFoodYes($unencryptedClient->getPreviousChristmasFoodYes());
			$clearClient->setPreviousChristmasFoodNo($unencryptedClient->getPreviousChristmasFoodNo());
			$clearClient->setCoatOrderDate($unencryptedClient->getCoatOrderDate());
			$clearClient->setChildrenNumber($unencryptedClient->getChildrenNumber());
			$clearClient->setChildcareServices($unencryptedClient->getChildcareServices());
			$clearClient->setHeatShutoff($unencryptedClient->getHeatShutoff());
			$clearClient->setLightShutoff($unencryptedClient->getLightShutoff());
			$clearClient->setWaterShutoff($unencryptedClient->getWaterShutoff());
			$clearClient->setOtherShutoff($unencryptedClient->getOtherShutoff());
			$clearClient->setTaxesDifficulty($unencryptedClient->getTaxesDifficulty());
			$clearClient->setForeclosureNotice($unencryptedClient->getForeclosureNotice());
			$clearClient->setLandlordEviction($unencryptedClient->getLandlordEviction());
			$clearClient->setOtherHousingIssue($unencryptedClient->getOtherHousingIssue());
			
			$em->persist($clearClient);
			$em->flush();
			//dump($clearClient);die;		
		}
			
		return new Response('Decryptor complete');
	}
}
?>
