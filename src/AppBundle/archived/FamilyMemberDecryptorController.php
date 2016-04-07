<?php

namespace AppBundle\Controller;

use AppBundle\Secret\Secret;
use AppBundle\Entity\FamilyMember;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Form\ClientType;
use AppBundle\Form\ReferralType;
use Doctrine\Common\Collections\ArrayCollection;
use DoctrineEncrypt\Subscribers\DoctrineEncryptSubscriber;
use Symfony\Component\HttpFoundation\Response;

class FamilyMemberDecryptorController extends Controller
{

    public function familyMemberDecryptAction()
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

		$familyMemberQuery = $em->createQuery('SELECT f FROM AppBundle:FamilyMember f');
		$unencryptedFamilyMembers = $familyMemberQuery->getResult();

		//dump($unencryptedFamilyMembers);die;
		
		$eventManager->removeEventSubscriber($subscriber);

		// query by the primary key (usually "id")
		//$client = $repository->find($id);

		//$eventManager->removeEventSubscriber($subscriber);
		foreach ($unencryptedFamilyMembers as $unencryptedFamilyMember) {
			$clearFamilyMember = new FamilyMember();
			$clearFamilyMember->setName($unencryptedFamilyMember->getName());
			$clearFamilyMember->setAge($unencryptedFamilyMember->getAge());
			$clearFamilyMember->setGender($unencryptedFamilyMember->getGender());
			$clearFamilyMember->setRelationship($unencryptedFamilyMember->getRelationship());
			
			$em->persist($clearFamilyMember);
			$em->flush();
			//dump($clearFamilyMember);die;		
		}
			
		return new Response('Family Member Decryptor complete');
	}
}
?>
