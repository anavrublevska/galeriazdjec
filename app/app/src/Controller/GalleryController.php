<?php
/**
 * Controller.
 */
namespace App\Controller;

use App\Entity\Gallery;
use App\Entity\Photo;
use App\Form\GalleryType;
use App\Repository\GalleryRepository;
use App\Repository\PhotoRepository;
use App\Service\GalleryService;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class GalleryController.
 *
 * @Route("/galleries")
 */

class GalleryController extends AbstractController
{
    /**
     * Category service.
     *
     * @var GalleryService
     */
    private $galleryService;

    /**
     * GalleryController constructor.
     * @param GalleryService $galleryService
     *
     * @var GalleryService
     */
    public function __construct(GalleryService $galleryService)
    {
        $this->galleryService = $galleryService;
    }

    /**
     * @param Request $request
     *
     * @return Response
     *
     * @Route(
     *     "/",
     *     name="galleries_index",
     *     methods={"GET"},
     * )
     */
    public function index(Request $request): Response
    {
        $page = $request->query->getInt('page', 1);
        $pagination = $this->galleryService->createPaginatedList($page);

        return $this->render(
            'project/gallery/index.html.twig',
            ['pagination' => $pagination]
        );
    }

    /**
     * Gallery show.
     *
     * @param Gallery $gallery
     *
     * @return Response
     *
     * @Route(
     *     "/{id}",
     *     name="gallery_show",
     *     methods={"GET"},
     *     requirements={"id": "[1-9]\d*"},
     * )
     */
    public function show(Gallery $gallery): Response
    {
        return $this->render(
            'project/gallery/show.html.twig',
            ['gallery' => $gallery]
        );
    }

    /**
     * Create gallery.
     *
     * @param Request $request
     *
     * @return Response
     *
     * @throws ORMException
     * @throws OptimisticLockException
     *
     * @Route(
     *     "/create",
     *     methods={"GET", "POST"},
     *     name="gallery_create",
     * )
     */
    public function create(Request $request): Response
    {
        $gallery = new Gallery();
        $form = $this->createForm(GalleryType::class, $gallery);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->galleryService->save($gallery);
            $this->addFlash('success', 'new_gallery_added');

            return $this->redirectToRoute('galleries_index');
        }

        return $this->render(
            'project/gallery/create.html.twig',
            ['form' => $form->createView()]
        );
    }

    /**
     * Edit.
     *
     * @param Request $request
     * @param Gallery $gallery
     *
     * @return Response
     *
     * @throws ORMException
     * @throws OptimisticLockException
     *
     * @Route(
     *     "/{id}/edit",
     *     methods={"GET", "PUT"},
     *     requirements={"id": "[1-9]\d*"},
     *     name="gallery_edit",
     * )
     */
    public function edit(Request $request, Gallery $gallery): Response
    {
        $form = $this->createForm(GalleryType::class, $gallery, ['method' => 'PUT']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->galleryService->save($gallery);

            $this->addFlash('info', 'edit_completed');

            return $this->redirectToRoute('galleries_index');
        }

        return $this->render(
            'project/gallery/edit.html.twig',
            [
                'form' => $form->createView(),
                'gallery' => $gallery,
            ]
        );
    }

    /**
     * Delete gallery.
     *
     * @param Request $request
     * @param Gallery $gallery
     *
     * @return Response
     *
     * @throws ORMException
     * @throws OptimisticLockException
     *
     * @Route(
     *     "/{id}/delete",
     *     methods={"GET", "DELETE"},
     *     requirements={"id": "[1-9]\d*"},
     *     name="gallery_delete",
     * )
     */
    public function delete(Request $request, Gallery $gallery): Response
    {
        if ($gallery->getPhotos()->count()) {
            $this->addFlash('warning', 'message_gallery_contains_photos');

            return $this->redirectToRoute('galleries_index');
        }
        $form = $this->createForm(FormType::class, $gallery, ['method' => 'DELETE']);
        $form->handleRequest($request);

        if ($request->isMethod('DELETE') && !$form->isSubmitted()) {
            $form->submit($request->request->get($form->getName()));
        }
        if ($form->isSubmitted() && $form->isValid()) {
            $this->galleryService->delete($gallery);
            $this->addFlash('warning', 'you_have_deleted');

            return $this->redirectToRoute('galleries_index');
        }

        return $this->render(
            'project/gallery/delete.html.twig',
            [
                'form' => $form->createView(),
                'gallery' => $gallery,
            ]
        );
    }
}
