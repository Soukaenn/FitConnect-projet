<?php

/**
 * Entité Seance
 * Représente une séance d'entraînement effectuée par un adhérent.
 */
class Seance
{
    private ?int $idSeance;
    private string $dateSeance;
    private int $duree; // en minutes
    private ?int $idEquipement;
    private int $idActivite;
    private int $idSalle;
    private int $idAdherent;

    public function __construct(
        string $dateSeance,
        int $duree,
        int $idActivite,
        int $idSalle,
        int $idAdherent,
        ?int $idEquipement = null,
        ?int $idSeance = null
    ) {
        $this->dateSeance = $dateSeance;
        $this->duree = $duree;
        $this->idEquipement = $idEquipement;
        $this->idActivite = $idActivite;
        $this->idSalle = $idSalle;
        $this->idAdherent = $idAdherent;
        $this->idSeance = $idSeance;
    }

    // ----- Getters -----
    public function getIdSeance(): ?int
    {
        return $this->idSeance;
    }

    public function getDateSeance(): string
    {
        return $this->dateSeance;
    }

    public function getDuree(): int
    {
        return $this->duree;
    }

    public function getIdEquipement(): ?int
    {
        return $this->idEquipement;
    }

    public function getIdActivite(): int
    {
        return $this->idActivite;
    }

    public function getIdSalle(): int
    {
        return $this->idSalle;
    }

    public function getIdAdherent(): int
    {
        return $this->idAdherent;
    }

    // ----- Setters -----
    public function setIdSeance(int $idSeance): void
    {
        $this->idSeance = $idSeance;
    }

    public function setDateSeance(string $dateSeance): void
    {
        $this->dateSeance = $dateSeance;
    }

    public function setDuree(int $duree): void
    {
        $this->duree = $duree;
    }

    public function setIdEquipement(?int $idEquipement): void
    {
        $this->idEquipement = $idEquipement;
    }

    public function setIdActivite(int $idActivite): void
    {
        $this->idActivite = $idActivite;
    }

    public function setIdSalle(int $idSalle): void
    {
        $this->idSalle = $idSalle;
    }

    public function setIdAdherent(int $idAdherent): void
    {
        $this->idAdherent = $idAdherent;
    }

    public function toArray(): array
    {
        return [
            'id_seance'     => $this->idSeance,
            'date_seance'   => $this->dateSeance,
            'duree'         => $this->duree,
            'id_equipement' => $this->idEquipement,
            'id_activite'   => $this->idActivite,
            'id_salle'      => $this->idSalle,
            'id_adherent'   => $this->idAdherent,
        ];
    }
}
