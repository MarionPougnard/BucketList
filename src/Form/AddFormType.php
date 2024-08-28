<?php

namespace App\Form;

use App\Entity\Category;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;
use App\Entity\Wish;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class AddFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, ['required' => true, 'label' => 'Your idea'])
            ->add('description', TextareaType::class, ['required' => false, 'label' => 'Please describe it!'])
            ->add('author', TextType::class, [
                'required' => true,
                'label' => 'Your username',
    ])
            ->add('image', FileType::class, [
                'required' => false,
                'label' => 'Image',
                'mapped' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                        ],
                        'mimeTypesMessage' => 'Please upload a valid JPG or PNG document',
                    ])
                ],])
            ->add('category', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'name',
                'placeholder' => '-- Choose a category --',
                'required' => true,
                'label' => 'Category',
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Save',
            ])
        ;

        $builder->addEventListener(
            FormEvents::PRE_SET_DATA,
            function (FormEvent $event) {
                $wish = $event->getData();
                $form = $event->getForm();
                if($wish && $wish->getImage()) {
                    $form->add('deleteImage', CheckboxType::class, [
                        'required'=> false,
                        'mapped'=> false,
                    ]);
                }
            }
        );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Wish::class,
        ]);
    }
}
