<?php

/**
 * public/index.php
 * Point d'entrée unique de l'application FitConnect.
 * Charge la connexion PDO, instancie les couches (Repository -> Service -> Controller)
 * puis route la requête vers le bon contrôleur/action.
 */

declare(strict_types=1);

require_once __DIR__ . '/../config/Database.php';

// --- Entités ---
require_once __DIR__ . '/../app/Entities/Adherent.php';
require_once __DIR__ . '/../app/Entities/Abonnement.php';
require_once __DIR__ . '/../app/Entities/Seance.php';
require_once __DIR__ . '/../app/Entities/Salle.php';
require_once __DIR__ . '/../app/Entities/Activite.php';

// --- Repositories ---
require_once __DIR__ . '/../app/Repositories/AdherentRepository.php';
require_once __DIR__ . '/../app/Repositories/AbonnementRepository.php';
require_once __DIR__ . '/../app/Repositories/SeanceRepository.php';
require_once __DIR__ . '/../app/Repositories/SalleRepository.php';
require_once __DIR__ . '/../app/Repositories/ActiviteRepository.php';

// --- Services ---
require_once __DIR__ . '/../app/Services/AdherentService.php';
require_once __DIR__ . '/../app/Services/AbonnementService.php';
require_once __DIR__ . '/../app/Services/SeanceService.php';
require_once __DIR__ . '/../app/Services/SalleService.php';
require_once __DIR__ . '/../app/Services/ActiviteService.php';

// --- Controllers ---
require_once __DIR__ . '/../app/Controllers/AdherentController.php';
require_once __DIR__ . '/../app/Controllers/AbonnementController.php';
require_once __DIR__ . '/../app/Controllers/SeanceController.php';
require_once __DIR__ . '/../app/Controllers/DashboardController.php';

// --- Connexion PDO centralisée ---
$pdo = Database::getConnection();

// --- Injection des dépendances (Repository -> Service -> Controller) ---
$adherentRepository   = new AdherentRepository($pdo);
$abonnementRepository = new AbonnementRepository($pdo);
$seanceRepository     = new SeanceRepository($pdo);
$salleRepository      = new SalleRepository($pdo);
$activiteRepository   = new ActiviteRepository($pdo);

$adherentService   = new AdherentService($adherentRepository, $abonnementRepository);
$abonnementService = new AbonnementService($abonnementRepository);
$seanceService      = new SeanceService($seanceRepository, $abonnementService);
$salleService       = new SalleService($salleRepository);
$activiteService    = new ActiviteService($activiteRepository);

$controllers = [
    'adherent'   => new AdherentController($adherentService, $salleService),
    'abonnement' => new AbonnementController($abonnementService, $adherentService),
    'seance'     => new SeanceController($seanceService, $adherentService, $salleService, $activiteService),
    'dashboard'  => new DashboardController($adherentService, $seanceService, $abonnementService, $salleService),
];

// --- Routage simple basé sur les paramètres GET ---
$controllerName = $_GET['controller'] ?? 'dashboard';
$action = $_GET['action'] ?? 'index';

if (!isset($controllers[$controllerName])) {
    http_response_code(404);
    echo "Contrôleur '{$controllerName}' introuvable.";
    exit;
}

$controller = $controllers[$controllerName];

if (!method_exists($controller, $action)) {
    http_response_code(404);
    echo "Action '{$action}' introuvable sur le contrôleur '{$controllerName}'.";
    exit;
}

$controller->$action();
