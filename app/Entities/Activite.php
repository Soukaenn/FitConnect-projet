<?php

/**
 * Entité Activite
 * Représente un type d'activité sportive (Musculation, Yoga, etc.).
 */
class Activite
{
    private ?int $idActivite;
    private string $nomActivite;

    public function __construct(string $nomActivite, ?int $idActivite = null)
    {
        $this->nomActivite = $nomActivite;
        $this->idActivite = $idActivite;
    }

    public function getIdActivite(): ?int
    {
        return $this->idActivite;
    }

    public function getNomActivite(): string
    {
        return $this->nomActivite;
    }

    public function setIdActivite(int $idActivite): void
    {
        $this->idActivite = $idActivite;
    }

    public function setNomActivite(string $nomActivite): void
    {
        $this->nomActivite = $nomActivite;
    }

    public function toArray(): array
    {
        return [
            'id_activite'  => $this->idActivite,
            'nom_activite' => $this->nomActivite,
        ];
    }
}
