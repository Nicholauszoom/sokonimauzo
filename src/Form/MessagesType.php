<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Building;
use App\Entity\Classroom;
use App\Entity\Infrastructure;
use App\Entity\Messages;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Security;

class MessagesType extends AbstractType
{
    
    public function __construct(
        private Security $security
    ){
    }



    public function buildForm( FormBuilderInterface $builder, array $options): void
    {
        $user = $this->security->getUser();
          
   
        
        $builder
//            ->add('title')
//            ->add('description')
//            ->add('imagePath')
//            ->add('infrastructure')
//            ->add('classroom')
//            ->add('building')
//
//



            ->add('title', TextType::class, [
                // 'label' => ['title',
                //             'class'=>'col-sm-3 text-end control-label col-form-label'
                // ],
                'attr' => [
                    'autocomplete' => 'title',
                    'class' => 'form-control border-0',
                    'placeholder' => 'eg. title'
                ],
            ])

            ->add('description', TextareaType::class, [
                // 'label' => ['Description',
                              
                //             'class'=>'col-sm-3 text-end control-label col-form-label'
                // ],
                'attr' => [
                    'autocomplete' => 'description',
                    'class' => 'form-control border-0',
                    
                    'placeholder' => 'eg.The description'
                ],
            ])

         
            ->add('imagePath',FileType::class, array('data_class' => null), [

                    // 'label' => ['image upload',
                    //             'class'=>'col-md-3'
                    // ],
                    'label' => 'Upload file (max size 10MB)',
                    'attr'=>array(
                        'class'=>'custom-file-input',
                        'required'=>false,
                        'maxSize' => '10M',
                        'mapped'=>false
                        

                    ),

                ]
            )

            // ->add('cameraImage', TextType::class, [
            //     'mapped' => false,
            //     'required' => false,
            // ])

//             ->add('infrastructure', EntityType::class, [

//                 // looks for choices from this entity
//                 'class' => Infrastructure::class,
//                 // uses the User.username property as the visible option string
//                 // 'choice_label' => 'name',
// //                 'multiple' => true,
//                  'expanded' => true,
//                 'choice_label' => function ($infrastructure) {
//                     return $infrastructure->getName();
//                 }
//                 // used to render a select box, check boxes or radios
//                 // 'multiple' => true,
//                 // 'expanded' => true,

//             ])


            // ->add('classroom', EntityType::class, [

            //     // looks for choices from this entity
            //     'class' => Classroom::class,
            //     // uses the User.username property as the visible option string
            //     // 'choice_label' => 'name',
            //     'choice_label' => function ($classroom) {
            //         return $classroom->getName();
            //     }
            //     // used to render a select box, check boxes or radios
            //     // 'multiple' => true,
            //     // 'expanded' => true,

            // ])

            // ->add('building', EntityType::class, [

            //     // looks for choices from this entity
            //     'class' => Building::class,
            //     // uses the User.username property as the visible option string
            //     // 'choice_label' => 'name',
            //     'choice_label' => function ($building) {
            //         return $building->getName();
            //     }
            //     // used to render a select box, check boxes or radios
            //     // 'multiple' => true,
            //     // 'expanded' => true,

            // ])
            ->add('time', DateType::class, [

                'widget' => 'single_text',
                // this is actually the default format for single_text
                'format' => 'yyyy-MM-dd',
    
            ])


            // ->add('user', EntityType::class, [

            //     'class' => User::class,
               
               
            //     'choice_label' => function ($user) {
                   
            //      return $user = $this->security->getUser();
                
            //     }
            // ])

            // ->add('user', HiddenType::class, [
            //     'class' => User::class,
            //     // 'choice_label' => 'firstName',
            //     'data' => $user->getId(), // Set the default data to the current user
            // ])

            // ->add('user', TextType::class, [
            //     // 'class' => User::class,
            //     'data' => $user->getId(),
            // ])
               

            ->add('user', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'firstName', // or any other property you want to use as the label
                'data' => $user, // assuming $user is an instance of User entity
            ])
            // ->add('longitude', TextType::class, [
            //     'attr' => [
            //         'id' => 'longitude'
            //     ]
            // ])
            // ->add('latitude', TextType::class, [
            //     'attr' => [
            //         'id' => 'latitude'
            //     ]
            // ])
         
            ->add('longitude', HiddenType::class)
            ->add('latitude', HiddenType::class)

 
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Messages::class,
        ]);
    }
}
