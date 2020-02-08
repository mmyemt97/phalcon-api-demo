<?php 
namespace Website\Models;
use Phalcon\Mvc\Model;

class Category extends BaseModel{
	public $id;
	public $category_name;
	public $description;
	public $created_at;
	public $updated_at;

	public function initialize(){
		$this->setSource('category');
	}
}
?>