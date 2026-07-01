<?php

require_once __DIR__ . '/../Repositories/ActiviteRepository.php';
require_once __DIR__ . '/../Entities/Activite.php';

/**
 * ActiviteService
 * Logique métier liée aux types d'activités sportives.
 */
class ActiviteService
{
    private ActiviteRepository $activiteRepository;

    public function __construct(ActiviteRepository $activiteRepository)
    {
        $this->activiteRepository = $activiteRepository;
    }

    public function lister(): array
    {
        return $this->activiteRepository->findAll();
    }

    public function trouver(int $id): ?Activite
    {
        return $this->activiteRepository->findById($id);
    }

    public function creer(string $nomActivite): Activite
    {
        if (trim($nomActivite) === '') {
            throw new InvalidArgumentException("Le nom de l'activité est obligatoire.");
        }

        $activite = new Activite($nomActivite);
        $id = $this->activiteRepository->create($activite);
        $activite->setIdActivite($id);

        return $activite;
    }
}
