<?php

namespace App\Form\Type;

use App\Entity\Post;
use App\Filter\TagsFilter;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;

class TagsFilterType extends AbstractType
{
    private $translator;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('posts', EntityType::class, [
                'label' => $this->translator->trans('posts'),
                'class' => Post::class,
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
            'data_class' => TagsFilter::class,
            'isAdmin' => true
        ]);
    }
}