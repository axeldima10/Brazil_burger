<?php

namespace App\Form;

use App\Entity\Client;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotNull;

class ClientType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', TextType::class, [
                'help' => 'saisi de mail facultatif',
                'required' => false,
                'attr' => [
                    'placeholder' => 'saisi facultative',
                    'class' => 'text-danger'
                ],
                'constraints' => [
                    new Email([], "Veuillez saisir un mail")
                ],
            ])
            ->add('roles', HiddenType::class, [
                'mapped' => false
            ])
            ->add('password', PasswordType::class, [
                'required' => false,
                'mapped'=>false,
                'constraints' => [
                    new Length(null, 5, 20, null, null, null, "mot de passe trop court", "mot de passe trop long"),
                ],
                'attr' => [
                    'placeholder' => 'saisi facultative',
                    'class' => 'text-danger'
                ],
            ])
            ->add('nomComplet', TextType::class, [
                'constraints' => [
                    new NotNull(null, "nom requis")
                ],
                'attr' => [
                    'placeholder' => 'saisi obligatoire',
                    'class' => 'text-danger'
                ],
            ])
            ->add('telephone', IntegerType::class, [
                'help' => 'ex: 221773595596',
                'required' => true,
                'constraints' => [
                    new Length(null, 10, 22, null, null, null, "numero trop court", "numero trop long")
                ],
                'attr' => [
                    'placeholder' => 'saisi obligatoire',
                    'class' => 'text-danger'
                ],
            ])
            ->add('soumettre', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-primary'
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Client::class,
            'constraints' => new UniqueEntity(
                [
                    'fields' => ["email", "telephone"],
                    'message' => "mail ou téléphone existe déjà"
                ]
            )
        ]);
    }
}
