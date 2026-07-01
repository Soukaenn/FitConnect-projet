<?php

/**
 * Entité Adherent
 * Représente un adhérent d'une salle du réseau FitConnect.
 * Encapsulation stricte : attributs privés + accesseurs.
 */
class Adherent
{
    private ?int $idAdherent;
    private string $nom;
    private string $prenom;
    private string $email;
    private ?string $telephone;
    private string $dateInscription;
    private int $idSalle;

    public function __construct(
        string $nom,
        string $prenom,
        string $email,
        ?string $telephone,
        string $dateInscription,
        int $idSalle,
        ?int $idAdherent = null
    ) {
        $this->nom = $nom;
        $this->prenom = $prenom;
        $this->email = $email;
        $this->telephone = $telephone;
        $this->dateInscription = $dateInscription;
        $this->idSalle = $idSalle;
        $this->idAdherent = $idAdherent;
    }

    // ----- Getters -----
    public function getIdAdherent(): ?int
    {
        return $this->idAdherent;
    }

    public function getNom(): string
    {
        return $this->nom;
    }

    public function getPrenom(): string
    {
        return $this->prenom;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function getDateInscription(): string
    {
        return $this->dateInscription;
    }

    public function getIdSalle(): int
    {
        return $this->idSalle;
    }

    // ----- Setters -----
    public function setIdAdherent(int $idAdherent): void
    {
        $this->idAdherent = $idAdherent;
    }

    public function setNom(string $nom): void
    {
        $this->nom = $nom;
    }

    public function setPrenom(string $prenom): void
    {
        $this->prenom = $prenom;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function setTelephone(?string $telephone): void
    {
        $this->telephone = $telephone;
    }

    public function setDateInscription(string $dateInscription): void
    {
        $this->dateInscription = $dateInscription;
    }

    public function setIdSalle(int $idSalle): void
    {
        $this->idSalle = $idSalle;
    }

    public function getNomComplet(): string
    {
        return $this->prenom . ' ' . $this->nom;
    }

    public function toArray(): array
    {
        return [
            'id_adherent'      => $this->idAdherent,
            'nom'              => $this->nom,
            'prenom'           => $this->prenom,
            'email'            => $this->email,
            'telephone'        => $this->telephone,
            'date_inscription' => $this->dateInscription,
            'id_salle'         => $this->idSalle,
        ];
    }
}
