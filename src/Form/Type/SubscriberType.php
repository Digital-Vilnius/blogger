<?php

namespace App\Form\Type;

use App\Entity\Blog;
use App\Entity\Subscriber;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;

class SubscriberType extends AbstractType
{
    private $translator;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class, [
                'label' => $this->translator->trans('email')
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
            'data_class' => Subscriber::class,
            'isAdmin' => true
        ]);
    }
}