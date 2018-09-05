<?php

namespace App\Controller;

use App\Entity\Restaurant;
use App\Form\RestaurantType;
use App\Repository\RestaurantRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/restaurant")
 */
class RestaurantController extends Controller
{
    /**
     * @Route("/", name="restaurant_index", methods="GET")
     */
    public function index(RestaurantRepository $restaurantRepository): Response
    {
        return $this->render('restaurant/index.html.twig', ['restaurants' => $restaurantRepository->findAll()]);
    }

    /**
     * @Route("/new", name="restaurant_new", methods="GET|POST")
     */
    public function new(Request $request): Response
    {
        $restaurant = new Restaurant();
        $form = $this->createForm(RestaurantType::class, $restaurant);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($restaurant);
            $em->flush();

            return $this->redirectToRoute('restaurant_index');
        }

        return $this->render('restaurant/new.html.twig', [
            'restaurant' => $restaurant,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="restaurant_show", methods="GET")
     */
    public function show(Restaurant $restaurant): Response
    {
        return $this->render('restaurant/show.html.twig', ['restaurant' => $restaurant]);
    }

    /**
     * @Route("/{id}/edit", name="restaurant_edit", methods="GET|POST")
     */
    public function edit(Request $request, Restaurant $restaurant): Response
    {
        $form = $this->createForm(RestaurantType::class, $restaurant);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('restaurant_edit', ['id' => $restaurant->getId()]);
        }

        return $this->render('restaurant/edit.html.twig', [
            'restaurant' => $restaurant,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="restaurant_delete", methods="DELETE")
     */
    public function delete(Request $request, Restaurant $restaurant): Response
    {
        if ($this->isCsrfTokenValid('delete'.$restaurant->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($restaurant);
            $em->flush();
        }

        return $this->redirectToRoute('restaurant_index');
    }
}
