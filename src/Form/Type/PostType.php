<?php

namespace App\Form\Type;

use App\Entity\Blog;
use App\Entity\Post;
use App\Entity\Tag;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;

class PostType extends AbstractType
{
    private $translator;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, [
                'label' => $this->translator->trans('title')
            ])
            ->add('summary', TextareaType::class, [
                'label' => $this->translator->trans('summary')
            ])
            ->add('content', TextareaType::class, [
                'label' => $this->translator->trans('content')
            ])
            ->add('visible', CheckboxType::class, [
                'label' => $this->translator->trans('visible')
            ])
            ->add('tags', EntityType::class, [
                'label' => $this->translator->trans('tags'),
                'class' => Tag::class,
                'multiple' => true,
            ]);

        if ($options['isAdmin']) {
            $builder->add('blog', EntityType::class, [
                'label' => $this->translator->trans('blog'),
                'class' => Blog::class
            ]);
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Post::class,
            'isAdmin' => true
        ]);
    }
}