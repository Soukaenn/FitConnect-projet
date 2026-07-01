<?php

require_once __DIR__ . '/../Repositories/SeanceRepository.php';
require_once __DIR__ . '/AbonnementService.php';
require_once __DIR__ . '/../Entities/Seance.php';

/**
 * SeanceService
 * Applique les règles de gestion liées aux séances,
 * indépendamment de la couche de persistance.
 */
class SeanceService
{
    private SeanceRepository $seanceRepository;
    private AbonnementService $abonnementService;

    public function __construct(
        SeanceRepository $seanceRepository,
        AbonnementService $abonnementService
    ) {
        $this->seanceRepository = $seanceRepository;
        $this->abonnementService = $abonnementService;
    }

    public function lister(): array
    {
        return $this->seanceRepository->findAll();
    }

    public function listerAvecDetails(): array
    {
        return $this->seanceRepository->findAllWithDetails();
    }

    public function trouverAvecDetails(int $id): ?array
    {
        return $this->seanceRepository->findByIdWithDetails($id);
    }

    public function trouver(int $id): ?Seance
    {
        return $this->seanceRepository->findById($id);
    }

    public function listerParAdherent(int $idAdherent): array
    {
        return $this->seanceRepository->findByAdherent($idAdherent);
    }

    /**
     * Règle de gestion centrale :
     * une séance ne peut être enregistrée que si l'abonnement de l'adhérent
     * est valide à la date du jour (ou à la date de la séance).
     */
    public function enregistrer(
        int $idAdherent,
        int $idSalle,
        int $idActivite,
        int $duree,
        ?int $idEquipement = null,
        ?string $dateSeance = null
    ): Seance {
        $dateSeance = $dateSeance ?? date('Y-m-d');

        if (!$this->abonnementService->estAbonnementValide($idAdherent, $dateSeance)) {
            throw new RuntimeException(
                "Impossible d'enregistrer la séance : l'abonnement de l'adhérent n'est pas valide à cette date."
            );
        }

        if ($duree <= 0) {
            throw new InvalidArgumentException('La durée de la séance doit être positive.');
        }

        $seance = new Seance($dateSeance, $duree, $idActivite, $idSalle, $idAdherent, $idEquipement);
        $id = $this->seanceRepository->create($seance);
        $seance->setIdSeance($id);

        return $seance;
    }

    public function modifier(Seance $seance): bool
    {
        if ($seance->getDuree() <= 0) {
            throw new InvalidArgumentException('La durée de la séance doit être positive.');
        }

        return $this->seanceRepository->update($seance);
    }

    public function supprimer(int $id): bool
    {
        return $this->seanceRepository->delete($id);
    }
}
