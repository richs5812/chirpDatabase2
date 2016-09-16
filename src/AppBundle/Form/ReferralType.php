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
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityRepository;

class ReferralType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
			->add('description', TextType::class, array(
				'label' => false,
        		'required' => false,				
			))
			->add('referralName', EntityType::class, array(
			// query choices from this entity
			'class' => 'AppBundle:ReferralName',

			// use the User.username property as the visible option string
			'choice_label' => 'name',
			
			'placeholder' => 'Choose a referral type',
// 			'required' => false,
			'label' => false,
			
			'query_builder' => function (EntityRepository $er) {
				return $er->createQueryBuilder('r')
					->orderBy('r.name', 'ASC')
					;
			},

			// used to render a select box, check boxes or radios
			// 'multiple' => true,
			// 'expanded' => true,
			))
			->add('date', DateType::class, array(
				'label' => false,
// 				'required' => false,
				'widget' => 'single_text',
				'format' => 'MM/dd/yyyy',
				))
        	->add('notes', TextareaType::class, array(
        		'label' => false,
        		'required' => false,
        	));
    }
    
	public function configureOptions(OptionsResolver $resolver)
	{
	    $resolver->setDefaults(array(
	        'data_class' => 'AppBundle\Entity\Referral',
 	   ));
	}
}

?>
