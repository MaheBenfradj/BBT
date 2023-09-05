<?php

namespace App\Controller;

use App\Entity\Devis;
use App\Entity\Patient;
use App\Form\DevisType;
use App\Repository\DevisRepository;
use App\Repository\ParametreRepository;
use App\Repository\PatientRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/devis")
 */
class DevisController extends AbstractController
{
    public function __construct(ParametreRepository $parametreRepository)
    {
        $this->appTel = $parametreRepository->findOneBy(array('nom' => 'telephone'))->getValeur();
        $this->appEmail = $parametreRepository->findOneBy(array('nom' => 'email'))->getValeur();
        $this->facebook = $parametreRepository->findOneBy(array('nom' => 'facebook'))->getValeur();
        $this->instagram = $parametreRepository->findOneBy(array('nom' => 'instagram'))->getValeur();
    }

    /**
     * @Route("/", name="app_devis_index", methods={"GET"})
     */
    public function index(DevisRepository $devisRepository): Response
    {
        return $this->render('devis/index.html.twig', [
            'devis' => $devisRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="app_devis_new", methods={"GET", "POST"})
     */
    public function new(ParametreRepository $parametreRepository,Request $request, PatientRepository $patientRepository, EntityManagerInterface $entityManager, MailerInterface $mailer): Response
    {
        $devi = new Devis();
        $patient = new Patient();

        $form = $this->createForm(DevisType::class, $devi);
        $form->handleRequest($request);
        $data = $form->getData();

        if ($form->isSubmitted()) {
            $patientEX = $patientRepository->findOneBy(array('email' => $data->getPatient()->getEmail(),
                'telephine' => $data->getPatient()->getTelephine(),
                'nom' => $data->getPatient()->getNom()));
            if (!isset($patientEX)) {
                $data->getPatient()->setDateCreation(new \DateTime('now'));
            }
        }
        if ($form->isSubmitted() && $form->isValid()) {
            if (isset($patientEX)) {
                $devi->setPatient($patientEX);
            } else {
                $patient->setDateCreation(new \DateTime('now'));
                $patient->setNom($data->getPatient()->getNom());
                $patient->setDateNaissance($data->getPatient()->getDateNaissance());
                $patient->setPays($data->getPatient()->getPays());
                $patient->setEmail($data->getPatient()->getEmail());
                $patient->setTelephine($data->getPatient()->getTelephine());
                $patient->setAdresse($data->getPatient()->GetAdresse());
                $entityManager->persist($patient);
                $devi->setPatient($patient);
            }

            $devi->setDateCreation(new \DateTime('now'));
            $devi->setEtat('En attente');
            $entityManager->persist($devi);
            $entityManager->flush();
            if (isset($patientEX)) {
                $patient = $patientEX;
            } else {
                $patient = !$patient;
            }
            $subject = 'Site web:Demande de devis ' . $devi->getId();
            $body = "Bonjour,\r\n Vous avez reçu une demande de devis pour :" . $data->getOperation()->getNom() . "la part de : \r\n";
            $body .= "Mr / Mme:" . $data->getPatient()->getNom() . " " . "\r\n";
            $body .= "Téléphone:" . $data->getPatient()->getTelephine() . "\r\n";
            $body .= "Email:" . $data->getPatient()->getEmail() . "\r\n";
            $body .= "Num devis:" . $devi->getId() . "\r\n";

            $email = (new TemplatedEmail())
                ->subject($subject)
                ->context(['patient' => $patient,
                    'devis' => $devi])
                ->from('bestbodytravel@gmail.com')
                ->to('bestbodytravel@gmail.com')
                ->htmlTemplate('email/demandeDevis.html.twig');
            // Get the email's HTML content as a string
            $htmlContent = $this->renderView($email->getHtmlTemplate(), $email->getContext());

            $bootstrapCssLink = '
     <link href="{{ asset("assets/vendor/bootstrap/css/bootstrap.min.css") }}" rel="stylesheet">
     <link href="{{ asset("assets/vendor/boxicons/css/boxicons.min.css") }}" rel="stylesheet">';

            $htmlContentWithStyles = str_replace('</head>', $bootstrapCssLink . '</head>', $htmlContent);

            // Set the modified HTML content back to the email
            $email->html($htmlContentWithStyles);
            $mailer->send($email);


            return $this->redirectToRoute('app_devis_new', [], Response::HTTP_SEE_OTHER);

            $this->addFlash('success', 'Votre message a été envoyé avec succès');
            return $this->redirectToRoute('app_devis_new', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('devis/new.html.twig', [
            'devi' => $devi,
            'form' => $form,
            'appEmail' =>  $this->appEmail,
            'appTel' =>  $this->appTel,  'facebook' => $this->facebook,
            'instagram' => $this->instagram,
        ]);
    }

    /**
     * @Route("/{id}", name="app_devis_show", methods={"GET"})
     */
    public function show(Devis $devi): Response
    {
        return $this->render('devis/show.html.twig', [
            'devi' => $devi,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_devis_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Devis $devi, DevisRepository $devisRepository): Response
    {
        $form = $this->createForm(DevisType::class, $devi);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $devisRepository->add($devi, true);

            return $this->redirectToRoute('app_devis_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('devis/edit.html.twig', [
            'devi' => $devi,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_devis_delete", methods={"POST"})
     */
    public function delete(Request $request, Devis $devi, DevisRepository $devisRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $devi->getId(), $request->request->get('_token'))) {
            $devisRepository->remove($devi, true);
        }

        return $this->redirectToRoute('app_devis_index', [], Response::HTTP_SEE_OTHER);
    }
}

