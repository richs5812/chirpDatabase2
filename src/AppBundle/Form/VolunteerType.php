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

class VolunteerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName')
            ->add('lastName')
            ->add('address')
            ->add('address2')                        
            ->add('homePhoneNumber')                        
            ->add('cellPhoneNumber') 
			->add('zipCode')                                               
			->add('isVolunteer')                                               
			->add('isDonor')
			->add('emailAddress')
			->add('newVolunteerCategory', EntityType::class, array(
			// query choices from this entity
			'class' => 'AppBundle:VolunteerCategory',

			// use the User.username property as the visible option string
			'choice_label' => 'category',
			
			'placeholder' => 'Add a volunteer category',
			'required' => false,
			'label' => false,
			
			'query_builder' => function (EntityRepository $er) {
				return $er->createQueryBuilder('v')
					->orderBy('v.category', 'ASC')
					;
			},

			// used to render a select box, check boxes or radios
			 'multiple' => true,
			 'expanded' => true,
		))
			->add('otherNotes')
			->add('save', SubmitType::class, array('label' => 'Save Volunteer'))
		;
		
		$builder->add('volunteerSessions', CollectionType::class, array(
            'entry_type' => VolunteerSessionType::class,
            'allow_add' => true,
			'by_reference' => false,
			'allow_delete' => true,
        ));

    }
    
	public function configureOptions(OptionsResolver $resolver)
	{
	    $resolver->setDefaults(array(
	        'data_class' => 'AppBundle\Entity\DonorVolunteer',
 	   ));
	}
}

?>
