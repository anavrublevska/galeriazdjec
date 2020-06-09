<?php
/**
 * Photo Controller.
 */
namespace App\Controller;

use App\Entity\Gallery;
use App\Entity\Photo;
use App\Form\PhotoType;
use App\Repository\PhotoRepository;
use App\Service\FileUploader;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

/**
 * Class PhotoController.
 *
 * @package App\Controller
 *
 * @Route("/photos")
 */
class PhotoController extends AbstractController
{
    /**
     * @var \App\Repository\PhotoRepository
     */
    private $photoRepository;
    /**
     * @var \App\Service\FileUploader
     */
    private $fileUploader;

    /**
     * PhotoController constructor.
     *
     * @param PhotoRepository $photoRepository
     * @param FileUploader $fileUploader
     */

    public function __construct(PhotoRepository $photoRepository, FileUploader $fileUploader)
    {
        $this->photoRepository = $photoRepository;
        $this->fileUploader = $fileUploader;
    }

    /**
     * Create.
     *
     * @param Request $request
     *
     * @return Response
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     *
     * @Route(
     *     "/create",
     *     name="photo_create",
     *     methods={"GET", "POST"}
     * )
     */
    public function create(Request $request): Response
    {
        $photo = new Photo();

        $form = $this->createForm(PhotoType::class, $photo);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $photoFilename = $form->get('file')->getData();
            $response = new Response();


            $a='a'.uniqid().'.'.$photoFilename->guessExtension();
//            try {
                $photoFilename->move(
                    $this->getParameter('photos_directory'),
                    $a
                );
//            } catch (FileException $e) {
                // ... handle exception if something happens during file upload
//            }
//            $photo->setTitle('Hello');
//            $photo->setDescription('lorem ipsum');
            $photo->setLink($a);


            $this->photoRepository->save($photo);
//            $response->setContent('<img src="/uploads/photos/'.$a.'">');
//            return $response;
            $this->addFlash('success', 'Yeeeep! You have got a new photoooo!');

            return $this->redirectToRoute('photos_index');

        }
        return $this->render(
            'project/photos/form.html.twig',
            ['form' => $form->createView()]
        );

    }
    /**
     * Index photos.
     *
     * @param Request            $request
     * @param PhotoRepository    $photoRepository
     * @param PaginatorInterface $paginator
     * @return Response
     *
     * @Route(
     *     "/",
     *     methods={"GET"},
     *     name="photos_index"
     * )
     */
    public function index(Request $request, PhotoRepository $photoRepository, PaginatorInterface $paginator): Response
    {
        $pagination = $paginator->paginate(
            $photoRepository->queryAll(),
            $request->query->getInt('page', 1),
            PhotoRepository::PAGINATOR_ITEMS_PER_PAGE
        );

        return $this->render(
            'project/photos/index.html.twig',
            ['pagination' => $pagination]
        );
    }
    /**
     * Show photo.
     *
     * @param Photo $photo
     * @return Response
     *
     * @Route(
     *     "/{id}",
     *     name="photo_show",
     *     methods={"GET"},
     *     requirements={"id": "[1-9]\d*"}
     * )
     */
    public function show(Photo $photo): Response
    {
        return $this->render(
            'project/photos/show.html.twig',
            ['photo' => $photo]
        );
    }

    /**
     * Edit action.
     *
     * @param Request $request
     * @param Photo $photo
     * @param PhotoRepository $photoRepository
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
     *     name="photo_edit",
     * )
     */
    public function edit(Request $request, Photo $photo, PhotoRepository $photoRepository): Response
    {
        $form = $this->createForm(PhotoType::class, $photo, ['method' => 'PUT']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $photoRepository->save($photo);

            $this->addFlash('warning', 'photo_updated_successfully');

            return $this->redirectToRoute('photos_index');
        }

        return $this->render(
            'project/photos/edit.html.twig',
            [
                'form' => $form->createView(),
                'photo' => $photo,
            ]
        );
    }

    /**
     * Delete photo.
     *
     * @param Request         $request
     * @param Photo           $photo
     * @param PhotoRepository $photoRepository
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
     *     name="photo_delete",
     * )
     */
    public function delete(Request $request, Photo $photo, PhotoRepository $photoRepository): Response
    {
        $form = $this->createForm(FormType::class, $photo, ['method' => 'DELETE']);
        $form->handleRequest($request);

        if ($request->isMethod('DELETE') && !$form->isSubmitted()) {
            $form->submit($request->request->get($form->getName()));
        }
        if ($form->isSubmitted() && $form->isValid()) {
            $photoRepository->delete($photo);
            $this->addFlash('success', 'photo_deleted_successfully');

            return $this->redirectToRoute('photos_index');
        }

        return $this->render(
            'project/photos/delete.html.twig',
            [
                'form' => $form->createView(),
                'photo' => $photo,
            ]
        );
    }


//
//    }/**
////     * @param Request $request
////     * @return Response
////     * @throws \Doctrine\ORM\ORMException
////     * @throws \Doctrine\ORM\OptimisticLockException
////     *
////     * @Route(
////     *     "/upload",
////     *     name="photos_upload1",
////     *     methods={"GET", "POST"}
////     * )
////     */
////
////    public function upload(Request $request): Response {
////
////        $photo = new Photo();
////        $form = $this->createForm(PhotoType::class, $photo);
////        $form->handleRequest($request);
////
////        $form->handleRequest($request);
////        if ($form->isSubmitted() && $form->isValid()) {
////
////            $photoFilename = $form->get('file')->getData();
////            $response = new Reponse();
////            $response->setContent($photoFilename);
////            return $response;
////        }
////
////
////
////        return $this->render(
////            'project/photos/form.html.twig',
////            ['form' => $form->createView()]
////        );


}

?>