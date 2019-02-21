<?php

namespace App\Controller;

use App\Entity\Restaurant;
use App\Form\RestaurantType;
use App\Repository\RestaurantRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;

class RestaurantController extends FOSRestController
{
    /**
     * @Rest\Get(
     * path = "/restaurant",
     * name = "liste_restaurant")
     */
    public function getRestaurantsAction(RestaurantRepository $restaurantRepository)
    {
        $restaurants = $restaurantRepository->findAll();

        return $this->view(['data' => $restaurants], 200);
    }

    /**
     * @Rest\Post(
     * path = "/restaurant",
     * name = "add_restaurant")
     */
    public function postRestaurantAction(Request $request)
    {
        $restaurant = new Restaurant();
        $body = $request->getContent();
        $data = json_decode($body, true);
        $form = $this->createForm(RestaurantType::class, $restaurant);
        $form->submit($data);
        $validator = $this->get('validator');
        $errors = $validator->validate($restaurant);

        if (count($errors) > 0) {
            $errorsString = (string) $errors;

            return new JsonResponse($errorsString);
        }
        $em = $this->getDoctrine()->getManager();
        $em->persist($restaurant);
        $em->flush();

        return $this->redirectToRoute('liste_restaurant');
    }

    /**
     * @Rest\Get(
     * path = "/restaurant/{id}",
     * name = "show_restaurant")
     */
    public function getRestaurantAction(Restaurant $restaurant)
    {
        return $this->view(['data' => $restaurant], 200);
    }

    /**
     * @Rest\Put(
     * path = "/restaurant/{id}",
     * name = "add_restaurant")
     */
    public function putRestaurantAction(Request $request, Restaurant $restaurant)
    {
        $body = $request->getContent();
        $data = json_decode($body, true);
        $form = $this->createForm(RestaurantType::class, $restaurant);
        $form->submit($data);
        $validator = $this->get('validator');
        $errors = $validator->validate($restaurant);

        if (count($errors) > 0) {
            $errorsString = (string) $errors;

            return new JsonResponse($errorsString);
        }
        $em = $this->getDoctrine()->getManager();
        $em->flush();

        return $this->view(['data' => $restaurant], 200);
    }

    /**
     * @Rest\Delete(
     * path = "/restaurant/{id}",
     * name = "remove_restaurant")
     */
    public function deleteRestaurantAction(Request $request, Restaurant $restaurant)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($restaurant);
        $em->flush();

        return new JsonResponse(['message' => 'L\'objet a été supprimer avec succès'], 200);
    }
}
