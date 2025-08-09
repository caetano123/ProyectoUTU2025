<?php

namespace App\Models;
use App\Core\Model;

class Post extends Model{

	public function __construct(){
	$this->table = "Posts";
	$this->primaryKey = "post_id";
	parent::__construct();
}

}
