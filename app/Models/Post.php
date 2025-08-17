<?php

namespace App\Models;
use App\Core\Model;

class Post extends Model {

    public function __construct() {
        $this->table = "Posts";
        $this->primaryKey = "ID_Posts"; // Nombre real de la BD
        parent::__construct();
    }

}
