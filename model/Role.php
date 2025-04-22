<?php
// class CRole extends CModel: SELECT `idRo`, `libelle` FROM `role`

class CRole
{
    // attributs privés
    private int $idRo;
    private string $libelle;

    // constructeur
    public function __construct(int $idRo, string $libelle)
    {
        $this->idRo = $idRo;
        $this->libelle = $libelle;
    }

    // accesseurs
    public function getIdRo(): int
    {
        return $this->idRo;
    }

    public function getLibelle(): string
    {
        return $this->libelle;
    }

    // toJson
    public function toJson(): array
    {
        return [
            'idRo' => $this->idRo,
            'libelle' => $this->libelle,
        ];
    }

    // toJsonString
    public function toJsonString(): string
    {
        return json_encode($this->toJson());
    }
}

// CRoles
class CRoles extends CPDOModel
{
    // attributs privés
    private array $roles = [];

    // constructeur
    public function __construct()
    {
        $this->loadRoles();
    }

    // méthode pour charger les rôles depuis la base de données
    private function loadRoles(): void
    {
        $query = "SELECT `idRo`, `libelle` FROM `role`";
        $stmt = $this->F_cGetDB()->prepare($query);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($result as $row) {
            $this->roles[] = new CRole($row['idRo'], $row['libelle']);
        }
    }

    // méthode pour obtenir tous les rôles
    public function getRoles(): array
    {
        return $this->roles;
    }
}