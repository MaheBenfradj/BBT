<?php

namespace App\Controller;

use App\Entity\Contacts;
use App\Entity\DemandeRDV;
use App\Entity\Patient;
use App\Form\DemandeRDV1Type;
use App\Repository\CliniqueRepository;
use App\Repository\DemandeRDVRepository;
use App\Repository\FaqRepository;
use App\Repository\InterventionRepository;
use App\Repository\ParametreRepository;
use App\Repository\PatientRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    public function __construct(ParametreRepository $parametreRepository)
    {
        $this->appTel = $parametreRepository->findOneBy(array('nom' => 'telephone'))->getValeur();
        $this->appEmail = $parametreRepository->findOneBy(array('nom' => 'email'))->getValeur();
        $this->facebook = $parametreRepository->findOneBy(array('nom' => 'facebook'))->getValeur();
        $this->instagram = $parametreRepository->findOneBy(array('nom' => 'instagram'))->getValeur();
    }


    /**
     * @Route("/", name="homepage")
     */
    public function index(PatientRepository $patientRepository, Request $request, DemandeRDVRepository $demandeRDVRepository, EntityManagerInterface $entityManager, FaqRepository $faqRepository, InterventionRepository $interventionRepository, CliniqueRepository $cliniqueRepository, MailerInterface $mailer)
    {

        $faqs = $faqRepository->findBy(array('afficher' => true));
        $interventions = $interventionRepository->findBy(array('afficher' => true), array('nom' => 'asc'));
        $demandeRDV = new DemandeRDV();
        $patient = new Patient();
        $form = $this->createForm(DemandeRDV1Type::class, $demandeRDV);
        $form->handleRequest($request);
        $data = $form->getData();
        if ($form->isSubmitted() && $form->isValid()) {
            // return $this->redirectToRoute('homepage/#appointment', [], Response::HTTP_SEE_OTHER);
            $demandeRDV->setDateCreation(new \DateTime('now'));
            $patientEX = $patientRepository->findOneBy(array('email' => $data->getEmail(),
                'telephine' => $data->getTelephone(),
                'nom' => $data->getNom()));
            if (isset($patientEX)) {
                $demandeRDV->setPatient($patientEX);
            } else {
                $patient->setDateCreation(new \DateTime('now'));
                $patient->setNom($data->getNom());
                $patient->setEmail($data->getEmail());
                $patient->setTelephine($data->getTelephone());
                $entityManager->persist($patient);
                $entityManager->flush();
                $demandeRDV->setPatient($patient);
                $demandeRDV->setEtat('En attente');
            }
            $demandeRDVRepository->add($demandeRDV, true);
            $subject = 'Site web:demande rendez-vous' . $demandeRDV->getId();
            $email = (new TemplatedEmail())
                ->subject($subject)
                ->context(['patient' => $patient,
                    'demandeRDV' => $demandeRDV])
                ->from('bestbodytravel@gmail.com')
                ->to('bestbodytravel@gmail.com')
                //->to('hela2225@gmail.com')
                ->htmlTemplate('email/demandeRDV.html.twig');
            // Get the email's HTML content as a string
            $htmlContent = $this->renderView($email->getHtmlTemplate(), $email->getContext());

            $bootstrapCssLink = '
     <link href="{{ asset("assets/vendor/bootstrap/css/bootstrap.min.css") }}" rel="stylesheet">
     <link href="{{ asset("assets/vendor/boxicons/css/boxicons.min.css") }}" rel="stylesheet">';

            $htmlContentWithStyles = str_replace('</head>', $bootstrapCssLink . '</head>', $htmlContent);

            // Set the modified HTML content back to the email
            $email->html($htmlContentWithStyles);
            $mailer->send($email);

            if (isset($patientEX)) {
                $patient = $patientEX;
            } else {
                $patient = !$patient;
            }
            //return $this->redirectToRoute('app_devis_new', [], Response::HTTP_SEE_OTHER);

            $this->addFlash('success', 'Votre demande de rendez-vous a été envoyée avec succès. Merci!');
            return $this->redirectToRoute('homepage', [], Response::HTTP_SEE_OTHER);
        }
        if (isset($_POST['envoyer'])) {
            $contact = new Contacts();
            $nom = $_POST['name'];
            $prenom = $_POST['Prénom'];
            $telephone = $_POST['telephone'];
            $emailContact = $_POST['email'];
            $subject = $_POST['subject'];
            $message = $_POST['message'];
            $contact->setDateCreation(new \DateTime('now'));
            $contact->setNom($nom);
            $contact->setPrenom($prenom);
            $contact->setTelephone($telephone);
            $contact->setEmail($emailContact);
            $contact->setObjet($subject);
            $contact->setMessage($message);
            $entityManager->persist($contact);
            $entityManager->flush();

            $subject = $_POST['subject'];
            $message = $_POST['message'];

            $email = (new TemplatedEmail())
                ->from('bestbodytravel@gmail.com')
                ->to('hela2225@gmail.com', 'bestbodytravel@gmail.com')
                ->htmlTemplate('email/formContact.html.twig')
                ->subject($subject)
                ->context(['nom' => $nom,
                    'prenom' => $prenom,
                    'telephone' => $telephone,
                    'emailContact' => $emailContact, 'subject' => $subject, 'message' => $message,
                ]);
            // Get the email's HTML content as a string
            $htmlContent = $this->renderView($email->getHtmlTemplate(), $email->getContext());

            $bootstrapCssLink = '
     <link href="{{ asset("assets/vendor/bootstrap/css/bootstrap.min.css") }}" rel="stylesheet">
     <link href="{{ asset("assets/vendor/boxicons/css/boxicons.min.css") }}" rel="stylesheet">';

            $htmlContentWithStyles = str_replace('</head>', $bootstrapCssLink . '</head>', $htmlContent);

            // Set the modified HTML content back to the email
            $email->html($htmlContentWithStyles);
            $mailer->send($email);
            $this->addFlash('success', 'message envoyé!');
        }
        return $this->render('homepage.html.twig', [
            'appTel' => $this->appTel,
            'appEmail' => $this->appEmail,
            'facebook' => $this->facebook,
            'instagram' => $this->instagram,
            'faqs' => $faqs,
            'cliniques' => $cliniqueRepository->findAll(array(), array('nom' => 'asc')),
            'interventions' => $interventions,
            'demande_r_d_v' => $demandeRDV,
            'form' => $form->createView(),

        ]);
    }

    /**
     * @Route("/QFP", name="QFP")
     */
    public function QFP(FaqRepository $faqRepository)
    {


        $faqs = $faqRepository->findBy(array('afficher' => true));
        return $this->render('FAQ.html.twig', [
            'faqs' => $faqs, 'appTel' => $this->appTel,
            'appEmail' => $this->appEmail, 'facebook' => $this->facebook,
            'instagram' => $this->instagram,

        ]);
    }

    /**
     * @Route("/quisommesnous", name="quisommesnous")
     */
    public function quisommesnous()
    {
        return $this->render('quisommesnous.html.twig', [
            'appTel' => $this->appTel,
            'appEmail' => $this->appEmail, 'facebook' => $this->facebook,
            'instagram' => $this->instagram,
        ]);
    }


    /**
     * @Route("/ChirurgieEsthétique", name="ChirurgieEsthétique")
     */
    public function ChirurgieEsthétique()
    {
        return $this->render('chirurgieEsthétique.html.twig', [
            'appTel' => $this->appTel,
            'appEmail' => $this->appEmail, 'facebook' => $this->facebook,
            'instagram' => $this->instagram,
        ]);
    }

    /**
     * @Route("/ChirurgieObésité", name="ChirurgieObésité")
     */
    public function ChirurgieObésité()
    {
        return $this->render('chirurgieObesite.html.twig', ['appTel' => $this->appTel, 'facebook' => $this->facebook,
            'instagram' => $this->instagram,
            'appEmail' => $this->appEmail,]);
    }

    /**
     * @Route("/GreffeCheveux", name="GreffeCheveux")
     */
    public function GreffeCheveux()
    {
        return $this->render('GreffeCheveux.html.twig', ['appTel' => $this->appTel, 'facebook' => $this->facebook,
            'instagram' => $this->instagram,
            'appEmail' => $this->appEmail,]);
    }

    /**
     * @Route("/chirurgieOrthopédique", name="chirurgieOrthopédique")
     */
    public function chirurgieOrthopédique()
    {
        return $this->render('chirurgieOrthopédique.html.twig', ['appTel' => $this->appTel, 'facebook' => $this->facebook,
            'instagram' => $this->instagram,
            'appEmail' => $this->appEmail,]);
    }

    /**
     * @Route("/neurochirurgie", name="neurochirurgie")
     */
    public function neurochirurgie()
    {
        return $this->render('neurochirurgie.html.twig', ['appTel' => $this->appTel, 'facebook' => $this->facebook,
            'instagram' => $this->instagram,
            'appEmail' => $this->appEmail,]);
    }

    /**
     * @Route("/traitementInfertilité", name="traitementInfertilité")
     */
    public function traitementInfertilité()
    {
        return $this->render('traitementInfertilité.html.twig', ['appTel' => $this->appTel, 'facebook' => $this->facebook,
            'instagram' => $this->instagram,
            'appEmail' => $this->appEmail,]);
    }
}

