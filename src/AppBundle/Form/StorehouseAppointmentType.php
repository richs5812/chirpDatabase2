<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class StorehouseAppointmentType extends AbstractType
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
        	->add('note', TextareaType::class, array(
        		'label' => false,
        		'required' => false,
        	));
    }
    
	public function configureOptions(OptionsResolver $resolver)
	{
	    $resolver->setDefaults(array(
	        'data_class' => 'AppBundle\Entity\StorehouseAppointment',
 	   ));
	}
}

?>