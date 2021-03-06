<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class VolunteerSessionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
			->add('date', DateType::class, array(
				'required' => false,
				'label' => false,
				'widget' => 'single_text',
				'format' => 'MM/dd/yyyy',
				))
			->add('hours', NumberType::class, array(
				'scale' => 1,
				'required' => false,
				'label' => false,
				))
        	->add('note', TextareaType::class, array(
        		'required' => false,
        		'label' => false,
        	));
    }
    
	public function configureOptions(OptionsResolver $resolver)
	{
	    $resolver->setDefaults(array(
	        'data_class' => 'AppBundle\Entity\VolunteerSession',
 	   ));
	}
}

?>
