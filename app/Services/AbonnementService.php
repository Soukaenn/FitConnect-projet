<?php

require_once __DIR__ . '/../Repositories/AbonnementRepository.php';
require_once __DIR__ . '/../Entities/Abonnement.php';

/**
 * AbonnementService
 * Applique les règles de gestion liées aux abonnements,
 * indépendamment de la couche de persistance.
 */
class AbonnementService
{
    private AbonnementRepository $abonnementRepository;

    private const TYPES_VALIDES = [
        Abonnement::TYPE_MENSUEL,
        Abonnement::TYPE_TRIMESTRIEL,
        Abonnement::TYPE_ANNUEL,
    ];

    public function __construct(AbonnementRepository $abonnementRepository)
    {
        $this->abonnementRepository = $abonnementRepository;
    }

    public function lister(): array
    {
        return $this->abonnementRepository->findAll();
    }

    public function trouver(int $id): ?Abonnement
    {
        return $this->abonnementRepository->findById($id);
    }

    public function listerParAdherent(int $idAdherent): array
    {
        return $this->abonnementRepository->findAllByAdherent($idAdherent);
    }

    /**
     * Règle de gestion : un adhérent ne détient qu'un seul abonnement actif à la fois.
     * Calcule automatiquement la date de fin selon le type choisi.
     */
    public function souscrire(int $idAdherent, string $type, string $dateDebut): Abonnement
    {
        if (!in_array($type, self::TYPES_VALIDES, true)) {
            throw new InvalidArgumentException(
                "Type d'abonnement invalide. Valeurs autorisées : " . implode(', ', self::TYPES_VALIDES)
            );
        }

        $abonnementEnCours = $this->abonnementRepository->findCurrentByAdherent($idAdherent);
        if ($abonnementEnCours !== null && $abonnementEnCours->estValide($dateDebut)) {
            throw new RuntimeException(
                "Cet adhérent possède déjà un abonnement actif jusqu'au {$abonnementEnCours->getDateFin()}."
            );
        }

        $dateFin = $this->calculerDateFin($type, $dateDebut);

        $abonnement = new Abonnement($type, $dateDebut, $dateFin, $idAdherent);
        $id = $this->abonnementRepository->create($abonnement);
        $abonnement->setIdAbonnement($id);

        return $abonnement;
    }

    public function modifier(Abonnement $abonnement): bool
    {
        if (!in_array($abonnement->getTypeAbonnement(), self::TYPES_VALIDES, true)) {
            throw new InvalidArgumentException("Type d'abonnement invalide.");
        }

        return $this->abonnementRepository->update($abonnement);
    }

    public function supprimer(int $id): bool
    {
        return $this->abonnementRepository->delete($id);
    }

    /**
     * Règle de gestion centrale :
     * une séance ne peut être enregistrée que si l'abonnement est valide à la date du jour.
     */
    public function estAbonnementValide(int $idAdherent, ?string $dateReference = null): bool
    {
        $abonnement = $this->abonnementRepository->findCurrentByAdherent($idAdherent);
        return $abonnement !== null && $abonnement->estValide($dateReference);
    }

    private function calculerDateFin(string $type, string $dateDebut): string
    {
        $date = new DateTime($dateDebut);

        switch ($type) {
            case Abonnement::TYPE_MENSUEL:
                $date->modify('+1 month');
                break;
            case Abonnement::TYPE_TRIMESTRIEL:
                $date->modify('+3 months');
                break;
            case Abonnement::TYPE_ANNUEL:
                $date->modify('+1 year');
                break;
        }

        return $date->format('Y-m-d');
    }
}
