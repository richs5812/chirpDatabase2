<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class WalkInFamilyMemberType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        	->add('age', IntegerType::class, array(
        		'label' => false,
        		'required' => false,
        	))
			->add('gender', ChoiceType::class, array(
				'label' => false,
				'choices'  => array(
				'' => null,
				'F' => 'F',
				'M' => 'M',
				),
			))
			;
    }
    
	public function configureOptions(OptionsResolver $resolver)
	{
	    $resolver->setDefaults(array(
	        'data_class' => 'AppBundle\Entity\WalkInFamilyMember',
 	   ));
	}
}

?>
