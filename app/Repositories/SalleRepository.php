<?php

require_once __DIR__ . '/../Entities/Salle.php';

/**
 * SalleRepository
 * Gère l'accès aux données de la table `salle` via PDO.
 */
class SalleRepository
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function findAll(): array
    {
        $stmt = $this->pdo->query('SELECT * FROM salle ORDER BY nom_salle');
        return array_map([$this, 'hydrate'], $stmt->fetchAll());
    }

    public function findById(int $id): ?Salle
    {
        $stmt = $this->pdo->prepare('SELECT * FROM salle WHERE id_salle = :id');
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();

        return $row ? $this->hydrate($row) : null;
    }

    public function create(Salle $salle): int
    {
        $stmt = $this->pdo->prepare(
            'INSERT INTO salle (nom_salle, adresse, ville) VALUES (:nom_salle, :adresse, :ville)'
        );
        $stmt->execute([
            'nom_salle' => $salle->getNomSalle(),
            'adresse'   => $salle->getAdresse(),
            'ville'     => $salle->getVille(),
        ]);

        return (int) $this->pdo->lastInsertId();
    }

    private function hydrate(array $row): Salle
    {
        return new Salle(
            $row['nom_salle'],
            $row['adresse'],
            $row['ville'],
            (int) $row['id_salle']
        );
    }
}
