<?php

namespace App\Controller;

use App\Entity\Colleague;
use App\Form\ColleagueType;
use App\Repository\ColleagueRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @Route("/colleague")
 */
class ColleagueController extends Controller
{
    /**
     * @Route("/", name="colleague_index", methods="GET")
     */
    public function getColleaguesAction(ColleagueRepository $colleagueRepository): Response
    {
        $_colleagues = [];
        $_colleague = [];
        $colleagues = $colleagueRepository->findAll();
        foreach ($colleagues as $colleague) {
            $_colleague['first_name'] = $colleague->getFirstName();
            $_colleague['last_name'] = $colleague->getLastName();
            $_colleague['contact'] = $colleague->getContact();
            $_colleague['id'] = $colleague->getId();
            $_colleagues[] = $_colleague;
        }

        return new JsonResponse(['colleagues' => $_colleagues], 200);
    }

    /**
     * @Route("/", name="colleague_new", methods="GET|POST")
     */
    public function postColleaguesAction(Request $request): Response
    {
        $colleague = new Colleague();
        $body = $request->getContent();
        $data = json_decode($body, true);
        $form = $this->createForm(ColleagueType::class, $colleague);
        $form->submit($data);
        $validator = $this->get('validator');
        $violations = $validator->validate($colleague);
        if (count($violations)) {
            return $this->view($this->checkConstraint($violations), Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        $em = $this->getDoctrine()->getManager();
        $em->persist($colleague);
        $em->flush();

        return $this->redirectToRoute('colleague_show', ['id' => $colleague->getId()]);
    }

    /**
     * @Route("/{id}", name="colleague_show", methods="GET")
     */
    public function getColleagueAction(Colleague $colleague): Response
    {
        $_colleague['first_name'] = $colleague->getFirstName();
        $_colleague['last_name'] = $colleague->getLastName();
        $_colleague['contact'] = $colleague->getContact();
        $_colleague['id'] = $colleague->getId();

        return new JsonResponse($_colleague, 200);
    }

    /**
     * @Route("/{id}", name="colleague_edit", methods="PUT")
     */
    public function putColleaguesAction(Request $request, Colleague $colleague): Response
    {
        $body = $request->getContent();
        $data = json_decode($body, true);
        $form = $this->createForm(ColleagueType::class, $colleague);
        $form->submit($data);
        $validator = $this->get('validator');
        $violations = $validator->validate($colleague);
        if (count($violations)) {
            return $this->view($this->checkConstraint($violations), Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        $em = $this->getDoctrine()->getManager();
        $em->flush();

        return $this->redirectToRoute('colleague_show', ['id' => $colleague->getId()]);
    }

    /**
     * @Route("/{id}", name="colleague_delete", methods="DELETE")
     */
    public function deleteColleaguesAction(Request $request, Colleague $colleague): Response
    {
        if ($this->isCsrfTokenValid('delete'.$colleague->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($colleague);
            $em->flush();
        }

        return $this->redirectToRoute('colleague_index');
    }
}
