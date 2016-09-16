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
		
class AppointmentController extends Controller
{
	/**
     * @Route("/form/appointments/{date}", name="appointments", defaults={"date" = "default"})
     */
    public function AppointmentAction(Request $request, $date)
    {	
    
    	$em = $this->getDoctrine()->getManager();
						
		if(isset($request->query->getIterator()["formDatePicker"])){
			$date=date_create($request->query->getIterator()["formDatePicker"]);
		} else if ($date == 'default') {
			$date=date_create(date("Y-m-d"));  	
    	} else {
    		$date=date_create($date);
    	}

    	if(isset($request->query->getIterator()["UpdateAppointment"])) {    		
			$appointment = $this->getDoctrine()
				->getRepository('AppBundle:Appointment')
				->findOneById($request->query->getIterator()["ApptID"]);
						
			$apptDate=date_create($request->query->getIterator()["AppointmentDate"]);
			$appointment->setDate($apptDate);
			$appointment->setStatus($request->query->getIterator()["AppointmentStatus"]);
			$appointment->setNote($request->query->getIterator()["AppointmentNote"]);

			$em->persist($appointment);
			$em->flush();
			
			if (date_format($date, "Y-m-d") != date_format($appointment->getDate(), "Y-m-d")){
				return $this->render('default/appointmentHandler.html.twig', array(
					'newDate' => date_format($appointment->getDate(), "Y-m-d"),
					'origDate' => date_format($date, "Y-m-d"),
				));
			}
						
			return $this->redirectToRoute('appointments', array('date'=> date_format($date, "Y-m-d")));
    	}
    	
    	if(isset($request->query->getIterator()["DeleteAppointment"])) {			
			$appointment = $this->getDoctrine()
				->getRepository('AppBundle:Appointment')
				->findOneById($request->query->getIterator()["ApptID"]);
			$apptDate = date_format($appointment->getDate(), "Y-m-d");	
			
			$em->remove($appointment);
			$em->flush();
			
			return $this->redirectToRoute('appointments', array('date' => $apptDate));
		}
					
		$allClientsQuery = $em->createQuery('SELECT c FROM AppBundle:Client c ORDER BY c.lastName ASC');
		$allClients = $allClientsQuery->getResult();
			
		$repository = $this->getDoctrine()
			->getRepository('AppBundle:Appointment');
		
		//get existing appointments
		$query = $em->createQuery(
			'SELECT a
			FROM AppBundle:Appointment a WHERE a.date = :date'
			)->setParameter('date', $date);
		$appointments = $query->getResult();

		//form for new appointment
		$appointment = new Appointment();
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
			->add('save', SubmitType::class, array('label' => 'Schedule Appointment'))
            ->getForm();
        	
		$form->handleRequest($request);
		
    if ($form->isSubmitted() && $form->isValid()) {			
			$client = $this->getDoctrine()
				->getRepository('AppBundle:Client')
				->findOneById($request->request->getIterator()["apptClient"]);
				
			$client->addAppointment($appointment);

			$em->persist($client);
			$em->persist($appointment);
			$em->flush();

			if (date_format($date, "Y-m-d") != date_format($appointment->getDate(), "Y-m-d")){
				return $this->render('default/appointmentHandler.html.twig', array(
					'newDate' => date_format($appointment->getDate(), "Y-m-d"),
					'origDate' => date_format($date, "Y-m-d"),
				));
			}
						
			return $this->redirectToRoute('appointments', array('date'=> date_format($date, "Y-m-d")));
		}

	    return $this->render('default/appointment.html.twig', array(
			'form' => $form->createView(),
	        'appointments' => $appointments,
	        'statusArray' => $statusArray,
	        'allClients' => $allClients,
	        'date' => $date,
	    ));
	}
}
?>
