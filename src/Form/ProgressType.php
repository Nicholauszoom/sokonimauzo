<?php

namespace App\Form;

use App\Entity\Progress;
use App\Entity\Task;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProgressType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            // ->add('description')
            // ->add('imagePath')
            // // ->add('createdAt')
            // // ->add('updatedAt')
            // ->add('tasks')

   
    
    
            ->add('description', TextareaType::class, [
                // 'label' => ['title',
                //             'class'=>'peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6'
                // ],
                'attr' => [
                    'autocomplete' => 'description',
                    'class' => 'required form-control',
                     'placeholder' => 'eg.The description'
                ],
            ])
            ->add('tasks', EntityType::class, [

                // looks for choices from this entity
                'class' => Task::class,
                // uses the User.username property as the visible option string
                // 'choice_label' => 'name',
                'choice_label' => function ($tasks) {
                    return $tasks->getName();
                }
                // used to render a select box, check boxes or radios
                // 'multiple' => true,
                // 'expanded' => true,
        
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
        ;
    }

    

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Progress::class,
        ]);
    }
}
