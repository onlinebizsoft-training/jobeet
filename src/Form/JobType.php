<?php


namespace App\Form;

use App\Entity\Category;
use App\Entity\Job;
use function Sodium\add;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;

class JobType extends AbstractType
{
    /**
     * {@inheritDoc}
     *
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('category', EntityType::class, [
                    'class' => Category::class,
                    'choice_label' => 'name',
                    'constraints' => [
                        new NotBlank()
                    ]
                ])
                ->add('type', ChoiceType::class, [
                    'choices' => array_combine(Job::TYPES, Job::TYPES),
                    'expanded' => true,
                    'constraints' => [
                        new NotBlank()
                    ]
                ])
                ->add('company', TextType::class, [
                    'constraints' => [
                        new NotBlank(),
                        new Length(['max' => 255])
                    ]
                ])
                ->add('logo', FileType::class, [
                    'required' => false,
                    'data_class' => null,
                    'constraints' => [
                        new Image()
                    ]
                ])
                ->add('url', UrlType::class, [
                    'required' => false,
                    'constraints' => [
                        new Length(['max' => 255])
                    ]
                ])
                ->add('position', TextType::class, [
                    'constraints' => [
                        new NotBlank(),
                        new Length(['max' => 255])
                    ]
                ])
                ->add('location', TextType::class, [
                    'constraints' => [
                        new NotBlank(),
                        new Length(['max' => 255])
                    ]
                ])
                ->add('description', TextareaType::class, [
                    'constraints' => [
                        new NotBlank()
                    ]
                ])
                ->add('howToApply', TextType::class, [
                    'constraints' => [
                        new NotBlank()
                    ]
                ])
                ->add('public', ChoiceType::class, [
                    'choices' => [
                        'Yes' => true,
                        'No' => false
                    ],
                    'constraints' => [
                        new NotNull()
                    ]
                ])
                ->add('activated', ChoiceType::class, [
                    'choices' => [
                        'Yes' => true,
                        'No' => false
                    ],
                    'constraints' => [
                        new NotNull()
                    ]
                ])
                ->add('email', EmailType::class, [
                    'constraints' => [
                        new NotBlank(),
                        new Email()
                    ]
                ]);
    }

    /**
     * {@inheritDoc}
     *
     * @param OptionsResolver $resolver
     */
    public function configureOption(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Job::class
        ]);
    }
}