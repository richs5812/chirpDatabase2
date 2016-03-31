<?php

namespace AppBundle\Controller;

use AppBundle\Entity\StorehouseAppointment;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use AppBundle\Secret\Secret;
use DoctrineEncrypt\Subscribers\DoctrineEncryptSubscriber;
		
class StorehouseAppointmentController extends Controller
{
	/**
     * @Route("/form/storehouseAppointments/{date}", name="storehouseAppointments", defaults={"date" = "default"})
     */
    public function storehouseAppointmentAction(Request $request, $date)
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
		
		if(isset($request->query->getIterator()["formDatePicker"])){
			$date=date_create($request->query->getIterator()["formDatePicker"]);
		} else if ($date == 'default') {
			$date=date_create(date("Y-m-d"));  	
    	} else {
    		$date=date_create($date);
    	}
		
    	if(isset($request->query->getIterator()["UpdateAppointment"])) {
    		//dump($request->query->getIterator());die;
    		
			$appointment = $this->getDoctrine()
				->getRepository('AppBundle:StorehouseAppointment')
				->findOneById($request->query->getIterator()["ApptID"]);
			
			//dump($appointment);die;
			
			$apptDate=date_create($request->query->getIterator()["AppointmentDate"]);
			$appointment->setDate($apptDate);
			$appointment->setStatus($request->query->getIterator()["AppointmentStatus"]);
			$appointment->setNote($request->query->getIterator()["AppointmentNote"]);

			
			//$em = $this->getDoctrine()->getManager();

			$em->persist($appointment);
			$em->flush();
			
			if (date_format($date, "Y-m-d") != date_format($appointment->getDate(), "Y-m-d")){
				return $this->render('default/storehouseAppointmentHandler.html.twig', array(
					'newDate' => date_format($appointment->getDate(), "Y-m-d"),
					'origDate' => date_format($date, "Y-m-d"),
				));
			}
						
			return $this->redirectToRoute('storehouseAppointments', array('date'=> date_format($date, "Y-m-d")));

    	}
    	
    	if(isset($request->query->getIterator()["DeleteAppointment"])) {
			//dump($request->query->getIterator());die;
			
			$appointment = $this->getDoctrine()
				->getRepository('AppBundle:StorehouseAppointment')
				->findOneById($request->query->getIterator()["ApptID"]);
			$apptDate = date_format($appointment->getDate(), "Y-m-d");	
			$em = $this->getDoctrine()->getManager();

			$em->remove($appointment);
			$em->flush();
			
			return $this->redirectToRoute('storehouseAppointments', array('date' => $apptDate));

		}
		
		//$em = $this->getDoctrine()->getManager();
			
		$allClientsQuery = $em->createQuery('SELECT c FROM AppBundle:StorehouseClient c ORDER BY c.lastName ASC');
		$allClients = $allClientsQuery->getResult();
			

    	
		$repository = $this->getDoctrine()
			->getRepository('AppBundle:StorehouseAppointment');
		


		//$em = $this->getDoctrine()->getManager();
		$query = $em->createQuery(
			'SELECT a
			FROM AppBundle:StorehouseAppointment a WHERE a.date = :date'
			)->setParameter('date', $date);

		$appointments = $query->getResult();
		//dump($appointments);die;
		$array = array();
		foreach ($appointments as $appointment){
			$firstName = $appointment->getStorehouseClient()->getFirstName();
				$appointment->setStorehouseClientFirstName($firstName);
			$lastName = $appointment->getStorehouseClient()->getLastName();
				$appointment->setStorehouseClientLastName($lastName);
			$theClientID = $appointment->getStorehouseClient()->getID();
				$appointment->setTheStorehouseClientID($theClientID);
		}

		$appointment = new StorehouseAppointment();

		$statusArray = array("Scheduled", "Kept Appointment", "Rescheduled", "Missed Appointment");

		$form = $this->createFormBuilder($appointment)
			->add('date', DateType::class, array(
				'label' => false,
				'required' => true,
				'widget' => 'single_text',
				'format' => 'MM/dd/yyyy',
				'data' => $date,
				))
			->add('status', ChoiceType::class, array(
				'label' => false,
				'choices'  => array(
				'' => null,
				'Scheduled' => 'Scheduled',
				'Kept Appointment' => 'Kept Appointment',
				'Rescheduled' => 'Rescheduled',
				'Missed Appointment' => 'Missed Appointment',
				),
			))
        	->add('note', TextareaType::class, array(
        		'label' => false,
        		'required' => false,
        	))
			->add('save', SubmitType::class, array('label' => 'Create Appointment'))
            ->getForm();
        	
		$form->handleRequest($request);
		
    if ($form->isSubmitted() && $form->isValid()) {
			
			$client = $this->getDoctrine()
				->getRepository('AppBundle:StorehouseClient')
				->findOneById($request->request->getIterator()["apptClient"]);
				
			$client->addStorehouseAppointment($appointment);
			
			//$em = $this->getDoctrine()->getManager();

			$em->persist($client);
			$em->persist($appointment);
			$em->flush();

			if (date_format($date, "Y-m-d") != date_format($appointment->getDate(), "Y-m-d")){
				return $this->render('default/storehouseAppointmentHandler.html.twig', array(
					'newDate' => date_format($appointment->getDate(), "Y-m-d"),
					'origDate' => date_format($date, "Y-m-d"),
				));
			}
						
			return $this->redirectToRoute('storehouseAppointments', array('date'=> date_format($date, "Y-m-d")));
		}

	    return $this->render('default/storehouseAppointment.html.twig', array(
			'form' => $form->createView(),
	        'appointments' => $appointments,
	        'statusArray' => $statusArray,
	        'allClients' => $allClients,
	        'date' => $date,
	    ));
	}
}
?>
