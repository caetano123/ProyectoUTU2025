<?php

namespace App\Models;

use App\Core\Model;

class Servicio extends Model
{

    public function __construct()
    {
        $this->table = "Servicios";
        $this->primaryKey = "ID_Servicio";
        parent::__construct();
    }

    public function createServicio($data)
    {
        return $this->create([
            'Nombre' => $data['Nombre'] ?? null,
            'Descripcion' => $data['Descripcion'] ?? null,
            'Precio' => $data['Precio'] ?? null,
            'ID_Categoria' => $data['ID_Categoria'] ?? null,
            'ID_Subcategoria' => $data['ID_Subcategoria'],
            'ID_Persona' => $data['ID_Persona'] ?? null,
            'ID_Zona' => $data['ID_Zona'] ?? null,
            'FechaPublicacion' => date('Y-m-d H:i:s')
        ]);
    }


    public function search($q = '', $categoria = '')
    {
        $sql = "SELECT * FROM {$this->table} WHERE 1=1";
        $params = [];

        if (!empty($q)) {
            $sql .= " AND Nombre LIKE :q";
            $params[':q'] = "%$q%";
        }

        if (!empty($categoria)) {
            $sql .= " AND ID_Categoria = :cat";
            $params[':cat'] = $categoria;
        }

        return $this->executeRawQueryArray($sql, $params);
    }

    public function getByUserId($userId)
    {
        $sql = "SELECT * FROM {$this->table} WHERE ID_Persona = :userId";
        $params = [':userId' => $userId];

        return $this->executeRawQueryArray($sql, $params);
    }


    public function findById($id)
    {
        if (is_array($id)) {
            $id = $id[0];
        }

        $sql = "SELECT * FROM {$this->table} WHERE {$this->primaryKey} = :id";
        $params = [':id' => $id];

        $result = $this->executeRawQueryArray($sql, $params);

        if (empty($result) || !is_array($result)) {
            return null;
        }

        return $result[0];
    }

    public function getByCategoryId($categoryId)
    {
        $sql = "SELECT * FROM {$this->table} WHERE ID_Categoria = :catId";
        $params = [':catId' => $categoryId];

        return $this->executeRawQueryArray($sql, $params);
    }

    public function findAll()
    {
        $sql = "SELECT * FROM {$this->table}";
        return $this->executeRawQueryArray($sql);
    }


    public function getServicios($pagina = 1, $categoriaId = null, $porPagina = 6, $query = null)
{
    $sql = "SELECT * FROM {$this->table} WHERE 1=1";
    $params = [];

    // Filtrar por ID de categoría
    if ($categoriaId) {
        $sql .= " AND ID_Categoria = :categoria";
        $params[':categoria'] = $categoriaId;
    }

    // Filtrar por búsqueda de texto
    if ($query) {
        $sql .= " AND Nombre LIKE :query";
        $params[':query'] = "%$query%";
    }

    $sql .= " ORDER BY FechaPublicacion DESC";

    // Calcular offset
    $inicio = (int)(($pagina - 1) * $porPagina);
    $porPaginaInt = (int)$porPagina;
    
    // Agregar LIMIT directamente
    $sql .= " LIMIT {$inicio}, {$porPaginaInt}";

    // Ejecutar query
    $stmt = $this->db->query($sql, $params);
    $data = $stmt->fetchAll(\PDO::FETCH_ASSOC);

    // Total registros
    $sqlCount = "SELECT COUNT(*) as total FROM {$this->table} WHERE 1=1";
    $countParams = [];

    if ($categoriaId) {
        $sqlCount .= " AND ID_Categoria = :categoria";
        $countParams[':categoria'] = $categoriaId;
    }
    if ($query) {
        $sqlCount .= " AND Nombre LIKE :query";
        $countParams[':query'] = "%$query%";
    }

    $totalRegistros = $this->db->query($sqlCount, $countParams)->fetch(\PDO::FETCH_ASSOC)['total'];
    $totalPaginas = ceil($totalRegistros / $porPagina);

    return [
        'data' => $data,
        'totalRegistros' => $totalRegistros,
        'totalPaginas' => $totalPaginas
    ];
}
}


