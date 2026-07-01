<?php

/**
 * Entité Abonnement
 * Représente l'abonnement (mensuel, trimestriel, annuel) d'un adhérent.
 */
class Abonnement
{
    public const TYPE_MENSUEL    = 'mensuel';
    public const TYPE_TRIMESTRIEL = 'trimestriel';
    public const TYPE_ANNUEL     = 'annuel';

    private ?int $idAbonnement;
    private string $typeAbonnement;
    private string $dateDebut;
    private string $dateFin;
    private int $idAdherent;

    public function __construct(
        string $typeAbonnement,
        string $dateDebut,
        string $dateFin,
        int $idAdherent,
        ?int $idAbonnement = null
    ) {
        $this->typeAbonnement = $typeAbonnement;
        $this->dateDebut = $dateDebut;
        $this->dateFin = $dateFin;
        $this->idAdherent = $idAdherent;
        $this->idAbonnement = $idAbonnement;
    }

    // ----- Getters -----
    public function getIdAbonnement(): ?int
    {
        return $this->idAbonnement;
    }

    public function getTypeAbonnement(): string
    {
        return $this->typeAbonnement;
    }

    public function getDateDebut(): string
    {
        return $this->dateDebut;
    }

    public function getDateFin(): string
    {
        return $this->dateFin;
    }

    public function getIdAdherent(): int
    {
        return $this->idAdherent;
    }

    // ----- Setters -----
    public function setIdAbonnement(int $idAbonnement): void
    {
        $this->idAbonnement = $idAbonnement;
    }

    public function setTypeAbonnement(string $typeAbonnement): void
    {
        $this->typeAbonnement = $typeAbonnement;
    }

    public function setDateDebut(string $dateDebut): void
    {
        $this->dateDebut = $dateDebut;
    }

    public function setDateFin(string $dateFin): void
    {
        $this->dateFin = $dateFin;
    }

    public function setIdAdherent(int $idAdherent): void
    {
        $this->idAdherent = $idAdherent;
    }

    /**
     * Vérifie si l'abonnement est valide à une date donnée (par défaut: aujourd'hui).
     */
    public function estValide(?string $dateReference = null): bool
    {
        $reference = $dateReference ?? date('Y-m-d');
        return $reference >= $this->dateDebut && $reference <= $this->dateFin;
    }

    public function toArray(): array
    {
        return [
            'id_abonnement'   => $this->idAbonnement,
            'type_abonnement' => $this->typeAbonnement,
            'date_debut'      => $this->dateDebut,
            'date_fin'        => $this->dateFin,
            'id_adherent'     => $this->idAdherent,
        ];
    }
}
