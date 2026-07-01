<?php

require_once __DIR__ . '/../Services/SeanceService.php';
require_once __DIR__ . '/../Services/AdherentService.php';
require_once __DIR__ . '/../Services/SalleService.php';
require_once __DIR__ . '/../Services/ActiviteService.php';

/**
 * SeanceController
 * Orchestre les services et repositories pour la ressource Séance.
 */
class SeanceController
{
    private SeanceService $seanceService;
    private AdherentService $adherentService;
    private SalleService $salleService;
    private ActiviteService $activiteService;

    public function __construct(
        SeanceService $seanceService,
        AdherentService $adherentService,
        SalleService $salleService,
        ActiviteService $activiteService
    ) {
        $this->seanceService = $seanceService;
        $this->adherentService = $adherentService;
        $this->salleService = $salleService;
        $this->activiteService = $activiteService;
    }

    public function index(): void
    {
        $seances = $this->seanceService->listerAvecDetails();
        require __DIR__ . '/../../views/seances/index.php';
    }

    public function create(): void
    {
        $adherents = $this->adherentService->lister();
        $salles = $this->salleService->lister();
        $activites = $this->activiteService->lister();
        $erreur = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $idEquipement = !empty($_POST['id_equipement']) ? (int) $_POST['id_equipement'] : null;

                $this->seanceService->enregistrer(
                    (int) ($_POST['id_adherent'] ?? 0),
                    (int) ($_POST['id_salle'] ?? 0),
                    (int) ($_POST['id_activite'] ?? 0),
                    (int) ($_POST['duree'] ?? 0),
                    $idEquipement,
                    trim($_POST['date_seance'] ?? date('Y-m-d'))
                );

                header('Location: index.php?controller=seance&action=index');
                exit;
            } catch (Exception $e) {
                $erreur = $e->getMessage();
            }
        }

        require __DIR__ . '/../../views/seances/create.php';
    }

    public function show(): void
    {
        $id = (int) ($_GET['id'] ?? 0);
        $seance = $this->seanceService->trouverAvecDetails($id);

        if ($seance === null) {
            http_response_code(404);
            echo "Séance introuvable.";
            return;
        }

        require __DIR__ . '/../../views/seances/show.php';
    }

    public function edit(): void
    {
        $id = (int) ($_GET['id'] ?? 0);
        $seance = $this->seanceService->trouver($id);

        if ($seance === null) {
            http_response_code(404);
            echo "Séance introuvable.";
            return;
        }

        $adherents = $this->adherentService->lister();
        $salles = $this->salleService->lister();
        $activites = $this->activiteService->lister();
        $erreur = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $idEquipement = !empty($_POST['id_equipement']) ? (int) $_POST['id_equipement'] : null;

                $seance->setDateSeance(trim($_POST['date_seance'] ?? ''));
                $seance->setDuree((int) ($_POST['duree'] ?? 0));
                $seance->setIdEquipement($idEquipement);
                $seance->setIdActivite((int) ($_POST['id_activite'] ?? 0));
                $seance->setIdSalle((int) ($_POST['id_salle'] ?? 0));
                $seance->setIdAdherent((int) ($_POST['id_adherent'] ?? 0));

                $this->seanceService->modifier($seance);

                header('Location: index.php?controller=seance&action=index');
                exit;
            } catch (Exception $e) {
                $erreur = $e->getMessage();
            }
        }

        require __DIR__ . '/../../views/seances/edit.php';
    }

    public function delete(): void
    {
        $id = (int) ($_GET['id'] ?? 0);
        $this->seanceService->supprimer($id);

        header('Location: index.php?controller=seance&action=index');
        exit;
    }
}
