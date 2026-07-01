<?php

require_once __DIR__ . '/../Repositories/AdherentRepository.php';
require_once __DIR__ . '/../Repositories/AbonnementRepository.php';
require_once __DIR__ . '/../Entities/Adherent.php';

/**
 * AdherentService
 * Applique les règles de gestion liées aux adhérents,
 * indépendamment de la couche de persistance.
 */
class AdherentService
{
    private AdherentRepository $adherentRepository;
    private AbonnementRepository $abonnementRepository;

    public function __construct(
        AdherentRepository $adherentRepository,
        AbonnementRepository $abonnementRepository
    ) {
        $this->adherentRepository = $adherentRepository;
        $this->abonnementRepository = $abonnementRepository;
    }

    public function lister(): array
    {
        return $this->adherentRepository->findAll();
    }

    public function trouver(int $id): ?Adherent
    {
        return $this->adherentRepository->findById($id);
    }

    public function inscrire(
        string $nom,
        string $prenom,
        string $email,
        ?string $telephone,
        int $idSalle,
        ?string $dateInscription = null
    ): Adherent {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException("L'adresse email '{$email}' est invalide.");
        }

        if ($this->adherentRepository->findByEmail($email) !== null) {
            throw new RuntimeException("Un adhérent avec l'email '{$email}' existe déjà.");
        }

        $adherent = new Adherent(
            $nom,
            $prenom,
            $email,
            $telephone,
            $dateInscription ?? date('Y-m-d'),
            $idSalle
        );

        $id = $this->adherentRepository->create($adherent);
        $adherent->setIdAdherent($id);

        return $adherent;
    }

    public function modifier(Adherent $adherent): bool
    {
        if (!filter_var($adherent->getEmail(), FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException("Adresse email invalide.");
        }

        return $this->adherentRepository->update($adherent);
    }

    /**
     * Règle de gestion :
     * un adhérent ne peut pas être supprimé s'il possède des séances enregistrées
     * ou un abonnement en cours.
     */
    public function supprimer(int $id): bool
    {
        if ($this->adherentRepository->countSeances($id) > 0) {
            throw new RuntimeException(
                "Impossible de supprimer cet adhérent : des séances sont enregistrées à son nom."
            );
        }

        if ($this->abonnementRepository->hasAbonnementEnCours($id)) {
            throw new RuntimeException(
                "Impossible de supprimer cet adhérent : un abonnement est en cours."
            );
        }

        return $this->adherentRepository->delete($id);
    }

    public function listerParSalle(int $idSalle): array
    {
        return $this->adherentRepository->findBySalle($idSalle);
    }
}
