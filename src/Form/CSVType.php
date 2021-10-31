<?php

namespace App\Form;

use Doctrine\DBAL\Types\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\FormTypeInterface;
use App\Entity\Admin;


class CSVType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('choice', ChoiceType::class, [
                "label" => "Type de fusion",
                "choices" => [
                    "Sequentiel" => "Sequentiel",
                    "Entrelacer" => "Entralacer"
                ]
            ])
            ->add('mergeFileName', TextType::class, [
                "label" => "Nom du fichier, de la fusion",
                "constraints" => [
                    new NotBlank()
                ]
            ])
            ->add('file0', FileType::class, [
                "label" => "Premier fichier .csv",
                "constraints" => [
                    new NotBlank(),
                    new File([
                        'mimeTypes' => [
                            'text/x-csv',
                            'text/csv',
                            'application/x-csv',
                            'application/csv',],
                            "mimeTypesMessage" => "Fichier .csv uniquement !"
                    ])
                ]
            ])
            ->add('file1', FileType::class, [
                "label" => "Second fichier .csv",
                "constraints" => [
                    new NotBlank(),
                    new File([
                        'mimeTypes' => [
                            'text/x-csv',
                            'text/csv',
                            'application/x-csv',
                            'application/csv',],
                            "mimeTypesMessage" => "Fichier .csv uniquement !"
                    ])
                ]
            ])
            ->setMethod("POST")
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}