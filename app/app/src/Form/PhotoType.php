<?php
/**
 * Photo type.
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
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Form\Extension\Core\Type\FileType;

/**
 * Class PhotoType
 */
class PhotoType extends AbstractType
{
    /**
     * Tags data transformer.
     *
     * @var TagsDataTransformer
     */
    private $tagsDataTransformer;

    /**
     * PhotoType constructor.
     * @param TagsDataTransformer $tagsDataTransformer
     */
    public function __construct(TagsDataTransformer $tagsDataTransformer)
    {
        $this->tagsDataTransformer = $tagsDataTransformer;
    }
    /**
     * Builds the form.
     *
     * This method is called for each type in the hierarchy starting from the
     * top most type. Type extensions can further modify the form.
     *
     * @see FormTypeExtensionInterface::buildForm()
     *
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options):void
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
            )
            ->add(
                'file',
                FileType::class,
                [
                'mapped' => false,
                'label' => 'label_photo',
                'required' => true,

                'constraints' => new Image(
                    [
                        'maxSize' => '4000k',
                        'mimeTypes' => [
                            'image/png',
                            'image/jpeg',
                            'image/pjpeg',
                            'image/jpeg',
                            'image/pjpeg',
                        ],
                        'mimeTypesMessage' => 'Please upload a valid JPG document',
                    ]
                ),
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
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(['data_class' => Photo::class]);
    }

    /**
     * @return string
     */
    public function getBlockPrefix(): string
    {
        return 'photo';
    }
}
