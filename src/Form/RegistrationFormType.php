<?php

namespace App\Form;

use App\Entity\Roles;
use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;


class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder

         ->add('firstName', TextType::class, [
            'label' => false,
            'attr' => [
                'autocomplete' => 'firstName',                     
                'class' => 'required form-control',
                'placeholder' => 'First Name'
            ],
        ])


         ->add('lastName', TextType::class, [
            'label' => false,
            'attr' => [
                'autocomplete' => 'lastName',                     
                'class' => 'required form-control',     
               'placeholder' => 'Last Name'
            ],
        ])

        ->add('email', TextType::class, [
            'label' => false,
            'attr' => [
                'autocomplete' => 'email',                     
                'class' => 'required form-control',
                'placeholder' => 'Email eg.ardhiuniversity@gmail.com'
            ],
        ])


        ->add('registrationNo', TextType::class, [
            'label' => false,
            'attr' => [
                'autocomplete' => 'registrationNo',                     
                'class' => 'required form-control',
                'placeholder' => 'registrationNo eg.251.../T.20..'
            ],
        ])
//         ->add('roles', EntityType::class, [

//             // looks for choices from this entity
//             'class' => Roles::class,
//             // uses the User.username property as the visible option string
//             // 'choice_label' => 'name',
// //                 'multiple' => true,
//              'expanded' => true,
//             'choice_label' => function ($roles) {
//                 return $roles->getName();
//             }
//             ])

            ->add('plainPassword', PasswordType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'label' => false,
                'mapped' => false,
                'attr' => [
                    'autocomplete' => 'new-password',                     
                    'class' => 'required form-control',
                    'placeholder' => 'Password'
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
           
            ->add('role', EntityType::class, [

                // looks for choices from this entity
                'class' => Roles::class,
                'multiple' => true,
                'expanded' => true,
                // uses the User.username property as the visible option string
                // 'choice_label' => 'name',
                'choice_label' => function ($role) {
                    return $role;

                }
                // used to render a select box, check boxes or radios
                
                // 'expanded' => true,
    
            ])


        // ->add('imagePath',FileType::class, array('data_class' => null), [

        //     // 'label' => ['upload image',
        //     //             'class'=>'block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer'
        //     // ],
        //         'attr'=>array(
        //        'class'=>'py-10',
        //        'required'=>false,
        //         'mapped'=>false
              
        //         ),
              
        //    ]
        //    )
           ;
            // ->add('coverFile', FileType::class, array('data_class' => null))

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}