<?php 
namespace Website\Controllers;
use SVCodebase\Validators\BaseValidate;
use Website\Models\Category;
use Website\Repos\CategoryRepo;

class CategoryController extends BaseController{

	protected $repo;

	public function onConstruct(){
		$this->repo = new CategoryRepo();
	}

	public function indexAction(){

		$indexCat = $this->repo->indexAction();

		return $this->outputSuccess($indexCat);

	}

	public function storeAction(){

		$rules=[
			'category_name'=>'required',
			'description'=>'required',
		];

		BaseValidate::validator($this->request->getPost(), $rules);

		$paramToStore=[
			'category_name'=> $this->request->getPost('category_name'),
			'description'=>$this->request->getPost('description'),
		];

		$checkStore = $this->repo->createAction($paramToStore);
	
		return $this->outputSuccess($checkStore);
	}

	public function showAction(){
		$id = $this->request->getQuery('id');

		$checkShow = $this->repo->showAction($id);

		return $this->outputSuccess($checkShow);
	}

	public function updateAction(){
		$rules = [
			'category_name'=>'required',
			'description'=>'required',
		];

		BaseValidate::validator($this->request->getPost(), $rules);

		$paramToUpdate = [
			'category_name'=>$this->request->getPost('category_name'),
			'description'=>$this->request->getPost('description'),
		];

		$id = $this->request->getPost('id');

		$checkUpdate = $this->repo->updateAction($id, $paramToUpdate);

		if($checkUpdate){
			return $this->outputSuccess($checkUpdate);
		}else{
			$err = 'Lỗi: Không có chuyên mục này!';

			return $this->outputSuccess($err);
		}

		
	}

	public function destroyAction(){
		$id = $this->request->getPost('id');

		$checkDel = $this->repo->destroyAction($id);

		return $this->outputSuccess($checkDel);
	}
}

?>