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

class EncryptorController extends Controller
{
	/**
     * @Route("/form/encryptor")
     */
    public function encryptAction()
    {	
    
		$em = $this->getDoctrine()->getManager();
		
		$clientQuery = $em->createQuery('SELECT c FROM AppBundle:Client c');
		$unencryptedClients = $clientQuery->getResult();

		//dump($unencryptedClients);die;		
		$savedSecret = new Secret();
		$secret = $savedSecret->getSecret();
		
		$subscriber = new DoctrineEncryptSubscriber(
			new \Doctrine\Common\Annotations\AnnotationReader,
			new \DoctrineEncrypt\Encryptors\AES256Encryptor($secret)
		);

		$eventManager = $em->getEventManager();
		$eventManager->addEventSubscriber($subscriber);

		// query by the primary key (usually "id")
		//$client = $repository->find($id);

		//$eventManager->removeEventSubscriber($subscriber);
		foreach ($unencryptedClients as $unencryptedClient) {
			$encryptedClient = new Client();
			$encryptedClient->setFirstName($unencryptedClient->getFirstName());
			$encryptedClient->setLastName($unencryptedClient->getLastName());
			$encryptedClient->setAge($unencryptedClient->getAge());
			$encryptedClient->setGender($unencryptedClient->getGender());
			$encryptedClient->setAddress($unencryptedClient->getAddress());    
			$encryptedClient->setAddress2($unencryptedClient->getAddress2());    
			$encryptedClient->setHomePhoneNumber($unencryptedClient->getHomePhoneNumber());
			$encryptedClient->setCellPhoneNumber($unencryptedClient->getCellPhoneNumber());
			$encryptedClient->setZipCode($unencryptedClient->getZipCode());
			$encryptedClient->setIsPregnant($unencryptedClient->getIsPregnant());
			$encryptedClient->setEnrollmentDate($unencryptedClient->getEnrollmentDate());
			$encryptedClient->setAddressVerified($unencryptedClient->getAddressVerified());
			$encryptedClient->setEmailAddress($unencryptedClient->getEmailAddress());
			$encryptedClient->setFamilySize($unencryptedClient->getFamilySize());
			$encryptedClient->setAdultsNumber($unencryptedClient->getAdultsNumber());
			$encryptedClient->setAgeRange05($unencryptedClient->getAgeRange05());
			$encryptedClient->setAgeRange617($unencryptedClient->getAgeRange617());
			$encryptedClient->setAgeRange1829($unencryptedClient->getAgeRange1829());
			$encryptedClient->setAgeRange3039($unencryptedClient->getAgeRange3039());
			$encryptedClient->setAgeRange4049($unencryptedClient->getAgeRange4049());
			$encryptedClient->setAgeRange5064($unencryptedClient->getAgeRange5064());
			$encryptedClient->setAgeRange65($unencryptedClient->getAgeRange65());
			$encryptedClient->setStoveYes($unencryptedClient->getStoveYes());
			$encryptedClient->setStoveNo($unencryptedClient->getStoveNo());
			$encryptedClient->setStateEmergencyRelease($unencryptedClient->getStateEmergencyRelease());
			$encryptedClient->setFoodStampAssistance($unencryptedClient->getFoodStampAssistance());
			$encryptedClient->setLimitedHealthServicesReferral($unencryptedClient->getLimitedHealthServicesReferral());
			$encryptedClient->setAdditionalServices($unencryptedClient->getAdditionalServices());
			$encryptedClient->setOtherNotes($unencryptedClient->getOtherNotes());
			$encryptedClient->setCoatOrder($unencryptedClient->getCoatOrder());
			$encryptedClient->setPreviousChristmasFoodYes($unencryptedClient->getPreviousChristmasFoodYes());
			$encryptedClient->setPreviousChristmasFoodNo($unencryptedClient->getPreviousChristmasFoodNo());
			$encryptedClient->setCoatOrderDate($unencryptedClient->getCoatOrderDate());
			$encryptedClient->setChildrenNumber($unencryptedClient->getChildrenNumber());
			$encryptedClient->setChildcareServices($unencryptedClient->getChildcareServices());
			$encryptedClient->setHeatShutoff($unencryptedClient->getHeatShutoff());
			$encryptedClient->setLightShutoff($unencryptedClient->getLightShutoff());
			$encryptedClient->setWaterShutoff($unencryptedClient->getWaterShutoff());
			$encryptedClient->setOtherShutoff($unencryptedClient->getOtherShutoff());
			$encryptedClient->setTaxesDifficulty($unencryptedClient->getTaxesDifficulty());
			$encryptedClient->setForeclosureNotice($unencryptedClient->getForeclosureNotice());
			$encryptedClient->setLandlordEviction($unencryptedClient->getLandlordEviction());
			$encryptedClient->setOtherHousingIssue($unencryptedClient->getOtherHousingIssue());
			
			$em->persist($encryptedClient);
			$em->flush();
			//dump($encryptedClient);die;		
		}
			
		return new Response('Encryptor complete');
	}
}
?>
