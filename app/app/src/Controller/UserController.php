<?php
/**
 * User controller.
 */

namespace App\Controller;

use App\Entity\User;
use App\Form\ChangePasswordType;

use App\Form\UserType;
use App\Service\UserService;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
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
     * @var UserService
     */
    private $userService;

    /**
     * UserController constructor.
     *
     * @param UserService $userService
     */
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * Index of galleries.
     *
     * @param Request $request
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
    public function index(Request $request): Response
    {
        $page = $request->query->getInt('page', 1);
        $pagination = $this->userService->createPaginatedList($page);

        return $this->render(
            'project/users/index.html.twig',
            ['pagination' => $pagination]
        );
    }

    /**
     * Show user.
     *
     * @param User $user
     *
     * @return Response
     *
     * @Route(
     *     "/{id}/myaccount",
     *     methods={"GET"},
     *     requirements={"id": "[1-9]\d*"},
     *     name="my_account"
     * )
     * @IsGranted(
     *     "VIEW",
     *     subject="user",
     * )
     */
    public function show(User $user): Response
    {
        return $this->render(
            'project/users/myAccount.html.twig',
            ['user' => $user]
        );
    }

    /**
     * Edit email.
     *
     * @param Request $request
     * @param User    $user
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
     *
     * @IsGranted(
     *     "EDIT_EMAIL",
     *     subject="user",
     * )
     */
    public function editEmail(Request $request, User $user):Response
    {
        $form = $this->createForm(UserType::class, $user, ['method' => 'PUT']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->userService->save($user);

            $id = $user->getId();

            $this->addFlash('success', 'email_updated');
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
     *
     * @IsGranted(
     *     "EDIT_PASS",
     *     subject="user",
     * )
     */
    public function editPassword(Request $request, User $user, UserPasswordEncoderInterface $passwordEncoder): Response
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
            $this->userService->save($user);

            $id = $user->getId();
            $this->addFlash('success', 'password_updated');

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
