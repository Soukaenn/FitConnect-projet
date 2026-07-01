<?php

require_once __DIR__ . '/../Entities/Seance.php';

/**
 * SeanceRepository
 * Gère l'accès aux données de la table `seance` via PDO.
 */
class SeanceRepository
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function findAll(): array
    {
        $stmt = $this->pdo->query(
            'SELECT * FROM seance ORDER BY date_seance DESC'
        );

        return array_map([$this, 'hydrate'], $stmt->fetchAll());
    }

    /**
     * Liste des séances avec libellés joints (adhérent, salle, activité, équipement)
     * utile pour l'affichage dans les vues / dashboard.
     */
    public function findAllWithDetails(): array
    {
        $sql = 'SELECT s.*,
                       a.nom AS nom_adherent, a.prenom AS prenom_adherent,
                       sa.nom_salle,
                       act.nom_activite,
                       eq.nom_equipement
                FROM seance s
                JOIN adherent a   ON a.id_adherent = s.id_adherent
                JOIN salle sa     ON sa.id_salle = s.id_salle
                JOIN activite act ON act.id_activite = s.id_activite
                LEFT JOIN equipement eq ON eq.id_equipement = s.id_equipement
                ORDER BY s.date_seance DESC';

        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll();
    }

    public function findByIdWithDetails(int $id): ?array
    {
        $sql = 'SELECT s.*,
                       a.nom AS nom_adherent, a.prenom AS prenom_adherent,
                       sa.nom_salle,
                       act.nom_activite,
                       eq.nom_equipement
                FROM seance s
                JOIN adherent a   ON a.id_adherent = s.id_adherent
                JOIN salle sa     ON sa.id_salle = s.id_salle
                JOIN activite act ON act.id_activite = s.id_activite
                LEFT JOIN equipement eq ON eq.id_equipement = s.id_equipement
                WHERE s.id_seance = :id';

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();

        return $row ?: null;
    }

    public function findById(int $id): ?Seance
    {
        $stmt = $this->pdo->prepare('SELECT * FROM seance WHERE id_seance = :id');
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();

        return $row ? $this->hydrate($row) : null;
    }

    public function findByAdherent(int $idAdherent): array
    {
        $stmt = $this->pdo->prepare(
            'SELECT * FROM seance WHERE id_adherent = :id_adherent ORDER BY date_seance DESC'
        );
        $stmt->execute(['id_adherent' => $idAdherent]);

        return array_map([$this, 'hydrate'], $stmt->fetchAll());
    }

    public function findBySalle(int $idSalle): array
    {
        $stmt = $this->pdo->prepare(
            'SELECT * FROM seance WHERE id_salle = :id_salle ORDER BY date_seance DESC'
        );
        $stmt->execute(['id_salle' => $idSalle]);

        return array_map([$this, 'hydrate'], $stmt->fetchAll());
    }

    public function create(Seance $seance): int
    {
        $stmt = $this->pdo->prepare(
            'INSERT INTO seance (date_seance, duree, id_equipement, id_activite, id_salle, id_adherent)
             VALUES (:date_seance, :duree, :id_equipement, :id_activite, :id_salle, :id_adherent)'
        );

        $stmt->execute([
            'date_seance'   => $seance->getDateSeance(),
            'duree'         => $seance->getDuree(),
            'id_equipement' => $seance->getIdEquipement(),
            'id_activite'   => $seance->getIdActivite(),
            'id_salle'      => $seance->getIdSalle(),
            'id_adherent'   => $seance->getIdAdherent(),
        ]);

        return (int) $this->pdo->lastInsertId();
    }

    public function update(Seance $seance): bool
    {
        $stmt = $this->pdo->prepare(
            'UPDATE seance
             SET date_seance = :date_seance, duree = :duree, id_equipement = :id_equipement,
                 id_activite = :id_activite, id_salle = :id_salle, id_adherent = :id_adherent
             WHERE id_seance = :id'
        );

        return $stmt->execute([
            'date_seance'   => $seance->getDateSeance(),
            'duree'         => $seance->getDuree(),
            'id_equipement' => $seance->getIdEquipement(),
            'id_activite'   => $seance->getIdActivite(),
            'id_salle'      => $seance->getIdSalle(),
            'id_adherent'   => $seance->getIdAdherent(),
            'id'            => $seance->getIdSeance(),
        ]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->pdo->prepare('DELETE FROM seance WHERE id_seance = :id');
        return $stmt->execute(['id' => $id]);
    }

    public function countByAdherent(int $idAdherent): int
    {
        $stmt = $this->pdo->prepare(
            'SELECT COUNT(*) AS total FROM seance WHERE id_adherent = :id'
        );
        $stmt->execute(['id' => $idAdherent]);

        return (int) $stmt->fetch()['total'];
    }

    private function hydrate(array $row): Seance
    {
        return new Seance(
            $row['date_seance'],
            (int) $row['duree'],
            (int) $row['id_activite'],
            (int) $row['id_salle'],
            (int) $row['id_adherent'],
            $row['id_equipement'] !== null ? (int) $row['id_equipement'] : null,
            (int) $row['id_seance']
        );
    }
}
