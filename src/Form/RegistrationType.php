<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class RegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('fullName',TextType::class,[
                'attr'=>[
                    'class'=>'form-control',
                    'minlength'=>'2',
                    'maxlength'=>'50'
                ],
                'label'=>'Nom / Prénom',
                'label_attr'=>[
                    'class'=>'form-label mt-4'
                ],
                'constraints'=>[
                    new Assert\Length(['min'=>2,'max'=>50]),
                    new Assert\NotBlank()
                ]
            ])
            ->add('pseudo',TextType::class,[
                'attr'=>[
                    'class'=>'form-control',
                    'minlength'=>'2',
                    'maxlength'=>'50'
                ],
                'required'=>false,
                'label'=>'Pseudo (Facultafitif)',
                'label_attr'=>[
                    'class'=>'form-label mt-4'
                ],
                'constraints'=>[
                    new Assert\Length(['min'=>2,'max'=>50]),
                    new Assert\NotBlank()
                ]
            ])
            ->add('email',EmailType::class,[
                'attr'=>[
                    'class'=>'form-control',
                    'minlength'=>'2',
                    'maxlength'=>'180'
                ],
                'label'=>'Adresse Email',
                'label_attr'=>[
                    'class'=>'form-label mt-4'
                ],
                'constraints'=>[
                    new Assert\Length(['min'=>2,'max'=>180]),
                    new Assert\Email(),
                    new Assert\NotBlank()
                ]
            ])
            ->add('plainPassword',RepeatedType::class,[
                'type'=>PasswordType::class,
                'first_options'=>[
                    'attr'=>[
                        'class'=>'form-control'
                    ],
                    'label'=>'Mot de passe',
                    'label_attr'=>[
                        'class'=>'form-label mt-4'
                    ]
                ],
                'second_options'=>[
                    'attr'=>[
                        'class'=>'form-control'
                    ],
                    'label'=>'Confirmation du mot de passe',
                    'label_attr'=>[
                        'class'=>'form-label mt-4'
                    ]
                ],
                'invalid_message'=>'Les mot de passe ne corresponde pas.'
            ])
            ->add('submit',SubmitType::class,[
                'attr'=>[
                    'class'=>'btn btn-primary mt-4'
                ],
                'label'=>'Créer mon compte'
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
