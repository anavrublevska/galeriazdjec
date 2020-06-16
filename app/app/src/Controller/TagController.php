<?php
/**
 * Tag controller.
 */

namespace App\Controller;

use App\Entity\Tag;
use App\Form\TagType;
use App\Repository\TagRepository;
use Doctrine\DBAL\Types\TextType;
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
     * @param TagRepository $tagRepository
     * @return Response
     *
     * @Route(
     *     "/",
     *     methods={"GET"},
     *     name="tags_index",
     * )
     */
    public function index(TagRepository $tagRepository): Response
    {
        return $this->render(
            'project/tags/index.html.twig',
            ['data' => $tagRepository->findAll()]
        );
    }

    /**
     * @param Request       $request
     * @param Tag           $tag
     * @param TagRepository $tagRepository
     *
     * @return Response
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
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

        if ($form->isSubmitted() && $form->isValid()){
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
     * @return Response
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
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

        if ($request->isMethod('DELETE') && !$form->isSubmitted()){
            $form->submit($request->request->get($form->getName()));
        }

        if ($form->isSubmitted() && $form->isValid()){
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


}

?>