<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;

class DonationType extends AbstractType
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
			->add('amount', MoneyType::class, array(
				'required' => false,
				'label' => false,
				'currency' => 'USD',
				'grouping' => true,
				))
			->add('paymentType', ChoiceType::class, array(
				'label' => false,
				'choices'  => array(
				'' => null,
				'Cash' => 'Cash',
				'Check' => 'Check',
				'Credit Card' => 'Credit Card',
				'In Kind' => 'In Kind',
				'Online' => 'Online',
				'Other' => 'Other',
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
	        'data_class' => 'AppBundle\Entity\Donation',
 	   ));
	}
}

?>
