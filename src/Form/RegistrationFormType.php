<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type as FormTypes;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('deputyFirstName', FormTypes\TextType::class, [
                'attr' => [
                    'class' => 'govuk-input govuk-!-width-one',
                    ],
                ])
            ->add('deputyLastName', null, [
                'attr' => [
                    'class' => 'govuk-input govuk-!-width-one',
                    ],
                ])
            ->add('clientsFirstNames', null, [
                'attr' => [
                    'class' => 'govuk-input govuk-!-width-one',
                    ],
                ])
            ->add('clientsLastName', null, [
                'attr' => [
                    'class' => 'govuk-input govuk-!-width-one',
                    ],
                ])
            ->add('clientsCaseNumber', null, [
                'attr' => [
                    'class' => 'govuk-input govuk-!-width-one',
                    ],
                'constraints' => [
                    new Length([
                        'min' => 8,
                        'max' => 8,
                        'minMessage' => 'This is 8 characters long and usually starts with a 1 or 9',
                    ]),
                ],
                ])
            ->add('email', null, [
                'attr' => [
                    'class' => 'govuk-input govuk-!-width-one',
                    ],
                ])
            ->add('plainPassword', PasswordType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'mapped' => false,
                'attr' => [
                    'autocomplete' => 'new-password',
                    'class' => 'govuk-input govuk-!-width-one',
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a password',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Your password should be at least {{ limit }} characters',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
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
