<?php
/**
 * Tag controller.
 */

namespace App\Controller;

use App\Entity\Tag;
use App\Form\TagType;
use App\Repository\TagRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Knp\Component\Pager\PaginatorInterface;
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
     * Index tags.
     *
     * @param Request            $request
     * @param TagRepository      $tagRepository
     * @param PaginatorInterface $paginator
     *
     * @return Response
     *
     * @Route(
     *     "/",
     *     methods={"GET"},
     *     name="tags_index",
     * )
     */
    public function index(Request $request, TagRepository $tagRepository, PaginatorInterface $paginator): Response
    {
        $pagination = $paginator->paginate(
            $tagRepository->queryAll(),
            $request->query->getInt('page', 1),
            TagRepository::PAGINATOR_ITEMS_PER_PAGE
        );

        return $this->render(
            'project/tags/index.html.twig',
            ['pagination' => $pagination]
        );
    }


    /**
     * Create tag.
     *
     * @param Request       $request
     * @param TagRepository $tagRepository
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
     */
    public function create(Request $request, TagRepository $tagRepository): Response
    {
        $tag = new Tag();
        $form = $this->createForm(TagType::class, $tag);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $tagRepository->save($tag);
            $this->addFlash('success', 'Yeeeep! You have added a new tag!');

            return $this->redirectToRoute('tags_index');
        }

        return $this->render(
            'project/tags/create.html.twig',
            ['form' => $form->createView()]
        );
    }

    /**
     * @param Request       $request
     * @param Tag           $tag
     * @param TagRepository $tagRepository
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
     */
    public function edit(Request $request, Tag $tag, TagRepository $tagRepository): Response
    {
        $form = $this->createForm(TagType::class, $tag, ['method' => 'PUT']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $tagRepository->save($tag);

            $this->addFlash('info', 'Wow you did a great edit!');

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
     * @param Request       $request
     * @param Tag           $tag
     * @param TagRepository $tagRepository
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
     */
    public function delete(Request $request, Tag $tag, TagRepository $tagRepository): Response
    {
        $form = $this->createForm(FormType::class, $tag, ['method' => 'DELETE']);
        $form->handleRequest($request);

        if ($request->isMethod('DELETE') && !$form->isSubmitted()) {
            $form->submit($request->request->get($form->getName()));
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $tagRepository->delete($tag);
            $this->addFlash('warning', 'Oh no you deleted a tag');

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
