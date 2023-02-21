<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;

class SpreadsheetUploadFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            // filetype class represents a file input in the form
            ->add('file', FileType::class, [
                'attr' => [
                    'class' => 'govuk-!-width-one-half',
                ],
            ])
            ->add('submit', SubmitType::class, [
                'attr' => [
                    'class' => 'govuk-button--secondary',
                ],
            ]);
    }
}
