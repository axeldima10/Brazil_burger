<?php

namespace App\Form;

use App\Entity\Burger;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\All;
use Symfony\Component\Validator\Constraints\Count;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotNull;

class BurgerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('libelle', TextType::class, [
                'constraints' => [
                    new NotNull(null, "ce champ est obligatoire")
                ]
            ])
            ->add('prix', MoneyType::class, [
                'constraints' => [
                    new Length(null, 3, 6, null, null, null, "montant trop court", "montant trop long"),
                ]
            ])
            ->add('disponibilite', ChoiceType::class, [
                'choices' => [
                    'disponible' => true,
                    'indisponible' => false
                ],
                'constraints' => [
                    new NotNull(null, "faites un choix")
                ],
                'attr' => [
                    "class" => 'form-control'
                ]
            ])
            ->add('categorie', HiddenType::class, [
                'mapped' => false
            ])
            ->add('description', TextareaType::class, [])
            ->add('images', FileType::class, [
                'help' => 'joindre une ou plusieurs images?',
                'required' => false,
                'multiple' => true,
                'mapped' => false,
                'constraints' => [
                    new Count(['max' => 3, "maxMessage" => "maximimum 3 images"]),
                    new All([
                        new File([
                            'maxSize' => '2048k',
                            'mimeTypes' => [
                                'image/jpeg',
                                'image/jpg',
                                'image/gif',
                                'image/png'
                            ],
                            'mimeTypesMessage' => 'choisir image en jpg gif jpeg png'
                        ])
                    ])
                ],
                'attr' => [
                    'accept' => '.jpg, .jpeg, .png'
                ],
            ])
            ->add('soumettre', SubmitType::class, [
                'attr' => [
                    'class' => "btn btn-primary",
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Burger::class,
        ]);
    }
}
