<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Validator\Constraints\Date;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityRepository;

class WalkInType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('age')
            ->add('gender', ChoiceType::class, array(
				'choices'  => array(
				'' => null,
				'F' => 'F',
				'M' => 'M',
				),
       		))
			->add('date', DateType::class, array(
				'widget' => 'single_text',
				'format' => 'MM/dd/yyyy',
				));
		$builder
			->add('save', SubmitType::class, array('label' => 'Save Walk-in record'))
		;
		
		$builder->add('walkInFamilyMembers', CollectionType::class, array(
            'entry_type' => WalkInFamilyMemberType::class,
            'allow_add' => true,
			'by_reference' => false,
			'allow_delete' => true,
        ));
	}
    
	public function configureOptions(OptionsResolver $resolver)
	{
	    $resolver->setDefaults(array(
	        'data_class' => 'AppBundle\Entity\WalkIn',
 	   ));
	}
}

?>
