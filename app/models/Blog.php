<?php 
namespace Website\Models;
use Phalcon\Mvc\Model;

class Blog extends BaseModel{
	public $id;
    public $blog_name;
    public $sapo;
    public $content;
    public $author;
    public $id_category;
    public $created_at;
    public $updated_at;

    public function initialize()
    {
        $this->setSource("blog");
    }
}
?>