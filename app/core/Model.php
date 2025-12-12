<?php
/**
 * Modèle Parent (Base Model)
 * C'est le socle de tous tes modèles (Produit, User, Panier...).
 * Il contient toutes les méthodes CRUD standard pour ne pas avoir à réécrire du SQL basique partout.
 */

class Model
{
    protected PDO $db;
    protected string $table;

    public function __construct()
    {
        // On récupère la connexion unique à la BDD (Singleton)
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Récupère TOUT le contenu de la table.
     * Par défaut, on trie par ID décroissant (du plus récent au plus vieux).
     */
    public function findAll(string $orderBy = 'id', string $order = 'DESC'): array
    {
        $sql = "SELECT * FROM {$this->table} ORDER BY {$orderBy} {$order}";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }

    /**
     * Trouve un seul élément grâce à son ID.
     */
    public function findById(int $id)
    {
        $sql = "SELECT * FROM {$this->table} WHERE id = :id LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    /**
     * Recherche générique sur une colonne spécifique.
     * Ex: findBy('category_id', 5) ou findBy('slug', 'mon-t-shirt')
     */
    public function findBy(string $column, $value): array
    {
        $sql = "SELECT * FROM {$this->table} WHERE {$column} = :value";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['value' => $value]);
        return $stmt->fetchAll();
    }

    /**
     * Idem que findBy, mais ne retourne qu'un seul résultat.
     * Utile pour trouver un utilisateur par email par exemple.
     */
    public function findOneBy(string $column, $value)
    {
        $sql = "SELECT * FROM {$this->table} WHERE {$column} = :value LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['value' => $value]);
        return $stmt->fetch();
    }

    /**
     * Insère une nouvelle ligne en base.
     * $data doit être un tableau associatif : ['colonne' => 'valeur']
     */
    public function create(array $data): int
    {
        $columns = implode(', ', array_keys($data));
        // On crée les placeholders pour la sécurité (:nom, :prix, etc.)
        $placeholders = ':' . implode(', :', array_keys($data));

        $sql = "INSERT INTO {$this->table} ({$columns}) VALUES ({$placeholders})";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($data);

        // On retourne l'ID de l'élément qu'on vient de créer
        return (int)$this->db->lastInsertId();
    }

    /**
     * Met à jour une ligne existante.
     */
    public function update(int $id, array $data): bool
    {
        $fields = '';
        foreach ($data as $key => $value) {
            $fields .= "{$key} = :{$key}, ";
        }
        $fields = rtrim($fields, ', '); // On vire la dernière virgule

        $sql = "UPDATE {$this->table} SET {$fields} WHERE id = :id";
        $data['id'] = $id;

        $stmt = $this->db->prepare($sql);
        return $stmt->execute($data);
    }

    /**
     * Supprime définitivement une ligne.
     */
    public function delete(int $id): bool
    {
        $sql = "DELETE FROM {$this->table} WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute(['id' => $id]);
    }

    // Compte simple du nombre d'éléments
    public function count(): int
    {
        $sql = "SELECT COUNT(*) FROM {$this->table}";
        $stmt = $this->db->query($sql);
        return (int)$stmt->fetchColumn();
    }

    /**
     * Pour les requêtes SQL manuelles (complexes, jointures...).
     * Retourne plusieurs résultats.
     */
    protected function query(string $sql, array $params = []): array
    {
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    /**
     * Pour les requêtes manuelles qui ne doivent retourner qu'UNE ligne.
     */
    protected function queryOne(string $sql, array $params = [])
    {
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetch();
    }

    // Vérifie si une valeur existe déjà (pratique pour l'email unique)
    public function exists(string $column, $value): bool
    {
        $sql = "SELECT COUNT(*) FROM {$this->table} WHERE {$column} = :value";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['value' => $value]);
        return (int)$stmt->fetchColumn() > 0;
    }
}