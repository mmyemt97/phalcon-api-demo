<?php 
namespace Website\Models;

class Users extends BaseModel{
	public $id;
	public $username;
	public $password;
	public $full_name;
	public $birthday;
	public $address;
	public $phone_number;
	public $created_at;
	public $updated_at;

	public function initialize(){
		$this->setSource('users');
	}
}
?>