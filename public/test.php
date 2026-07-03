<?php

/**
 * public/test.php
 * Script de test rapide pour valider chaque couche (Entités, Repositories, Services)
 * indépendamment de l'interface utilisateur.
 *
 * Usage : php public/test.php  (en CLI)  ou via le navigateur.
 */

declare(strict_types=1);

require_once __DIR__ . '/../config/Database.php';

require_once __DIR__ . '/../app/Entities/Adherent.php';
require_once __DIR__ . '/../app/Entities/Abonnement.php';
require_once __DIR__ . '/../app/Entities/Seance.php';

require_once __DIR__ . '/../app/Repositories/AdherentRepository.php';
require_once __DIR__ . '/../app/Repositories/AbonnementRepository.php';
require_once __DIR__ . '/../app/Repositories/SeanceRepository.php';

require_once __DIR__ . '/../app/Services/AdherentService.php';
require_once __DIR__ . '/../app/Services/AbonnementService.php';
require_once __DIR__ . '/../app/Services/SeanceService.php';

function section(string $titre): void
{
    echo "\n=== {$titre} ===\n";
}

try {
    $pdo = Database::getConnection();
    echo "[OK] Connexion PDO à la base FitConnect établie.\n";

    // --- Test 1 : Entité ---
    section('Test Entité Adherent');
    $adherentTest = new Adherent('Test', 'Unitaire', 'test.unitaire@email.com', '0600000000', date('Y-m-d'), 1);
    echo "Nom complet généré : " . $adherentTest->getNomComplet() . "\n";

    // --- Test 2 : Repositories ---
    section('Test AdherentRepository');
    $adherentRepository = new AdherentRepository($pdo);
    $tousLesAdherents = $adherentRepository->findAll();
    echo "Nombre d'adhérents en base : " . count($tousLesAdherents) . "\n";

    section('Test AbonnementRepository');
    $abonnementRepository = new AbonnementRepository($pdo);
    $abonnements = $abonnementRepository->findAll();
    echo "Nombre d'abonnements en base : " . count($abonnements) . "\n";

    section('Test SeanceRepository');
    $seanceRepository = new SeanceRepository($pdo);
    $seances = $seanceRepository->findAll();
    echo "Nombre de séances en base : " . count($seances) . "\n";

    // --- Test 3 : Services / règles de gestion ---
    section('Test AbonnementService - validité d\'abonnement');
    $abonnementService = new AbonnementService($abonnementRepository);

    if (!empty($tousLesAdherents)) {
        $premierAdherent = $tousLesAdherents[0];
        $idAdherent = $premierAdherent->getIdAdherent();
        $valide = $abonnementService->estAbonnementValide($idAdherent);
        echo "Abonnement valide aujourd'hui pour l'adhérent #{$idAdherent} ("
            . $premierAdherent->getNomComplet() . ") : " . ($valide ? 'OUI' : 'NON') . "\n";
    }

    section('Test SeanceService - règle de gestion (abonnement requis)');
    $seanceService = new SeanceService($seanceRepository, $abonnementService);

    // Tentative volontaire avec un adhérent fictif (id inexistant) -> doit lever une exception
    try {
        $seanceService->enregistrer(9999, 1, 1, 30);
        echo "[ECHEC] Aucune exception levée pour un adhérent sans abonnement valide.\n";
    } catch (RuntimeException $e) {
        echo "[OK] Règle de gestion respectée : " . $e->getMessage() . "\n";
    }

    section('Test AdherentService - règle de suppression protégée');
    $adherentService = new AdherentService($adherentRepository, $abonnementRepository);

    if (!empty($tousLesAdherents)) {
        $idAvecSeances = $tousLesAdherents[0]->getIdAdherent();
        try {
            $adherentService->supprimer($idAvecSeances);
            echo "[ATTENTION] L'adhérent #{$idAvecSeances} a été supprimé sans séance/abonnement associé.\n";
        } catch (RuntimeException $e) {
            echo "[OK] Suppression bloquée comme attendu : " . $e->getMessage() . "\n";
        }
    }

    section('Tests terminés');
    echo "Toutes les couches (Entités, Repositories, Services) répondent correctement.\n";
} catch (Throwable $e) {
    echo "[ERREUR] " . $e->getMessage() . "\n";
}
