<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\JsonResponse;

class UserController extends FOSRestController
{
    /**
     * @Rest\Get(
     * path = "/user",
     * name = "liste_users")
     */
    public function cgetUserAction(UserRepository $userRepository)
    {
        $users = $userRepository->findAll();

        return $this->view(['data' => $users], 200);
    }

    /**
     * @Rest\Post(
     * path = "login",
     * name = "log_user")
     */
    public function postLoginUserAction(Request $request, UserRepository $userRepository)
    {
        $data = json_decode($request->getContent(), true) ?: [];
        $user = $userRepository->findOneByEmail($data['email']);

        $token = $this->get('lexik_jwt_authentication.encoder')
                                  ->encode(['email' => $data['email']]);

        // if (!$user) {
        //     throw new ApiException(
        //         'The given data is invalid',
        //         ['login' => ['Le nom de cet utilisateur est introuvable.']],
        //         Response::HTTP_UNPROCESSABLE_ENTITY
        //     );
        // }

        // $isValid = $this->container->get('security.password_encoder')->isPasswordValid($account, $data['password']);
        // if (!$isValid) {
        //     throw new ApiException(
        //         'The given data is invalid',
        //         ['login/password' => ['Le mot de passe est incorrect.']],
        //         Response::HTTP_UNPROCESSABLE_ENTITY
        //     );
        // }

        return $this->view(['data' => ['token' => $token, 'user' => $user]], 200);
    }

    /**
     * @Rest\Post(
     * path = "/user",
     * name = "add_user")
     */
    public function postUserAction(Request $request)
    {
        $user = new User();
        $body = $request->getContent();
        $data = json_decode($body, true);
        $form = $this->createForm(UserType::class, $user);
        $form->submit($data);
        $validator = $this->get('validator');
        $errors = $validator->validate($user);

        if (count($errors) > 0) {
            $errorsString = (string) $errors;

            return new JsonResponse($errorsString);
        }
        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();

        return $this->redirectToRoute('liste_users');
    }

    /**
     * @Rest\Get(
     * path = "/user/{id}",
     * name = "show_user")
     */
    public function getUserAction(User $user)
    {
        return $this->view(['data' => $user], 200);
    }

    /**
     * @Rest\Put(
     * path = "/user/{id}",
     * name = "get_user")
     */
    public function putUserAction(Request $request, User $user)
    {
        $body = $request->getContent();
        $data = json_decode($body, true);
        $form = $this->createForm(UserType::class, $user);
        $form->submit($data);
        $validator = $this->get('validator');
        $errors = $validator->validate($user);

        if (count($errors) > 0) {
            $errorsString = (string) $errors;

            return new JsonResponse($errorsString);
        }
        $em = $this->getDoctrine()->getManager();
        $em->flush();

        return $this->view(['data' => $user], 200);
    }

    /**
     * @Rest\Delete(
     * path = "/user/{id}",
     * name = "remove_remove")
     */
    public function deleteUserAction(Request $request, User $user)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($user);
        $em->flush();

        return $this->redirectToRoute('user_index');
    }
}
