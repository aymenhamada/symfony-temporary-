<?php
namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;



class EditProfileType extends AbstractType{

    public function buildForm(FormBuilderInterface $builder, array $options){
        $builder->add("name", TextType::class)
                ->add("prename", TextType::class)
                ->add("picture", FileType::class, ["required" => false])
                ->add('birthday', BirthdayType::class)
                ->add("adresses", TextType::class, ["required" => false]);

    }


    public function configureOptions(OptionsResolver $resolver){
        $resolver->setDefaults(array(
            'data_class' => User::class,
        ));
    }
}