<?php

namespace App\Form\Type;

use App\Entity\Notification;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;

class NotificationType extends AbstractType
{
    private $translator;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', CheckboxType::class, [
                'label' => false,
                'attr' => ['class' => 'switch'],
            ])
            ->add('sms', CheckboxType::class, [
                'label' => false,
                'attr' => ['class' => 'switch'],
            ])
            ->add('push', CheckboxType::class, [
                'label' => false,
                'attr' => ['class' => 'switch'],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Notification::class,
        ]);
    }
}