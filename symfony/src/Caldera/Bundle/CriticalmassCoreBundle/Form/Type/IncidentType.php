<?php

namespace Caldera\Bundle\CriticalmassCoreBundle\Form\Type;

use Caldera\Bundle\CalderaBundle\Entity\Incident;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;

class IncidentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', 'text', array('required' => false))
            ->add('description', 'textarea', array('required' => false))
            ->add('polyline', 'hidden')
            ->add('geometryType', 'hidden')
            ->add('incidentType', ChoiceType::class,
                [
                    'choices'  => [
                        0 => Incident::INCIDENT_RAGE,
                        1 => Incident::INCIDENT_DANGER,
                        2 => Incident::INCIDENT_ROADWORKS
                    ]
                ]
            )
            ->add('visibleFrom', 'date')
            ->add('visibleTo', 'date')
            ->add('expires', 'checkbox');
    }

    public function getName()
    {
        return 'incident';
    }
}