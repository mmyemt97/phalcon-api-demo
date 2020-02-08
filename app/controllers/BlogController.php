<?php 
namespace Website\Controllers;
use SVCodebase\Validators\BaseValidate;
use Website\Models\Blog;
use Website\Repos\BlogRepo;
use Spatie\Fractalistic\Fractal;
use Website\Transformers\BlogTransformers;

class BlogController extends BaseController{

	protected $repo;

	public function onConstruct(){
		$this->repo = new BlogRepo();
	}

	public function indexAction(){

		$result =  $this->repo->getAll();

		$result->items = Fractal::create()
            ->collection($result->items->toArray())
            ->transformWith(new BlogTransformers())
            ->toArray();

		return $this->outputSuccess($result);
	}

	public function storeAction(){

		$rules=[
			'blog_name'=>'required',
			'sapo' => 'required',
			'content' => 'required',
			'id_user' => 'digit_min:1',
			'id_category' => 'digit_min:1',
		];
		BaseValidate::validator($this->request->getPost(), $rules);

		$paramToStore = [
			'blog_name' => $this->request->getPost('blog_name'),
			'sapo' => $this->request->getPost('sapo'),
			'content' => $this->request->getPost('content'),
			'id_user' => $this->request->getPost('id_user'),
			'id_category' => $this->request->getPost('id_category'),
		];

		$isSuccess = $this->repo->createAction($paramToStore);
		
		if($isSuccess === true){
			return $this->outputSuccess($isSuccess);
		}
	}


	public function showAction(){

		$rules = [
			'id' => 'required',
		];

		$request = $this->request->getQuery();
		BaseValidate::validator($request, $rules);

		$id = $this->request->getQuery('id');

		$blog = $this->repo->showAction($id);

		$result = Fractal::create()
            ->collection($blog->toArray())
            ->transformWith(function ($blog) {
                return [
		            'id' => $blog['id'],
		            'blog_name' => $blog['blog_name'],
		            'sapo' => $blog['sapo'],
		            'created_at' => date("d-m H:i", strtotime($blog['created_at']))
		        ];
            })
            ->toArray();
		
		return $this->outputSuccess($result);
	}

	public function updateAction(){

		$id = $this->request->getPost('id');
		$rules=[
			'blog_name'=>'required',
			'sapo' => 'required',
			'content' => 'required',
			'id_user' => 'required|digit_min:1',
			'id_category' => 'required|digit_min:1',
		];

		BaseValidate::validator($this->request->getPost(), $rules);

		$paramToUpdate = [
			'blog_name' => $this->request->getPost('blog_name'),
			'sapo' => $this->request->getPost('sapo'),
			'content' => $this->request->getPost('content'),
			'id_user' => $this->request->getPost('id_user'),
			'id_category' => $this->request->getPost('id_category'),
		];

		$isSuccess = $this->repo->updateAction($id, $paramToUpdate);

		if($isSuccess === true){
			return $this->outputSuccess($isSuccess);
		}
	}

	public function destroyAction(){
		$id = $this->request->getPost('id');
		// $blog = Blog::findFirst($id);
		$isSuccess = $this->repo->deleteAction($id);

		if($isSuccess === true){
			return $this->outputSuccess($isSuccess);
		}else{
			$err = "This blog doesn't exist";
			
			return $this->outputSuccess($err);
		}

	}

}
