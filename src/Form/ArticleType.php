<?php

namespace App\Form;

use App\Entity\Continent;
use App\Entity\Article;
use App\EventSubscriber\Form\ArticleFormSubscriber;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Positive;

class ArticleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('titre', TextType::class, [
            	'constraints' => [
            		new NotBlank([
            		    'message' => "Le titre est obligatoire"
		            ])
	            ]
            ])
           
            ->add('description', TextareaType::class, [
	            'constraints' => [
		            new NotBlank([
                         'message' => "La description est obligatoire"
                     ])
                ]
            ])
            
            // le champ image est créé dans le souscripteur de formulaire
            //->add('slug')
	        
	        ->add('conti', EntityType::class, [
				'class' => Continent::class,
	            'choice_label' => 'name',
	            'placeholder' => '',
	            'constraints' => [
	            	new NotBlank([
	            	    'message' => 'La continent est obligatoire'
		            ])
	            ]
            ])
        ;

        // ajout d'un soucripteur de formulaire
	    $builder->addEventSubscriber( new ArticleFormSubscriber() );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
        ]);
    }
}