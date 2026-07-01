<?php

require_once __DIR__ . '/../Entities/Abonnement.php';

/**
 * AbonnementRepository
 * Gère l'accès aux données de la table `abonnement` via PDO.
 */
class AbonnementRepository
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function findAll(): array
    {
        $stmt = $this->pdo->query(
            'SELECT * FROM abonnement ORDER BY date_debut DESC'
        );

        return array_map([$this, 'hydrate'], $stmt->fetchAll());
    }

    public function findById(int $id): ?Abonnement
    {
        $stmt = $this->pdo->prepare(
            'SELECT * FROM abonnement WHERE id_abonnement = :id'
        );
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();

        return $row ? $this->hydrate($row) : null;
    }

    /**
     * Récupère le dernier abonnement (en cours ou le plus récent) d'un adhérent.
     * Règle de gestion : un adhérent ne détient qu'un seul abonnement actif à la fois.
     */
    public function findCurrentByAdherent(int $idAdherent): ?Abonnement
    {
        $stmt = $this->pdo->prepare(
            'SELECT * FROM abonnement
             WHERE id_adherent = :id_adherent
             ORDER BY date_fin DESC
             LIMIT 1'
        );
        $stmt->execute(['id_adherent' => $idAdherent]);
        $row = $stmt->fetch();

        return $row ? $this->hydrate($row) : null;
    }

    public function findAllByAdherent(int $idAdherent): array
    {
        $stmt = $this->pdo->prepare(
            'SELECT * FROM abonnement WHERE id_adherent = :id_adherent ORDER BY date_debut DESC'
        );
        $stmt->execute(['id_adherent' => $idAdherent]);

        return array_map([$this, 'hydrate'], $stmt->fetchAll());
    }

    public function create(Abonnement $abonnement): int
    {
        $stmt = $this->pdo->prepare(
            'INSERT INTO abonnement (type_abonnement, date_debut, date_fin, id_adherent)
             VALUES (:type_abonnement, :date_debut, :date_fin, :id_adherent)'
        );

        $stmt->execute([
            'type_abonnement' => $abonnement->getTypeAbonnement(),
            'date_debut'      => $abonnement->getDateDebut(),
            'date_fin'        => $abonnement->getDateFin(),
            'id_adherent'     => $abonnement->getIdAdherent(),
        ]);

        return (int) $this->pdo->lastInsertId();
    }

    public function update(Abonnement $abonnement): bool
    {
        $stmt = $this->pdo->prepare(
            'UPDATE abonnement
             SET type_abonnement = :type_abonnement, date_debut = :date_debut, date_fin = :date_fin
             WHERE id_abonnement = :id'
        );

        return $stmt->execute([
            'type_abonnement' => $abonnement->getTypeAbonnement(),
            'date_debut'      => $abonnement->getDateDebut(),
            'date_fin'        => $abonnement->getDateFin(),
            'id'              => $abonnement->getIdAbonnement(),
        ]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->pdo->prepare('DELETE FROM abonnement WHERE id_abonnement = :id');
        return $stmt->execute(['id' => $id]);
    }

    public function hasAbonnementEnCours(int $idAdherent): bool
    {
        $abonnement = $this->findCurrentByAdherent($idAdherent);
        return $abonnement !== null && $abonnement->estValide();
    }

    private function hydrate(array $row): Abonnement
    {
        return new Abonnement(
            $row['type_abonnement'],
            $row['date_debut'],
            $row['date_fin'],
            (int) $row['id_adherent'],
            (int) $row['id_abonnement']
        );
    }
}
