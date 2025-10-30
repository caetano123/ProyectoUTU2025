<?php 

namespace App\Models;
use App\Core\Model;
use PDO;

class Notificacion extends Model{

    public function __construct(){
            $this->table = "Notificaciones";
            $this->primaryKey = "ID_Notificacion";

            parent::__construct();
    }

    public function findById($id) {
        $sql = "SELECT * FROM {$this->table} WHERE {$this->primaryKey} = :id";
        $stmt = $this->executeRawQuery($sql, [':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }


    public function getLatestNotifications($userId, $limit = 15) {

         $sql = "SELECT * FROM {$this->table} WHERE ID_Persona = :userId 
            ORDER BY FechaCreacion DESC LIMIT :limitValue";
            
        $params = [
        ':userId' => $userId, 
        ':limitValue' => (int) $limit];
    
         return $this->db->query($sql, $params)->fetchAll(); 
    }

    public function crearNotificacion(array $data){
        return $this->create([
            'ID_Persona' => $data['ID_Persona'] ?? null,
            'Mensaje' => $data['Mensaje'] ?? null,
            'URL' => $data['URL'] ?? null,
            'Leida' => $data['Leida'] ?? 0,
        ]);

    }

}