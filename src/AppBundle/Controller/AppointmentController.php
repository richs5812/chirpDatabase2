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
			//get the current date
			$date = $appointment->getDate();	
			//set the updated date
			$apptDate=date_create($request->query->getIterator()["AppointmentDate"]);
			$appointment->setDate($apptDate);
			$appointment->setStatus($request->query->getIterator()["AppointmentStatus"]);
			$appointment->setTime($request->query->getIterator()["AppointmentTime"]);
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
			FROM AppBundle:Appointment a
			JOIN a.client c
			WHERE a.date = :date
			ORDER BY a.time, c.lastName ASC'
			)->setParameter('date', $date);
		$appointments = $query->getResult();

		//form for new appointment
		$appointment = new Appointment();
		$statusArray = array("Scheduled", "Kept Appointment", "Rescheduled", "Missed Appointment");
		$timeArray = array("10:00", "10:15", "10:30", "10:45", "11:00", "11:15", "11:30", "11:45", "12:00", "12:15", "12:30", "12:45", "1:00", "1:15", "1:30", "3:00", "3:15", "3:30", "3:45", "4:00", "4:15", "4:30", "4:45", "5:00", "5:15", "5:30", "5:45", "6:00", "6:15", "6:30");  
		
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
			->add('time', ChoiceType::class, array(
				'label' => false,
				'choices'  => array(
				'' => null,
				'10:00' => '10:00',
				'10:15' => '10:15',
				'10:30' => '10:30',
				'10:45' => '10:45',
				'11:00' => '11:00',
				'11:15' => '11:15',
				'11:30' => '11:30',
				'11:45' => '11:45',
				'12:00' => '12:00',
				'12:15' => '12:15',
				'12:30' => '12:30',
				'12:45' => '12:45',
				'1:00' => '1:00',
				'1:15' => '1:15',
				'1:30' => '1:30',
				'3:00' => '3:00',
				'3:15' => '3:15',
				'3:30' => '3:30',
				'3:45' => '3:45',
				'4:00' => '4:00',
				'4:15' => '4:15',
				'4:30' => '4:30',
				'4:45' => '4:45',
				'5:00' => '5:00',
				'5:15' => '5:15',
				'5:30' => '5:30',
				'5:45' => '5:45',
				'6:00' => '6:00',
				'6:15' => '6:15',
				'6:30' => '6:30',
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
// 			dump($request);die;

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
	        'timeArray' => $timeArray,
	        'allClients' => $allClients,
	        'date' => $date,
	    ));
	}
}
?>
