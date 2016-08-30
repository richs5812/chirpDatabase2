<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Client;
use AppBundle\Entity\FocusGroup;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Form\ClientType;
use AppBundle\Form\ReferralType;
use Doctrine\Common\Collections\ArrayCollection;

class FocusGroupDisplayController extends Controller
{
	/**
     * @Route("/form/focusGroup/{groupName}", name="focusGroupDisplay", defaults={"groupName" = "A team"})
     */
    public function focusGroupDisplayAction(Request $request, $groupName)
    {	

		$em = $this->getDoctrine()->getManager();
				
		//query clients in focus group
		$query = $em->createQuery("SELECT c FROM AppBundle:Client c JOIN c.focusGroups f WHERE f.groupName = :groupName");
		$query->setParameter('groupName', $groupName);
		$result = $query->getResult();
		//dump($result);die;

	    return $this->render('default/focusGroupDisplay.html.twig', array(
	        'clients' => $result,
	        'groupName' => $groupName,
	    ));
	}
}
?>
