<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotNull;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'constraints' => [
                    new Email(["message" => "saisir obligatoirement un mail"]),
                    new NotNull(["message" => "doit pas être nul"])
                ]
            ])
            ->add('password', PasswordType::class, [
                'constraints' => [
                    new NotNull(["message" => "mot de passe ne doit pas être nul"]),
                    new Length(null, 5, 20, null, null, null, "mot de passe trop court", "mot de passe trop long"),
                ]
            ])
            ->add('nomComplet', TextType::class, [
                'constraints' => [
                    new NotNull(["message" => "doit pas être nul"])
                ]
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
            'data_class' => User::class,
        ]);
    }
}
