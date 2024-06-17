<?php

namespace App\Form;

use App\Entity\Serie;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Validator\Constraints\NotBlank;

class SerieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Title'
            ])
            ->add('overview', TextareaType::class, [
                'required' => false
            ])
            ->add('status', ChoiceType::class, [
                'choices' => [
                    'Canceled' => 'canceled',
                    'Ended' => 'ended',
                    'Returning' => 'returning'
                ],
                'attr' => [
                    'class' => "machin"
                ],
            ])
            ->add('vote')
            ->add('popularity')
            ->add('genres', ChoiceType::class, [
                'choices' => [
                    'Western' => 'western',
                    'Comedy' => 'comedy',
                    'Romance' => 'romance',
                    'SF' => 'sf',
                    'Fantasy' => 'fantasy',
                    'Action' => 'action'
                ],
                'expanded' => true
            ])
            ->add('firstAirDate', DateType::class,
                ['widget' => 'single_text'])
            ->add('lastAirDate', DateType::class, [
                'widget' => 'single_text'])
            ->add('backdrop')
            ->add('poster', FileType::class, [
                'mapped' => false,
                'constraints' => [
                    new Image(
                        [
                            'maxSize' => '10000k',
                            'mimeTypesMessage' => 'Image format not allowed !',
                            'maxSizeMessage' => 'The file is too large !'
                        ]
                    )
                ]
            ])
            ->add('tmdbId');
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Serie::class,
            'required' => false,

        ]);
    }
}
