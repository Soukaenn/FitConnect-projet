<?php

require_once __DIR__ . '/../Services/AdherentService.php';
require_once __DIR__ . '/../Services/SeanceService.php';
require_once __DIR__ . '/../Services/AbonnementService.php';
require_once __DIR__ . '/../Services/SalleService.php';

/**
 * DashboardController
 * Fournit une vue d'ensemble du réseau FitConnect (statistiques globales).
 */
class DashboardController
{
    private AdherentService $adherentService;
    private SeanceService $seanceService;
    private AbonnementService $abonnementService;
    private SalleService $salleService;

    public function __construct(
        AdherentService $adherentService,
        SeanceService $seanceService,
        AbonnementService $abonnementService,
        SalleService $salleService
    ) {
        $this->adherentService = $adherentService;
        $this->seanceService = $seanceService;
        $this->abonnementService = $abonnementService;
        $this->salleService = $salleService;
    }

    public function index(): void
    {
        $adherents = $this->adherentService->lister();
        $seances = $this->seanceService->listerAvecDetails();
        $abonnements = $this->abonnementService->lister();
        $salles = $this->salleService->lister();

        $stats = [
            'total_adherents'   => count($adherents),
            'total_seances'     => count($seances),
            'total_abonnements' => count($abonnements),
            'total_salles'      => count($salles),
        ];

        require __DIR__ . '/../../views/dashboard/index.php';
    }
}
