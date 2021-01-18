<?php

namespace App\Form;

use App\Entity\RealEstate;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\RangeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RealEstateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
            ->add('description')
            ->add('surface', RangeType::class, ['attr' => [
                'min' => 10,
                'max' => 400,
                'class' => 'p-0',
            ],
                ])
            ->add('price')
            // on peut chnager le type du champ avec le 2nd paramètre
            ->add('rooms', ChoiceType::class, [
                'choices' => [
                    'Studio' => 1,
                    'T2' => 2,
                    'T3' => 3,
                    'T4' => 4,
                    'T5' => 5,
                ],
            ])
            ->add('type', ChoiceType::class, [
                'choices' => [
                    'Maison' => 'maison',
                    'Appartement' => 'appartement'
                ],
                'expanded' => true
            ])
            ->add('sold')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => RealEstate::class,
        ]);
    }
}
