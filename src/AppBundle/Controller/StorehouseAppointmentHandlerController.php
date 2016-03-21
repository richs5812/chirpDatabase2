<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Appointment;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Collections\ArrayCollection;
use AppBundle\Form\AppointmentType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

		
class AppointmentHandlerController extends Controller
{
	/**
     * @Route("/storehouseAppointmentHandler/{date}", name="storehouseAppointmentHandler", defaults={"date" = "default"})
     */
    public function appointmentHandlerAction(Request $request, $date)
    {	
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

			
			$em = $this->getDoctrine()->getManager();

			$em->persist($appointment);
			$em->flush();
				return $this->redirectToRoute('appointments');

    	}
    	
    	if(isset($request->query->getIterator()["DeleteAppointment"])) {
			//dump($request->query->getIterator());die;
			
			$appointment = $this->getDoctrine()
				->getRepository('AppBundle:StorehouseAppointment')
				->findOneById($request->query->getIterator()["ApptID"]);
				
			$em = $this->getDoctrine()->getManager();

			$em->remove($appointment);
			$em->flush();
		}
		
		$em = $this->getDoctrine()->getManager();
			
		$allClientsQuery = $em->createQuery('SELECT c FROM AppBundle:StorehouseClient c ORDER BY c.lastName ASC');
		$allClients = $allClientsQuery->getResult();
			

    	
		$repository = $this->getDoctrine()
			->getRepository('AppBundle:StorehouseAppointment');
		


		$em = $this->getDoctrine()->getManager();
		$query = $em->createQuery(
			'SELECT a
			FROM AppBundle:StorehouseAppointment a WHERE a.date = :date'
			)->setParameter('date', $date);

		$appointments = $query->getResult();
		//dump($appointments);die;
		$array = array();
		foreach ($appointments as $appointment){
			$firstName = $appointment->getClient()->getFirstName();
				$appointment->setClientFirstName($firstName);
			$lastName = $appointment->getClient()->getLastName();
				$appointment->setClientLastName($lastName);
			$theClientID = $appointment->getClient()->getID();
				$appointment->setTheClientID($theClientID);
		}

		$appointment = new Appointment();

		$statusArray = array("Scheduled", "Kept Appointment", "Rescheduled", "Missed Appointment");

		//$form = $this->createForm(AppointmentType::class, $appointment);
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
				//dump($appointment);die;
			
			$client = $this->getDoctrine()
				->getRepository('AppBundle:StorehouseClient')
				->findOneById($request->request->getIterator()["apptClient"]);
				
			$client->addAppointment($appointment);
			
			$em = $this->getDoctrine()->getManager();

			$em->persist($client);
			$em->persist($appointment);
			$em->flush();
			
			$date = date_format($appointment->getDate(), "Y-m-d");
			
			return $this->redirectToRoute('storehouseAppointments', array('date'=> $date));
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
