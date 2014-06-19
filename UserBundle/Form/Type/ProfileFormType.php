<?php

namespace Ars\UserBundle\Form\Type;

use Symfony\Component\Form\FormBuilderInterface;
use FOS\UserBundle\Form\Type\ProfileFormType as BaseType;

class ProfileFormType extends BaseType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        //add custom fields
        $builder
            ->add('firstname')
            ->add('lastname');

        //remove username
        $builder->remove('username');
    }

    public function getName()
    {
        return 'ars_user_profile';
    }

}