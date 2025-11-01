<?php

namespace App\Models;

use App\Core\Model;

class Valoracion extends Model
{
    public function __construct()
    {
        $this->table = "Valoraciones";
        $this->primaryKey = "ID_Valor";
        parent::__construct();
    }

    // Crear una valoración
    public function crearValoracion($data)
    {
        return $this->create([
            'ID_Cliente'   => $data['ID_Cliente'] ?? null,
            'ID_Proveedor' => $data['ID_Proveedor'] ?? null,
            'Puntos'       => $data['Puntos'] ?? null,
            'Comentario'   => $data['Comentario'] ?? ''
        ]);
    }

    // Obtenes todos los comentarios del proveedor
public function obtenerComentarios($idProveedor)
{
    $sql = "SELECT v.Comentario, v.Puntos, p.Nombre
            FROM {$this->table} v
            LEFT JOIN Personas p ON v.ID_Cliente = p.ID_Persona
            WHERE v.ID_Proveedor = :proveedor
            ORDER BY v.ID_Valor DESC";
    $params = [':proveedor' => $idProveedor];
    $rows = $this->executeRawQueryArray($sql, $params);

    $comentarios = [];
    foreach ($rows as $r) {
        $comentarios[] = [
            'usuario' => $r['Nombre'] ?? 'Anonimo',
            'estrellas' => (int)($r['Puntos'] ?? 0),
            'Comentario' => $r['Comentario'] ?? ''
        ];
    }
    return $comentarios;
}



    // Verificar si el cliente ya valoró a este proveedor
    public function yaValoro($idCliente, $idProveedor)
    {
        $sql = "SELECT * FROM {$this->table} WHERE ID_Cliente = :cliente AND ID_Proveedor = :proveedor";
        $params = [':cliente' => $idCliente, ':proveedor' => $idProveedor];
        return count($this->executeRawQueryArray($sql, $params)) > 0;
    }

    // Obtener promedio y total de valoraciones de un proveedor
    public function promedioValoraciones($idProveedor)
    {
        $sql = "SELECT AVG(Puntos) as promedio, COUNT(*) as total 
                FROM {$this->table} WHERE ID_Proveedor = :proveedor";
        $params = [':proveedor' => $idProveedor];
        return $this->executeRawQueryArray($sql, $params)[0] ?? ['promedio' => 0, 'total' => 0];
    }
}
