<?php

namespace App\Form;

use App\Entity\Message;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotNull;

class MessageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                "constraints" => [
                    new NotNull([
                        "message" => "ne doit pas être null"
                    ])
                ]
            ])
            ->add('mail', EmailType::class, [
                'constraints' => [
                    new Email(["message" => "saisir un mail correcte"]),
                    new NotNull(["message" => "doit pas être nul"])
                ]
            ])
            ->add('message', TextareaType::class, [
                'constraints' => [
                    new NotNull(["message" => "doit pas être nul"])
                ]
            ])
            ->add('envoyer', SubmitType::class, [])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Message::class,
        ]);
    }
}
