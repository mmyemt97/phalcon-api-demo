<?php 
namespace Website\Controllers;
use SVCodebase\Validators\BaseValidate;
use Website\Models\Users;
use Website\Repos\UserRepo;


class UserController extends BaseController{

	protected $repo;

	public function onConstruct(){
		$this->repo = new UserRepo();
	}

	public function validateRegister(){
		$rules = [
			'username' => 'required',
            'password' => 'required',
            'full_name' => 'required',
            'birthday' => 'required',
            'address' => 'required',
            'phone_number' => 'required',
		];

		BaseValidate::validator($this->request->getPost(), $rules);
	}

	public function validateLogin(){
		$rules = [
            'username' => 'required',
            'password' => 'required'
        ];

        BaseValidate::validator($this->request->getPost(), $rules);

	}

	public function RegisterUserAction(){
		$this->validateRegister();

		$paramToReg = [
			'username' => $this->request->getPost('username'),
			'password' => $this->request->getPost('password'),
			'full_name' => $this->request->getPost('full_name'),
			'birthday' => $this->request->getPost('birthday'),
			'address' => $this->request->getPost('address'),
			'phone_number' => $this->request->getPost('phone_number')
		];

		$checkRegister = $this->repo->registerAction($paramToReg);

		if($checkRegister){
			return $this->outputSuccess($checkRegister);
		}else{

			$err = 'Tên đăng nhập đã có người sử dụng!';
			return $this->outputSuccess($err);
		}
	}

	public function loginAction(){
		$this->validateLogin();
		
		$username = $this->request->getPost('username');
		$password = $this->request->getPost('password');
		$paramToLogin = [
			'username'=>$username,
			'password'=>$password,
		];

		$checkLogin = $this->repo->loginAction($paramToLogin);

		if($checkLogin != false){
			$auth_jwt = $this->repo->registerJWT($username,$password);
			return $this->outputSuccess($auth_jwt);
		}
	}

}
?>