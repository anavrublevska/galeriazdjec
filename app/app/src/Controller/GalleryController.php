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
     * Index of gallery.
     *
     * @param Request            $request
     * @param GalleryRepository  $galleryRepository
     * @param PaginatorInterface $paginator
     *
     * @return Response
     *
     * @Route(
     *     "/",
     *     name="galleries_index",
     *     methods={"GET"},
     * )
     */
    public function index(Request $request, GalleryRepository $galleryRepository, PaginatorInterface $paginator): Response
    {
        $pagination = $paginator->paginate(
            $galleryRepository->queryAll(),
            $request->query->getInt('page', 1),
            GalleryRepository::PAGINATOR_ITEMS_PER_PAGE
        );

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
     * @param Request           $request
     * @param GalleryRepository $galleryRepository
     *
     * @return Response
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     *
     * @Route(
     *     "/create",
     *     methods={"GET", "POST"},
     *     name="gallery_create",
     * )
     */
    public function create(Request $request, GalleryRepository $galleryRepository): Response
    {
        $gallery = new Gallery();
        $form = $this->createForm(GalleryType::class, $gallery);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $galleryRepository->save($gallery);

            $this->addFlash('success', 'Yeeeep! You have got a new gallery!');

            return $this->redirectToRoute('galleries_index');
        }

        return $this->render(
            'project/gallery/create.html.twig',
            ['form' => $form->createView()]
        );
    }

    /**
     * Edit gallery.
     *
     * @param Request           $request
     * @param Gallery           $gallery
     * @param GalleryRepository $galleryRepository
     *
     * @return Response
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     *
     * @Route(
     *     "/{id}/edit",
     *     methods={"GET", "PUT"},
     *     requirements={"id": "[1-9]\d*"},
     *     name="gallery_edit",
     * )
     */
    public function edit(Request $request, Gallery $gallery, GalleryRepository $galleryRepository): Response
    {
        $form = $this->createForm(GalleryType::class, $gallery, ['method' => 'PUT']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $galleryRepository->save($gallery);

            $this->addFlash('info', 'Wow you did a great edit!');

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
     * @param Request           $request
     * @param Gallery           $gallery
     * @param GalleryRepository $galleryRepository
     *
     * @return Response
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     *
     * @Route(
     *     "/{id}/delete",
     *     methods={"GET", "DELETE"},
     *     requirements={"id": "[1-9]\d*"},
     *     name="gallery_delete",
     * )
     */
    public function delete(Request $request, Gallery $gallery, GalleryRepository $galleryRepository): Response
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
            $galleryRepository->delete($gallery);
            $this->addFlash('warning', 'Oh no you deleted a gallery');

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
