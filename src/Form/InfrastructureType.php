<?php

namespace App\Form;

use App\Entity\Infrastructure;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Entity\Categorie;

use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
// https://symfony.com/doc/current/reference/constraints/Image.html
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class InfrastructureType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom', TextType::class, [
                'label' => 'Nom',
                'attr' => [
                    'placeholder' => 'Ex : Pizza et Ravioli'
                ],
            ])
            ->add('rue', TextType::class, [
                'label' => 'Rue',
                'attr' => [
                    'placeholder' => 'Ex : 3 Rue de la Ville'
                ],
            ])
            ->add('codePostal', TextType::class, [
                'label' => 'Code postal',
                'attr' => [
                    'placeholder' => 'Ex : 13001'
                ],
            ])
            ->add('arrondissement', TextType::class, [
                'label' => 'Arrondissement',
                'attr' => [
                    'placeholder' => 'Veuillez respecter le format : 1er, 2eme, etc'
                ],
            ])
            ->add('ville', TextType::class, [
                'label' => 'Ville',
                'attr' => [
                    'placeholder' => 'Marseille (bien sûr !)'
                ],
            ])
            ->add('latitude', NumberType::class,[
                'scale'=> 10,
                'attr' => [
                    'placeholder' => 'Ne pas remplir'
                ],
                
            ])
            ->add('longitude', NumberType::class,[
                'scale'=> 10,
                'attr' => [
                    'placeholder' => 'Ne pas remplir'
                ],
            ])
            ->add('horaires', null, [
                'label' => 'Horaires',
                'attr' => [
                    'placeholder' => 'Ex : Ouvert du lundi au vendredi, 09:00 - 17:30'
                ],
            ])
            ->add('telephone', null, [
                'label' => 'Telephone',
                'attr' => [
                    'placeholder' => 'Ex : +33 6 90 90 09 09'
                ],
            ])
            ->add('email', null, [
                'label' => 'Email',
                'attr' => [
                    'placeholder' => 'Ex : adresse@mail.fr'
                ],
            ])
            ->add('siteWeb', null, [
                'label' => 'Site Web',
                'attr' => [
                    'placeholder' => 'Veuillez respecter le format : https://'
                ],
            ])
            ->add('image', FileType::class,[
                'label' => 'Choisissez une image',

                'attr' => [
                    'placeholder' => 'Image au format .jpg ou .png'
                ],
                // unmapped means that this field is not associated to any entity property
                'mapped' => false,

                // make it optional so you don't have to re-upload the PDF file
                // every time you edit the Product details
                'required' => false,

                // unmapped fields can't define their validation using annotations
                // in the associated entity, so you can use the PHP constraint classes
                'constraints' => [
                    new Image([
                        'maxSize' => '10240k',
                        // on peut ajouter des contraintes sur les tailles en pixel
                        'mimeTypes' => [
                            'image/png',
                            'image/jpeg',
                        ],
                        'mimeTypesMessage' => 'Veuillez choisir une image au format jpeg ou png',

                    ])
                ],
            ])

            // ->add('utilisateur')
            // => Ajouter code pour indiquer id de l'utilisateur connecté

            ->add('categories', EntityType::class, [
                'label' => 'Catégories',
                'class' => Categorie::class,
                'choice_label' => 'nom',
                'multiple' => true,
                'expanded' => true,
            ])
            // => Ajouter code pour choix des categorie
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Infrastructure::class,
        ]);
    }
}
