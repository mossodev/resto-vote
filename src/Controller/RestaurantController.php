<?php

namespace App\Controller;

use App\Entity\Restaurant;
use App\Form\RestaurantType;
use App\Repository\RestaurantRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @Route("/restaurant")
 */
class RestaurantController extends Controller
{
    /**
     * @Route("/", name="restaurant_index", methods="GET")
     */
    public function getRestaurantsAction(RestaurantRepository $restaurantRepository): Response
    {
        $restaurants = $restaurantRepository->findAll();
        $_restaurant = [];
        $_restaurants = [];
        foreach ($restaurants as $restaurant) {
            $_restaurant['name'] = $restaurant->getName();
            $_restaurant['address'] = $restaurant->getAddress();
            $_restaurant['phone'] = $restaurant->getPhone();
            $_restaurant['id'] = $restaurant->getId();
            $_restaurants[] = $_restaurant;
        }

        return new JsonResponse($_restaurants, 200);
        // return $this->render('restaurant/index.html.twig', ['restaurants' => ]);
    }

    /**
     * @Route("/", name="restaurant_new", methods="POST")
     */
    public function postRestaurantAction(Request $request): Response
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

        return $this->redirectToRoute('restaurant_index');
    }

    /**
     * @Route("/{id}", name="restaurant_show", methods="GET")
     */
    public function getRestaurantAction(Restaurant $restaurant): Response
    {
        $_restaurant['name'] = $restaurant->getName();
        $_restaurant['address'] = $restaurant->getAddress();
        $_restaurant['phone'] = $restaurant->getPhone();
        $_restaurant['id'] = $restaurant->getId();

        return new JsonResponse($_restaurant, 200);
    }

    /**
     * @Route("/{id}", name="restaurant_edit", methods="PUT|POST")
     */
    public function putRestaurantAction(Request $request, Restaurant $restaurant): Response
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

        return $this->redirectToRoute('restaurant_index');
    }

    /**
     * @Route("/{id}", name="restaurant_delete", methods="DELETE")
     */
    public function deleteRestaurantAction(Request $request, Restaurant $restaurant): Response
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($restaurant);
        $em->flush();

        return $this->redirectToRoute('restaurant_index');
    }
}
