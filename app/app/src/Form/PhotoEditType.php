<?php
/**
 * Edit photo form.
 */
namespace App\Form;

use App\Entity\Gallery;
use App\Entity\Photo;
use App\Form\DataTransformer\TagsDataTransformer;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class PhotoEditType
 */
class PhotoEditType extends AbstractType
{
    /**
     * data transformer.
     *
     * @var TagsDataTransformer
     */
    private $tagsDataTransformer;

    /**
     * PhotoType constructor.
     *
     * @param TagsDataTransformer $tagsDataTransformer
     */
    public function __construct(TagsDataTransformer $tagsDataTransformer)
    {
        $this->tagsDataTransformer = $tagsDataTransformer;
    }

    /**
     * Form builder.
     *
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'title',
                TextType::class,
                [
                    'label' => 'label_title',
                    'required' => true,
                ]
            )
            ->add(
                'description',
                TextType::class,
                [
                    'label' => 'label_description',
                    'required' => true,
                ]
            );
        $builder->add(
            'gallery',
            EntityType::class,
            [
                    'class' => Gallery::class,
                    'choice_label' => function ($gallery) {
                        return $gallery->getNameGallery();
                    },
                    'label' => 'label_gallery',
                    'placeholder' => 'label_none',
                    'required' => true,
                ]
        );
        $builder->add(
            'tags',
            TextType::class,
            [
                'label' => 'label_tags',
                'required' => false,
                'attr' => ['max_length' => 128],
            ]
        );
        $builder->get('tags')->addModelTransformer(
            $this->tagsDataTransformer
        );
    }

    /**
     * Configure options.
     *
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(['data_class' => Photo::class]);
    }

    /**
     * Prefix.
     *
     * @return string
     */
    public function getBlockPrefix(): string
    {
        return 'photo';
    }
}
