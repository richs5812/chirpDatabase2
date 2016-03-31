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
			
			$em->persist($encryptedClient);
			$em->flush();
			//dump($encryptedClient);die;
		
		}
			
		return new Response('Encryptor complete');
	}
}
?>
