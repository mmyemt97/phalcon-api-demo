<?php 
namespace Website\Repos;

use Website\Models\Users;
use \Phalcon\Security;
use Firebase\JWT\JWT;

class UserRepo extends BaseRepo{
	public function loginAction(array $param){
		$h = Users::buildModel();

		$username = $param['username'];
		$password = $param['password'];

		$login = $h->where("username = '".$username."'")
				->getQuery()
				->getSingleResult();		

		if($login){
			$security = new \Phalcon\Security();
            // $password = $security->checkHash($param['password'], $login->password); //Check có đúng password hay không
            $password = $this->checkPassword($password, $login->password); //Check có đúng password hay không
            // xxx($password);
            if ($password) { //if true
                return $login; //dăng nhập
            }
        }else{
        	return false;
        }
	}

	public function registerAction(array $param){
		$h = Users::buildModel();

		$user = $h->newModel();
		$username = $param['username'];
		$checkDuplicator = $this->checkDuplicator($username);
		if($checkDuplicator){
			return $user->create($param);
		}
		else{
			return false;
		}
		
	}

	public function checkPassword($pass1, $pass2){
		if($pass1 == $pass2){
			return true;
		}
	}

	public function checkDuplicator($username){
		$h = Users::buildModel();

		$check = $h->where("username = '".$username."'")->get();
		// xxx(count($check));
		if(count($check) >= 1){
			return false;
		}else{
			return true;
		}
	}

	public function registerJwt($username, $fullname){
        $key = 'phalcontestsvweb';
        $header = [
            'alg' => 'HS256',
            'typ' => 'JWT'
        ];
        $payload = [
            'iat' => time(),
            'username' => $username,
            'full_name'=> $fullname
        ];
        return $jwt = JWT::encode($payload, $key);
    }
}
?>