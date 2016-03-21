<?php

// src/AppBundle/Form/StorehouseClientType.php
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


class StorehouseClientType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder
            ->add('firstName')
            ->add('lastName')
            ->add('age')
            ->add('address')
            ->add('address2')                        
            ->add('homePhoneNumber')                        
            ->add('cellPhoneNumber') 
			->add('zipCode')                                               
            ->add('gender', ChoiceType::class, array(
				'choices'  => array(
				'' => null,
				'F' => 'F',
				'M' => 'M',
				),
       		))
			->add('isPregnant')                                               
			->add('enrollmentDate', DateType::class, array(
				'widget' => 'single_text',
				'format' => 'MM/dd/yyyy',
				'required' => false,
				));
		$builder
			->add('addressVerified')                                               
			->add('emailAddress')
 			->add('familySize')                                               
			->add('adultsNumber')
			->add('childrenNumber')
			->add('ageRange05')                                               
			->add('ageRange617')
			->add('ageRange1829')                                               
			->add('ageRange3039')
			->add('ageRange4049')                                               
			->add('ageRange5064')   
			->add('ageRange65')                                                                                                                                                                                 
			->add('stoveYes')                                                                                                                                                                                 
			->add('stoveNo')                                                                                                                                                                                 
			->add('stateEmergencyRelease')                                                                                                                                                                                 
			->add('foodStampAssistance')                                                                                                                                                                                 
			->add('limitedHealthServicesReferral')                                                                                                                                                                                 
			->add('additionalServices')                                                                                                                                                                                 
			->add('otherNotes')
			->add('coatOrder')
			->add('previousChristmasFoodYes')
			->add('previousChristmasFoodNo')
			->add('coatOrderDate', DateType::class, array(
				'widget' => 'single_text',
				'format' => 'MM/dd/yyyy',
				'required' => false,
				))
			->add('childcareServices')                                                                                                                                                                                 
			->add('heatShutoff')                                                                                                                                                                                 
			->add('lightShutoff')                                                                                                                                                                                 
			->add('waterShutoff')                                                                                                                                                                                 
			->add('otherShutoff')
			->add('taxesDifficulty')
			->add('foreclosureNotice')
			->add('landlordEviction')
			->add('otherHousingIssue')
			->add('save', SubmitType::class, array('label' => 'Save Client'))
		;
		$builder->add('storehouseFamilyMembers', CollectionType::class, array(
            'entry_type' => StorehouseFamilyMemberType::class,
            'allow_add' => true,
			'by_reference' => false,
			'allow_delete' => true,
        ));
		$builder->add('storehouseReferrals', CollectionType::class, array(
            'entry_type' => StorehouseReferralType::class,
            'allow_add' => true,
			'by_reference' => false,
			'allow_delete' => true,
        ));
		$builder->add('storehouseAppointments', CollectionType::class, array(
            'entry_type' => StorehouseAppointmentType::class,
            'allow_add' => true,
			'by_reference' => false,
			'allow_delete' => true,
        ));
    }
    
	public function configureOptions(OptionsResolver $resolver)
	{
	    $resolver->setDefaults(array(
	        'data_class' => 'AppBundle\Entity\StorehouseClient',
 	   ));
	}
}

?>