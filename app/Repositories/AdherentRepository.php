<?php

require_once __DIR__ . '/../Entities/Adherent.php';

/**
 * AdherentRepository
 * Gère l'accès aux données de la table `adherent` exclusivement via PDO,
 * avec des requêtes systématiquement paramétrées.
 */
class AdherentRepository
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function findAll(): array
    {
        $stmt = $this->pdo->query(
            'SELECT * FROM adherent ORDER BY nom, prenom'
        );
        $rows = $stmt->fetchAll();

        return array_map([$this, 'hydrate'], $rows);
    }

    public function findById(int $id): ?Adherent
    {
        $stmt = $this->pdo->prepare(
            'SELECT * FROM adherent WHERE id_adherent = :id'
        );
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();

        return $row ? $this->hydrate($row) : null;
    }

    public function findByEmail(string $email): ?Adherent
    {
        $stmt = $this->pdo->prepare(
            'SELECT * FROM adherent WHERE email = :email'
        );
        $stmt->execute(['email' => $email]);
        $row = $stmt->fetch();

        return $row ? $this->hydrate($row) : null;
    }

    public function findBySalle(int $idSalle): array
    {
        $stmt = $this->pdo->prepare(
            'SELECT * FROM adherent WHERE id_salle = :id_salle ORDER BY nom, prenom'
        );
        $stmt->execute(['id_salle' => $idSalle]);

        return array_map([$this, 'hydrate'], $stmt->fetchAll());
    }

    public function create(Adherent $adherent): int
    {
        $stmt = $this->pdo->prepare(
            'INSERT INTO adherent (nom, prenom, email, telephone, date_inscription, id_salle)
             VALUES (:nom, :prenom, :email, :telephone, :date_inscription, :id_salle)'
        );

        $stmt->execute([
            'nom'              => $adherent->getNom(),
            'prenom'           => $adherent->getPrenom(),
            'email'            => $adherent->getEmail(),
            'telephone'        => $adherent->getTelephone(),
            'date_inscription' => $adherent->getDateInscription(),
            'id_salle'         => $adherent->getIdSalle(),
        ]);

        return (int) $this->pdo->lastInsertId();
    }

    public function update(Adherent $adherent): bool
    {
        $stmt = $this->pdo->prepare(
            'UPDATE adherent
             SET nom = :nom, prenom = :prenom, email = :email,
                 telephone = :telephone, date_inscription = :date_inscription, id_salle = :id_salle
             WHERE id_adherent = :id'
        );

        return $stmt->execute([
            'nom'              => $adherent->getNom(),
            'prenom'           => $adherent->getPrenom(),
            'email'            => $adherent->getEmail(),
            'telephone'        => $adherent->getTelephone(),
            'date_inscription' => $adherent->getDateInscription(),
            'id_salle'         => $adherent->getIdSalle(),
            'id'               => $adherent->getIdAdherent(),
        ]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->pdo->prepare('DELETE FROM adherent WHERE id_adherent = :id');
        return $stmt->execute(['id' => $id]);
    }

    /**
     * Compte le nombre de séances liées à un adhérent
     * (utile pour vérifier l'intégrité avant suppression).
     */
    public function countSeances(int $idAdherent): int
    {
        $stmt = $this->pdo->prepare(
            'SELECT COUNT(*) AS total FROM seance WHERE id_adherent = :id'
        );
        $stmt->execute(['id' => $idAdherent]);

        return (int) $stmt->fetch()['total'];
    }

    private function hydrate(array $row): Adherent
    {
        return new Adherent(
            $row['nom'],
            $row['prenom'],
            $row['email'],
            $row['telephone'],
            $row['date_inscription'],
            (int) $row['id_salle'],
            (int) $row['id_adherent']
        );
    }
}
