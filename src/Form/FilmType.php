<?php

namespace App\Form;

use App\Entity\Film;
use App\Entity\Genre;
use App\Entity\Realisateur;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;


class FilmType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options = []): void
    {
    
        $builder
            ->add('titreFilm', TextType::class, [
                "attr" => ['class' => "form-control"]
                ])

            ->add('anneeSortie', DateType::class, [
                'widget' => 'single_text',
                "attr" => ['class' => "form-control"]
                ])

            ->add('dureeMin' , IntegerType::class, [
                "attr" => ['class' => "form-control"]
                ])

            ->add('synopsis', TextareaType::class ,[
                "attr" => [ 'class' => "form-control"]
                ])

            ->add('note', IntegerType::class, [
                "attr" => ['class' => "form-control"]
                ])

            ->add('affiche', FileType::class ,[
                'label' => 'Affiche du film ',
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

            ->add('realisateur', EntityType::class, [
                'class' => Realisateur::class,
                'choice_label' => 'nom',
                "attr" => ['class' => "form-control"],
                'label' => 'Choisis le réalisateur associé au film',
            ])

            ->add('genres', EntityType::class, [
                'choice_label' => "libelle",
                'class' => Genre::class,
                'expanded' => true,
                'multiple' => true,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('g')
                        ->orderBy('g.libelle', 'ASC');
                },
                "attr" => ['class' => "form-control"],
                'label' => 'Choisis le genre associé au film',
            ])
           
            ->add('submit',SubmitType::class, [
                "attr" => ['class' => "form-control bg-primary"]
                ])
        ;
    }


    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Film::class,
        ]);
    }
}
