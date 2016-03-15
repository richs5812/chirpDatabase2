<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Client;
use AppBundle\Entity\FamilyMember;
use Symfony\Component\HttpFoundation\Response;

class ClientNameController extends Controller
{
    /**
     * @Route("/clientName/{id}", name="clientName")
     */
    public function nameAction($id)
{

    $familyMember = $this->getDoctrine()
        ->getRepository('AppBundle:FamilyMember')
        ->find($id);

    $clientName = $familyMember->getClient()->getFirstName();

        return new Response(
            'Client Name is: '.$clientName
        );
    }
}
