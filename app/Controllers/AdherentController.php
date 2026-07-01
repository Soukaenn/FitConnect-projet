<?php

require_once __DIR__ . '/../Services/AdherentService.php';
require_once __DIR__ . '/../Services/SalleService.php';

/**
 * AdherentController
 * Orchestre les services et repositories pour la ressource Adhérent.
 */
class AdherentController
{
    private AdherentService $adherentService;
    private SalleService $salleService;

    public function __construct(AdherentService $adherentService, SalleService $salleService)
    {
        $this->adherentService = $adherentService;
        $this->salleService = $salleService;
    }

    public function index(): void
    {
        $adherents = $this->adherentService->lister();
        require __DIR__ . '/../../views/adherents/index.php';
    }

    public function create(): void
    {
        $salles = $this->salleService->lister();
        $erreur = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $this->adherentService->inscrire(
                    trim($_POST['nom'] ?? ''),
                    trim($_POST['prenom'] ?? ''),
                    trim($_POST['email'] ?? ''),
                    trim($_POST['telephone'] ?? '') ?: null,
                    (int) ($_POST['id_salle'] ?? 0)
                );

                header('Location: index.php?controller=adherent&action=index');
                exit;
            } catch (Exception $e) {
                $erreur = $e->getMessage();
            }
        }

        require __DIR__ . '/../../views/adherents/create.php';
    }

    public function show(): void
    {
        $id = (int) ($_GET['id'] ?? 0);
        $adherent = $this->adherentService->trouver($id);

        if ($adherent === null) {
            http_response_code(404);
            echo "Adhérent introuvable.";
            return;
        }

        $salle = $this->salleService->trouver($adherent->getIdSalle());

        require __DIR__ . '/../../views/adherents/show.php';
    }

    public function edit(): void
    {
        $id = (int) ($_GET['id'] ?? 0);
        $adherent = $this->adherentService->trouver($id);

        if ($adherent === null) {
            http_response_code(404);
            echo "Adhérent introuvable.";
            return;
        }

        $salles = $this->salleService->lister();
        $erreur = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $adherent->setNom(trim($_POST['nom'] ?? ''));
                $adherent->setPrenom(trim($_POST['prenom'] ?? ''));
                $adherent->setEmail(trim($_POST['email'] ?? ''));
                $adherent->setTelephone(trim($_POST['telephone'] ?? '') ?: null);
                $adherent->setIdSalle((int) ($_POST['id_salle'] ?? 0));

                $this->adherentService->modifier($adherent);

                header('Location: index.php?controller=adherent&action=index');
                exit;
            } catch (Exception $e) {
                $erreur = $e->getMessage();
            }
        }

        require __DIR__ . '/../../views/adherents/edit.php';
    }

    public function delete(): void
    {
        $id = (int) ($_GET['id'] ?? 0);

        try {
            $this->adherentService->supprimer($id);
        } catch (Exception $e) {
            // En production : transmettre l'erreur via un flash message
            echo "Erreur : " . htmlspecialchars($e->getMessage());
            return;
        }

        header('Location: index.php?controller=adherent&action=index');
        exit;
    }
}
