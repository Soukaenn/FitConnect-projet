<?php

require_once __DIR__ . '/../Services/AbonnementService.php';
require_once __DIR__ . '/../Services/AdherentService.php';

/**
 * AbonnementController
 * Orchestre les services et repositories pour la ressource Abonnement.
 */
class AbonnementController
{
    private AbonnementService $abonnementService;
    private AdherentService $adherentService;

    public function __construct(AbonnementService $abonnementService, AdherentService $adherentService)
    {
        $this->abonnementService = $abonnementService;
        $this->adherentService = $adherentService;
    }

    public function index(): void
    {
        $abonnements = $this->abonnementService->lister();
        require __DIR__ . '/../../views/abonnements/index.php';
    }

    public function create(): void
    {
        $adherents = $this->adherentService->lister();
        $erreur = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $this->abonnementService->souscrire(
                    (int) ($_POST['id_adherent'] ?? 0),
                    trim($_POST['type_abonnement'] ?? ''),
                    trim($_POST['date_debut'] ?? date('Y-m-d'))
                );

                header('Location: index.php?controller=abonnement&action=index');
                exit;
            } catch (Exception $e) {
                $erreur = $e->getMessage();
            }
        }

        require __DIR__ . '/../../views/abonnements/create.php';
    }

    public function show(): void
    {
        $id = (int) ($_GET['id'] ?? 0);
        $abonnement = $this->abonnementService->trouver($id);

        if ($abonnement === null) {
            http_response_code(404);
            echo "Abonnement introuvable.";
            return;
        }

        $adherent = $this->adherentService->trouver($abonnement->getIdAdherent());

        require __DIR__ . '/../../views/abonnements/show.php';
    }

    public function edit(): void
    {
        $id = (int) ($_GET['id'] ?? 0);
        $abonnement = $this->abonnementService->trouver($id);

        if ($abonnement === null) {
            http_response_code(404);
            echo "Abonnement introuvable.";
            return;
        }

        $erreur = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $abonnement->setTypeAbonnement(trim($_POST['type_abonnement'] ?? ''));
                $abonnement->setDateDebut(trim($_POST['date_debut'] ?? ''));
                $abonnement->setDateFin(trim($_POST['date_fin'] ?? ''));

                $this->abonnementService->modifier($abonnement);

                header('Location: index.php?controller=abonnement&action=index');
                exit;
            } catch (Exception $e) {
                $erreur = $e->getMessage();
            }
        }

        require __DIR__ . '/../../views/abonnements/edit.php';
    }

    public function delete(): void
    {
        $id = (int) ($_GET['id'] ?? 0);
        $this->abonnementService->supprimer($id);

        header('Location: index.php?controller=abonnement&action=index');
        exit;
    }
}
