<?php
namespace App\Controller;

use App\Entity\Gallery;
use App\Repository\GalleryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class GalleryController
 *
 * @package App\Controller
 * @Route("/galleries")
 */
class GalleryController extends AbstractController{
    /**
     * @param GalleryRepository $galleryRepository
     * @return Response
     * @Route(
     *     "/",
     *     name="galleries_index",
     *     methods={"GET"}
     * )
     */

    public function index(GalleryRepository $galleryRepository):Response{
        return $this->render(
            'project/gallery/index.html.twig',
            ['galleries' => $galleryRepository->findAll()]
        );
    }

    /**
     * @param Gallery $gallery
     * @return Response
     * @Route(
     *     "/{id}",
     *     name="gallery_show",
     *     methods={"GET"},
     *     requirements={"id": "[1-9]\d*"},
     * )
     */

    public function show(gallery $gallery):Response{
        return $this->render(
            'project/gallery/show.html.twig',
            ['gallery'=>$gallery]

        );
    }
}
