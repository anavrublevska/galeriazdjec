<?php
/**
 * Tag controller.
 */

namespace App\Controller;

use App\Entity\Tag;
use App\Form\TagType;
use App\Service\TagService;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class TagController.
 *
 * @Route("/tags")
 */
class TagController extends AbstractController
{
    /**
     * Tag service.
     *
     * @var TagService
     */
    private $tagService;

    /**
     * CategoryController constructor.
     *
     * @param TagService $tagService
     */
    public function __construct(TagService $tagService)
    {
        $this->tagService = $tagService;
    }

    /**
     * Index tags.
     *
     * @param Request $request
     *
     * @return Response
     *
     * @Route(
     *     "/",
     *     methods={"GET"},
     *     name="tags_index",
     * )
     *
     * @IsGranted("ROLE_ADMIN")
     */
    public function index(Request $request): Response
    {

        $page = $request->query->getInt('page', 1);
        $pagination = $this->tagService->createPaginatedList($page);

        return $this->render(
            'project/tags/index.html.twig',
            ['pagination' => $pagination]
        );
    }


    /**
     * Create tag.
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
     *     name="tag_create",
     * )
     *
     * @IsGranted("ROLE_ADMIN")
     */
    public function create(Request $request): Response
    {
        $tag = new Tag();
        $form = $this->createForm(TagType::class, $tag);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->tagService->save($tag);
            $this->addFlash('success', 'new_tag_added');

            return $this->redirectToRoute('tags_index');
        }

        return $this->render(
            'project/tags/create.html.twig',
            ['form' => $form->createView()]
        );
    }

    /**
     * @param Request $request
     * @param Tag     $tag
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
     *     name="tag_edit",
     * )
     *
     * @IsGranted("ROLE_ADMIN")
     */
    public function edit(Request $request, Tag $tag): Response
    {
        $form = $this->createForm(TagType::class, $tag, ['method' => 'PUT']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->tagService->save($tag);

            $this->addFlash('info', 'edit_completed');

            return $this->redirectToRoute('tags_index');
        }

        return $this->render(
            'project/tags/edit.html.twig',
            [
                'form' => $form->createView(),
                'tag' => $tag,
            ]
        );
    }

    /**
     * Tag delete.
     *
     * @param Request $request
     * @param Tag     $tag
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
     *     name="tag_delete",
     * )
     *
     * @IsGranted("ROLE_ADMIN")
     */
    public function delete(Request $request, Tag $tag): Response
    {
        $form = $this->createForm(FormType::class, $tag, ['method' => 'DELETE']);
        $form->handleRequest($request);

        if ($request->isMethod('DELETE') && !$form->isSubmitted()) {
            $form->submit($request->request->get($form->getName()));
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $this->tagService->delete($tag);
            $this->addFlash('warning', 'deleted_successfully');

            return $this->redirectToRoute('tags_index');
        }

        return $this->render(
            'project/tags/delete.html.twig',
            [
                'form' => $form->createView(),
                'tag' => $tag,
            ]
        );
    }

    /**
     * Filtr for tags.
     *
     * @param Tag $tag
     *
     * @return Response
     *
     * @Route(
     *     "/{id}/filtr",
     *     name="tag_filtration",
     *     requirements={"id": "[1-9]\d*"},
     *     methods={"GET"},
     * )
     */
    public function filtrate(Tag $tag): Response
    {
        return $this->render(
            'project/tags/filtration.html.twig',
            ['tag' => $tag]
        );
    }
}
