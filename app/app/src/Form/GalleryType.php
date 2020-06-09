<?php
namespace App\Form;

use App\Entity\Gallery;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class GalleryType
 * @package App\Form
 */
class GalleryType extends AbstractType
{
    /**
     * Builds the form.
     *
     * This method is called for each type in the hierarchy starting from the
     * top most type. Type extensions can further modify the form.
     *
     * @see FormTypeExtensionInterface::buildForm()
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add(
            'name_gallery',
            TextType::class,
            [
                'label'=> 'label_title',
                'required' => true,
                'attr' => ['max_length' => 64]
            ]
        );


    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
       $resolver->setDefaults(['data_class' => Gallery::class ]);
    }

    /**
     * Returns the prefix.
     *
     * @return string
     */
    public function getBlockPrefix(): string
    {
        return 'gallery';
    }
}


?>