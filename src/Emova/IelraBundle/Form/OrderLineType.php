<?php

namespace Emova\IelraBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OrderLineType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('reference',TextType::class, array('attr' => array('class' => 'form-control')))
            ->add('addressLivraison',TextType::class, array('attr' => array('class' => 'form-control')))
            ->add('designation',TextType::class, array('attr' => array('class' => 'form-control')))
            ->add('quantite', IntegerType::class, array('attr' => array('class' => 'form-control')))
            ->add('prixUnitaire', MoneyType::class,   array('attr' => array('class' => 'form-control')))
            ->add('tVA',MoneyType::class, array('attr' => array('class' => 'form-control')))
            ->add('prixTTC',MoneyType::class, array('attr' => array('class' => 'form-control')))
            ->add('fraisLivraison',MoneyType::class, array('attr' => array('class' => 'form-control')))
            ->add('numeroCommande', TextType::class, array('attr' => array('class' => 'form-control')))
            ->add('dateFacturation', DateType::class, array(
                'widget' => 'single_text',
                'html5' => false,
                // this is actually the default format for single_text
                'format' => 'dd-MM-yyyy',
                'placeholder' => 'jj-mm-aaaa',
                'attr' => array('class' => 'form-control')
            ))
            ->add('numeroFacture',TextType::class, array('attr' => array('class' => 'form-control')))
            ->add('devise',TextType::class, array('attr' => array('class' => 'form-control')))
            ->add('email', TextType::class, array('attr' => array('class' => 'form-control')))
            ->add('statut', TextType::class, array('attr' => array('class' => 'form-control')));
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Emova\IelraBundle\Entity\OrderLine'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'emova_ielrabundle_orderline';
    }


}
