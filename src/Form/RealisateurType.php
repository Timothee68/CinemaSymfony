<?php

namespace App\Form;

use App\Entity\Realisateur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class RealisateurType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                "attr" => ['class' => "form-control"]
                ])

            ->add('prenom', TextType::class, [
                "attr" => ['class' => "form-control"]
                ])

            ->add('dateNaissance', DateType::class, [
                'widget' => 'single_text',
                "attr" => ['class' => "form-control"]
                ])

            ->add('sexe', TextType::class, [
                "attr" => ['class' => "form-control"],
                "constraints" => [
                    new Length([
                        'max' => 5,
                        'maxMessage' => 'Le sexe est définie par Homme ou Femme / max 5 caractères ',
                    ])
                ]
                ])

            ->add('imageRealisateur', FileType::class ,[
                'label' => 'Photo du réalisateur',
                "attr" => [ 'class' => "form-control"],
                'mapped' => false,
                'required' => false,
                    'constraints' => [
                        new File([
                        'maxSize' => '10254k',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/webp',
                        ],
                        'mimeTypesMessage' => 'Please upload a valid image',
                        ]),
                    ]
                ])

            ->add('biographie', TextareaType::class ,[
                "attr" => [ 'class' => "form-control"]
                ])
            
            ->add('origine', TextType::class, [
                "attr" => ['class' => "form-control"]
                ])
                
            ->add('submit',SubmitType::class, [
                "attr" => ['class' => "form-control bg-primary"]
                ])
            ;
        }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Realisateur::class,
        ]);
    }
}
