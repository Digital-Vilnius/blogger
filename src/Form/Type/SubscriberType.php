<?php

namespace App\Form\Type;

use App\Entity\Application;
use App\Entity\Subscriber;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
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
            ->add('emailNotification', CheckboxType::class, [
                'label' => $this->translator->trans('email'),
                'attr' => ['class' => 'switch'],
            ])
            ->add('smsNotification', CheckboxType::class, [
                'label' => $this->translator->trans('sms'),
                'attr' => ['class' => 'switch'],
            ])
            ->add('email', TextType::class, [
                'label' => $this->translator->trans('email'),
            ])
            ->add('application', EntityType::class, [
                'label' => $this->translator->trans('application'),
                'class' => Application::class
            ])
            ->add('phone', TextType::class, [
                'label' => $this->translator->trans('phone'),
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Subscriber::class,
        ]);
    }
}