<?php
/**
 * Photo Controller.
 */
namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Photo;
use App\Form\CommentType;
use App\Form\PhotoEditType;
use App\Form\PhotoType;
use App\Repository\CommentRepository;
use App\Repository\PhotoRepository;
use App\Service\CommentService;
use App\Service\FileUploader;
use App\Service\PhotoService;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class PhotoController.
 *
 * @Route("/photos")
 */
class PhotoController extends AbstractController
{

    /**
     * @var FileUploader
     */
    private $fileUploader;
    /**
     * Photo service.
     *
     * @var PhotoService
     */
    private $photoService;


    /**
     * PhotoController constructor.
     *
     * @param PhotoService $photoService
     * @param FileUploader $fileUploader
     */
    public function __construct(PhotoService $photoService, FileUploader $fileUploader)
    {
        $this->photoService = $photoService;
        $this->fileUploader = $fileUploader;
    }


    /**
     * Create.
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
     *     name="photo_create",
     *     methods={"GET", "POST"}
     * )
     *
     * @IsGranted("ROLE_USER")
     */
    public function create(Request $request): Response
    {
        $photo = new Photo();

        $form = $this->createForm(PhotoType::class, $photo);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $photo->setAuthor($this->getUser());

            $photoFilename = $form->get('file')->getData();

            $a = 'a'.uniqid().'.'.$photoFilename->guessExtension();
            $photoFilename->move(
                $this->getParameter('photos_directory'),
                $a
            );
            $photo->setCreatedAt(new \DateTime());

            $photo->setLink($a);
            $this->photoService->save($photo);

            $this->addFlash('success', 'new_photo_added');

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
     * @param Request $request
     *
     * @return Response
     *
     * @Route(
     *     "/",
     *     methods={"GET"},
     *     name="photos_index"
     * )
     */
    public function index(Request $request): Response
    {
        $page = $request->query->getInt('page', 1);
        $pagination = $this->photoService->createPaginatedList($page);

        return $this->render(
            'project/photos/index.html.twig',
            ['pagination' => $pagination]
        );
    }

    /**
     * Show comments.
     *
     * @param Request           $request
     * @param Photo             $photo
     * @param CommentRepository $commentRepository
     *
     * @return Response
     *
     * @throws ORMException
     * @throws OptimisticLockException
     *
     * @Route(
     *     "/{id}",
     *     name="photo_show",
     *     methods={"GET", "POST", "PUT"},
     *     requirements={"id": "[1-9]\d*"}
     * )
     */
    public function show(Request $request, Photo $photo, CommentRepository $commentRepository): Response
    {
        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);
        $id = $photo->getId();
        if ($form->isSubmitted() && $form->isValid()) {
            $comment->setUser($this->getUser());
            $comment->setPhoto($photo);
            $commentRepository->save($comment);

            return $this->redirectToRoute('photo_show', ['id' => $id]);
        }


        return $this->render(
            'project/photos/show.html.twig',
            ['photo' => $photo, 'form' => $form->createView()]
        );
    }


    /**
     * Edit action.
     *
     * @param Request $request
     * @param Photo   $photo
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
     *     name="photo_edit",
     * )
     *
     * @IsGranted("ROLE_ADMIN")
     */
    public function edit(Request $request, Photo $photo): Response
    {
        $form = $this->createForm(PhotoEditType::class, $photo, ['method' => 'PUT']);
        $form->handleRequest($request);
        $id = $photo->getId();

        if ($form->isSubmitted() && $form->isValid()) {
            $this->photoService->save($photo);

            $this->addFlash('warning', 'edit_completed');

            return $this->redirectToRoute('photo_show', ['id' => $id]);
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
     * @throws ORMException
     * @throws OptimisticLockException
     *
     * @Route(
     *     "/{id}/delete",
     *     methods={"GET", "DELETE"},
     *     requirements={"id": "[1-9]\d*"},
     *     name="photo_delete",
     * )
     *
     * @IsGranted("ROLE_ADMIN")
     */
    public function delete(Request $request, Photo $photo, PhotoRepository $photoRepository): Response
    {
        $form = $this->createForm(FormType::class, $photo, ['method' => 'DELETE']);
        $form->handleRequest($request);

        if ($request->isMethod('DELETE') && !$form->isSubmitted()) {
            $form->submit($request->request->get($form->getName()));
        }
        if ($form->isSubmitted() && $form->isValid()) {
            $this->photoService->delete($photo);
            $this->addFlash('success', 'deleted_successfully');

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
}
