<?php

require_once __DIR__ . '/../Repositories/SalleRepository.php';
require_once __DIR__ . '/../Entities/Salle.php';

/**
 * SalleService
 * Logique métier liée aux salles du réseau FitConnect.
 */
class SalleService
{
    private SalleRepository $salleRepository;

    public function __construct(SalleRepository $salleRepository)
    {
        $this->salleRepository = $salleRepository;
    }

    public function lister(): array
    {
        return $this->salleRepository->findAll();
    }

    public function trouver(int $id): ?Salle
    {
        return $this->salleRepository->findById($id);
    }

    public function creer(string $nomSalle, string $adresse, string $ville): Salle
    {
        if (trim($nomSalle) === '') {
            throw new InvalidArgumentException('Le nom de la salle est obligatoire.');
        }

        $salle = new Salle($nomSalle, $adresse, $ville);
        $id = $this->salleRepository->create($salle);
        $salle->setIdSalle($id);

        return $salle;
    }
}
