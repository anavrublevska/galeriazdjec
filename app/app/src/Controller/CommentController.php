<?php
/**
 * Controller.
 */
namespace App\Controller;

use App\Entity\Comment;
use App\Form\CommentType;
use App\Repository\CommentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\FormType;

/**
 * Class CommentController.
 *
 */
class CommentController extends AbstractController
{
    /**
     * Edit comment.
     *
     * @param Request           $request
     * @param Comment           $comment
     * @param CommentRepository $commentRepository
     *
     * @return Response
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     *
     * @Route(
     *     "/{photo_id}/comment/{id}/edit",
     *     methods={"GET", "PUT"},
     *     requirements={"id": "[1-9]\d*"},
     *     name="comment_edit",
     * )
     */
    public function edit(Request $request, Comment $comment, CommentRepository $commentRepository): Response
    {
        $form = $this->createForm(CommentType::class, $comment, ['method' => 'PUT']);
        $form->handleRequest($request);
        $id = $comment->getPhoto();
        $id_photo = $id->getId();

        if ($form->isSubmitted() && $form->isValid()) {
            $commentRepository->save($comment);
            $this->addFlash('info', 'Wow you did a great edit!');

            return $this->redirectToRoute('photo_show', ['id'=> $id_photo]);
        }
        return $this->render(
            'project/comments/edit.html.twig',
            [
                'form' => $form->createView(),
                'comment' => $comment,
            ]
        );
    }

    /**
     * Delete comment.
     *
     * @param Request           $request
     * @param Comment           $comment
     * @param CommentRepository $commentRepository
     *
     * @return Response
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     *
     * @Route(
     *     "/{photo_id}/comment/{id}/delete",
     *     methods={"GET", "DELETE"},
     *     requirements={"id": "[1-9]\d*"},
     *     name="comment_delete",
     * )
     */
    public function delete(Request $request, Comment $comment, CommentRepository $commentRepository): Response
    {
        $form = $this->createForm(FormType::class, $comment, ['method' => 'DELETE']);
        $form->handleRequest($request);

        $id = $comment->getPhoto();
        $id_photo = $id->getId();

        if ($request->isMethod('DELETE') && !$form->isSubmitted()) {
            $form->submit($request->request->get($form->getName()));
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $commentRepository->delete($comment);
            $this->addFlash('warning', 'Comment_deleted_successfully');

            return $this->redirectToRoute('photo_show', ['id'=> $id_photo]);
        }

        return $this->render(
            'project/comments/delete.html.twig',
            [
                'form' => $form->createView(),
                'comment' => $comment,
            ]
        );
    }
}
