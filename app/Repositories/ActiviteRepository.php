<?php

require_once __DIR__ . '/../Entities/Activite.php';

/**
 * ActiviteRepository
 * Gère l'accès aux données de la table `activite` via PDO.
 */
class ActiviteRepository
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function findAll(): array
    {
        $stmt = $this->pdo->query('SELECT * FROM activite ORDER BY nom_activite');
        return array_map([$this, 'hydrate'], $stmt->fetchAll());
    }

    public function findById(int $id): ?Activite
    {
        $stmt = $this->pdo->prepare('SELECT * FROM activite WHERE id_activite = :id');
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();

        return $row ? $this->hydrate($row) : null;
    }

    public function create(Activite $activite): int
    {
        $stmt = $this->pdo->prepare(
            'INSERT INTO activite (nom_activite) VALUES (:nom_activite)'
        );
        $stmt->execute(['nom_activite' => $activite->getNomActivite()]);

        return (int) $this->pdo->lastInsertId();
    }

    private function hydrate(array $row): Activite
    {
        return new Activite($row['nom_activite'], (int) $row['id_activite']);
    }
}
