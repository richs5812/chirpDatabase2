<?php

// src/AppBundle/Form/ReferralType.php
namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class AppointmentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
			->add('date', DateType::class, array(
				'label' => false,
				'required' => true,
				'widget' => 'single_text',
				'format' => 'MM/dd/yyyy',
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
        	));
    }
    
	public function configureOptions(OptionsResolver $resolver)
	{
	    $resolver->setDefaults(array(
	        'data_class' => 'AppBundle\Entity\Appointment',
 	   ));
	}
}

?>
