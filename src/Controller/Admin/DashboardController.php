<?php

namespace App\Controller\Admin;

use App\Entity\Clinique;
use App\Entity\Contacts;
use App\Entity\DemandeRDV;
use App\Entity\Devis;
use App\Entity\Faq;
use App\Entity\Intervention;
use App\Entity\Parametre;
use App\Entity\Patient;
use App\Repository\ContactsRepository;
use App\Repository\DemandeRDVRepository;
use App\Repository\DevisRepository;
use App\Repository\PatientRepository;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class DashboardController extends AbstractDashboardController
{
    protected $patients;
    protected $contacts;
    protected $devis;
    protected $demandeRDV;
    public function __construct(PatientRepository $patientRepository,DevisRepository $devisRepository,DemandeRDVRepository $demandeRDVRepository,ContactsRepository $contactsRepository){
       $this->patients=$patientRepository;
       $this->contacts=$contactsRepository;
       $this->demandeRDV=$demandeRDVRepository;
       $this->devis=$devisRepository;
    }
    /**
     * @Route("/admin", name="admin")
     */
    public function index(): Response
    {
        $etats =$this->devis->DevisEtat();
        $etatD = [];
        $nbrD = [];
        foreach ($etats as $cmd) {
            if ($cmd['etat'] == null) {
                $etatD[] = "En attente";
            } else {
                $etatD[] = $cmd['etat'];
            }
            $nbrD[] = $cmd['nombreD'];
        }
        // Check if the current user has the ROLE_ADMIN role
        if (!$this->isGranted('ROLE_ADMIN')) {
            throw new AccessDeniedException('You do not have permission to access this resource.');
        }
        // Your logic for the action when the user has the ROLE_ADMIN role
        //return parent::index();
      // dd($this->devis->DevisAFacturer());
         return $this->render('easyadmin/welcome.html.twig',[
            'totalContacts'=>$this->contacts->totalContacts(),
            'totalContactsYESTERDAY'=>$this->contacts->totalContactsYESTERDAY(),
            'totalContactsTODAY'=>$this->contacts->totalContactsTODAY(),
            'totalDemandeRDV'=>$this->demandeRDV->totalDemandeRDV(),
            'totalDevis'=>$this->devis->totalDevis(),
             'EtatDevis' => json_encode($etatD),
             'nbrDevis' => json_encode($nbrD),
            'DevisAFacturer'=>json_encode($this->devis->DevisAFacturer()),
            'DevisFacturé'=>$this->devis->DevisFacturé(),
            'DevisTraitées'=>$this->devis->DevisTraitées(),
            'DevisRefusé'=>$this->devis->DevisRefusé(),
            'totalPatient'=>$this->patients->totalPatient(),
         ]);


    }


    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Best Body Travel')
            ->setFaviconPath('favicon.svg')
            ->renderContentMaximized()
            ->generateRelativeUrls()
            ->setTitle('<img src="assets/img/BBTL7.png">  <span class="text-small"></span>');


    }

    public function configureMenuItems(): iterable
    {


        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        // yield MenuItem::linkToCrud('The Label', 'fas fa-list', EntityClass::class);
        yield MenuItem::linkToCrud('Clinique', 'fas fa-map-marker-alt', Clinique::class);
        yield MenuItem::linkToCrud('Devis', 'fa fa-tags', Devis::class);
        yield MenuItem::linkToCrud('FAQ', 'fas fa-comments', Faq::class);
        yield MenuItem::linkToCrud('Intervention', 'fas fa-comments', Intervention::class);
        yield MenuItem::linkToCrud('Patient', 'fas fa-comments', Patient::class);
        yield MenuItem::linkToCrud('DemandeRDV', 'fas fa-comments', DemandeRDV::class);
        yield MenuItem::linkToCrud('Contacts', 'fas fa-comments', Contacts::class);
        yield MenuItem::linkToCrud('Parametre', 'fas fa-comments', Parametre::class);


    }
}
