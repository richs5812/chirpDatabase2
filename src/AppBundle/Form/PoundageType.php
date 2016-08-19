<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class PoundageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
			->add('date', DateType::class, array(
				'required' => true,
				'label' => false,
				'widget' => 'single_text',
				'format' => 'MM/dd/yyyy',
				))
			->add('poundage', IntegerType::class, array(
				'label' => false,
				))
        	->add('note', TextareaType::class, array(
        		'required' => false,
        		'label' => false,
        	))
			->add('save', SubmitType::class, array('label' => 'Save New Poundage'));
    }
    
	public function configureOptions(OptionsResolver $resolver)
	{
	    $resolver->setDefaults(array(
	        'data_class' => 'AppBundle\Entity\Poundage',
 	   ));
	}
}

?>