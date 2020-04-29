<?php

namespace App\Form\Type;

use App\Entity\Category;
use App\Entity\Tag;
use App\Filter\PostsFilter;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;

class PostsFilterType extends AbstractType
{
    private $translator;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('categories', EntityType::class, [
                'label' => $this->translator->trans('categories'),
                'class' => Category::class,
                'expanded' => true,
                'multiple' => true,
                'required' => false,
            ])
            ->add('tags', EntityType::class, [
                'label' => $this->translator->trans('tags'),
                'class' => Tag::class,
                'expanded' => true,
                'multiple' => true,
                'required' => false,
            ]);
    }

    public function getBlockPrefix()
    {
        return '';
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => PostsFilter::class,
            'isAdmin' => true
        ]);
    }
}