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

class FamilyMemberEncryptorController extends Controller
{
	/**
     * @Route("/form/familyMemberEncryptor")
     */
    public function familyMemberEncryptAction()
    {	
    
		$em = $this->getDoctrine()->getManager();
		
		$familyMemberQuery = $em->createQuery('SELECT f FROM AppBundle:FamilyMember f');
		$unencryptedFamilyMembers = $familyMemberQuery->getResult();

		//dump($unencryptedFamilyMembers);die;		
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
		foreach ($unencryptedFamilyMembers as $unencryptedFamilyMember) {
			$encryptedFamilyMember = new FamilyMember();
			$encryptedFamilyMember->setName($unencryptedFamilyMember->getName());
			$encryptedFamilyMember->setAge($unencryptedFamilyMember->getAge());
			$encryptedFamilyMember->setGender($unencryptedFamilyMember->getGender());
			$encryptedFamilyMember->setRelationship($unencryptedFamilyMember->getRelationship());
			
			$em->persist($encryptedFamilyMember);
			$em->flush();
			//dump($encryptedFamilyMember);die;		
		}
			
		return new Response('Family Member Encryptor complete');
	}
}
?>
