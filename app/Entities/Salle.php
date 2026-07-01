<?php

/**
 * Entité Salle
 * Représente une salle de sport du réseau FitConnect.
 */
class Salle
{
    private ?int $idSalle;
    private string $nomSalle;
    private string $adresse;
    private string $ville;

    public function __construct(
        string $nomSalle,
        string $adresse,
        string $ville,
        ?int $idSalle = null
    ) {
        $this->nomSalle = $nomSalle;
        $this->adresse = $adresse;
        $this->ville = $ville;
        $this->idSalle = $idSalle;
    }

    public function getIdSalle(): ?int
    {
        return $this->idSalle;
    }

    public function getNomSalle(): string
    {
        return $this->nomSalle;
    }

    public function getAdresse(): string
    {
        return $this->adresse;
    }

    public function getVille(): string
    {
        return $this->ville;
    }

    public function setIdSalle(int $idSalle): void
    {
        $this->idSalle = $idSalle;
    }

    public function setNomSalle(string $nomSalle): void
    {
        $this->nomSalle = $nomSalle;
    }

    public function setAdresse(string $adresse): void
    {
        $this->adresse = $adresse;
    }

    public function setVille(string $ville): void
    {
        $this->ville = $ville;
    }

    public function toArray(): array
    {
        return [
            'id_salle'  => $this->idSalle,
            'nom_salle' => $this->nomSalle,
            'adresse'   => $this->adresse,
            'ville'     => $this->ville,
        ];
    }
}
