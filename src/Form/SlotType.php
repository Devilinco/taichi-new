<?php

namespace App\Form;

use App\Entity\Location;
use App\Entity\Slot;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SlotType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('dayId', ChoiceType::class, [
                'choices' => $this->getDays(),
                'required' => true,
            ])
            ->add('startAt', TimeType::class, [
                'input'  => 'datetime',
                'widget' => 'single_text',
            ])
            ->add('endAt', TimeType::class, [
                'input'  => 'datetime',
                'widget' => 'single_text',
            ])
            ->add('level', ChoiceType::class, [
                'choices' => $this->getLevels(),
                'required' => true,
            ])
            ->add('location', EntityType::class, [
                'required' => true,
                'attr' => ['class' => 'text-capitalize'],
                'class' => Location::class,
                'query_builder' => function (EntityRepository  $cr) {
                    return $cr->createQueryBuilder('l')
                        ->orderBy('l.name');
                },
                'choice_label' => 'name'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Slot::class,
            'translation_domain' => 'security'
        ]);
    }

    private function getDays()
    {
        $choices = Slot::days;
        $output = [];
        foreach ($choices as $k => $v) {
            $output[$v] = $k;
        }
        return $output;
    }
    private function getLevels()
    {
        $choices = Slot::levels;
        $output = [];
        foreach ($choices as $k => $v) {
            $output[$v] = $k;
        }
        return $output;
    }
}