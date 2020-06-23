<?php
/**
 * User controller.
 */

namespace App\Controller;

use App\Entity\User;
use App\Form\ChangePasswordType;

use App\Form\UserType;
use App\Repository\TagRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Class UserController.
 *
 * @Route("/users")
 */
class UserController extends AbstractController
{
    /**
     * @param Request            $request
     * @param UserRepository     $userRepository
     * @param PaginatorInterface $paginator
     *
     * @return Response
     *
     * @Route(
     *     "/",
     *     methods={"GET"},
     *     name="users_index",
     * )
     * @IsGranted("ROLE_ADMIN")
     */
    public function index(Request $request, UserRepository $userRepository, PaginatorInterface $paginator): Response
    {
        $pagination = $paginator->paginate(
            $userRepository->queryAll(),
            $request->query->getInt('page', 1),
            UserRepository::PAGINATOR_ITEMS_PER_PAGE
        );

        return $this->render(
            'project/users/index.html.twig',
            ['pagination' => $pagination]
        );
    }


    /**
     * Show user.
     *
     * @param UserRepository $userRepository
     * @param User           $user
     *
     * @return Response
     *
     * @Route(
     *     "/{id}/myaccount",
     *     methods={"GET"},
     *     requirements={"id": "[1-9]\d*"},
     *     name="my_account"
     * )
     * @IsGranted("ROLE_USER")
     */
    public function show(UserRepository $userRepository, User $user): Response
    {
        return $this->render(
            'project/users/myAccount.html.twig',
            ['user' => $user]
        );
    }

    /**
     * Edit email.
     *
     * @param Request        $request
     * @param User           $user
     * @param UserRepository $userRepository
     *
     * @return Response
     *
     * @throws ORMException
     * @throws OptimisticLockException
     *
     * @Route(
     *     "/{id}/edit/email",
     *     methods={"GET", "PUT"},
     *     requirements={"id": "[1-9]\d*"},
     *     name="edit_email",
     * )
     */
    public function editEmail(Request $request, User $user, UserRepository $userRepository):Response
    {
        $form = $this->createForm(UserType::class, $user, ['method' => 'PUT']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userRepository->save($user);

            $id = $user->getId();

            if ($this->isGranted('ROLE_ADMIN')) {
                $redirect = $this->redirectToRoute('users_index');
            } else {
                $redirect = $this->redirectToRoute('my_account', ['id' => $id]);
            }

            return $redirect;
        }

        return $this->render(
            'project/users/edit.html.twig',
            [
                'form' => $form->createView(),
                'user' => $user,
            ]
        );
    }

    /**
     * Edit password.
     *
     * @param Request                      $request
     * @param User                         $user
     * @param UserRepository               $userRepository
     * @param UserPasswordEncoderInterface $passwordEncoder
     *
     * @return Response
     *
     * @throws ORMException
     * @throws OptimisticLockException
     *
     * @Route(
     *     "/{id}/edit/password",
     *     methods={"GET", "PUT"},
     *     requirements={"id": "[1-9]\d*"},
     *     name="edit_password",
     * )
     */
    public function editPassword(Request $request, User $user, UserRepository $userRepository, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $form = $this->createForm(ChangePasswordType::class, $user, ['method' => 'PUT']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $form->get('password')->getData()
                )
            );

            $userRepository->save($user);
            $id = $user->getId();
            $this->addFlash('success', 'message_account_updated_successfully');

            if ($this->isGranted('ROLE_ADMIN')) {
                $redirect = $this->redirectToRoute('users_index');
            } else {
                $redirect = $this->redirectToRoute('my_account', ['id' => $id]);
            }

            return $redirect;
        }

        return $this->render(
            'project/users/editPassword.html.twig',
            [
                'form' => $form->createView(),
                'user' => $user,
            ]
        );
    }
}
