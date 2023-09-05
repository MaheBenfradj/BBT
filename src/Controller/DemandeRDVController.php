<?php

namespace App\Controller;

use App\Entity\DemandeRDV;
use App\Form\DemandeRDV1Type;
use App\Repository\DemandeRDVRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/demande/r/d/v")
 */
class DemandeRDVController extends AbstractController
{
    /**
     * @Route("/", name="app_demande_r_d_v_index", methods={"GET"})
     */
    public function index(DemandeRDVRepository $demandeRDVRepository): Response
    {
        return $this->render('demande_rdv/index.html.twig', [
            'demande_r_d_vs' => $demandeRDVRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="app_demande_r_d_v_new", methods={"GET", "POST"})
     */
    public function new(Request $request, DemandeRDVRepository $demandeRDVRepository): Response
    {
        $demandeRDV = new DemandeRDV();
        $form = $this->createForm(DemandeRDV1Type::class, $demandeRDV);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $demandeRDV->setDateCreation(new \DateTime('now'));
            $demandeRDVRepository->add($demandeRDV, true);

            return $this->redirectToRoute('app_demande_r_d_v_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('demande_rdv/new.html.twig', [
            'demande_r_d_v' => $demandeRDV,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_demande_r_d_v_show", methods={"GET"})
     */
    public function show(DemandeRDV $demandeRDV): Response
    {
        return $this->render('demande_rdv/show.html.twig', [
            'demande_r_d_v' => $demandeRDV,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_demande_r_d_v_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, DemandeRDV $demandeRDV, DemandeRDVRepository $demandeRDVRepository): Response
    {
        $form = $this->createForm(DemandeRDV1Type::class, $demandeRDV);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $demandeRDVRepository->add($demandeRDV, true);

            return $this->redirectToRoute('app_demande_r_d_v_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('demande_rdv/edit.html.twig', [
            'demande_r_d_v' => $demandeRDV,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_demande_r_d_v_delete", methods={"POST"})
     */
    public function delete(Request $request, DemandeRDV $demandeRDV, DemandeRDVRepository $demandeRDVRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$demandeRDV->getId(), $request->request->get('_token'))) {
            $demandeRDVRepository->remove($demandeRDV, true);
        }

        return $this->redirectToRoute('app_demande_r_d_v_index', [], Response::HTTP_SEE_OTHER);
    }
}
