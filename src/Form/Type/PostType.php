<?php

namespace App\Form\Type;

use App\Entity\Blog;
use App\Entity\Category;
use App\Entity\Post;
use App\Entity\Tag;
use App\Entity\User;
use App\Form\DataTransformer\TagsArrayToStringTransformer;
use Doctrine\ORM\EntityManagerInterface;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
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
    private $entityManager;

    public function __construct(TranslatorInterface $translator, EntityManagerInterface $entityManager)
    {
        $this->translator = $translator;
        $this->entityManager = $entityManager;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, [
                'label' => $this->translator->trans('title')
            ])
            ->add('summary', CKEditorType::class, [
                'label' => $this->translator->trans('summary')
            ])
            ->add('content', CKEditorType::class, [
                'label' => $this->translator->trans('content')
            ])
            ->add('visible', CheckboxType::class, [
                'label' => $this->translator->trans('visible'),
                'attr' => ['class' => 'switch'],
            ])
            ->add('category', EntityType::class, [
                'label' => $this->translator->trans('category'),
                'class' => Category::class,
                'required' => false
            ])
            ->add('tags', TextType::class, [
                'label' => $this->translator->trans('tags'),
                'attr' => ['class' => 'tags-input']
            ]);

        if ($options['isAdmin']) {
            $builder->add('blog', EntityType::class, [
                'label' => $this->translator->trans('blog'),
                'class' => Blog::class
            ]);
        }

        $builder->get('tags')->addModelTransformer(new TagsArrayToStringTransformer($this->entityManager, $builder->getData()->getBlog()));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Post::class,
            'isAdmin' => true
        ]);
    }
}