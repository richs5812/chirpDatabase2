<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class FocusGroupType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
		/*$builder->add('groupName', TextType::class, array(
			'label' => false,
			'required' => false,
		));*/
		$builder->add('groupName', HiddenType::class);
		
		/*
		
		$builder->add('groupName', ChoiceType::class, array(
			'choices' => array('In Stock' => true, 'Out of Stock' => false),
		));*/
    }
    
	public function configureOptions(OptionsResolver $resolver)
	{
	    $resolver->setDefaults(array(
	        'data_class' => 'AppBundle\Entity\FocusGroup',
 	   ));
	}
}

?>