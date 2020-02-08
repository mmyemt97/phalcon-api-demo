<?php 
namespace Website\Repos;

use Website\Models\Blog;

class BlogRepo extends BaseRepo{
	public function createAction(array $param){
		$h = Blog::buildModel();
		$blog = $h->newModel();

		return $blog->create($param);
	}

	public function getAll(){
		$h = Blog::buildModel();
		return $h->limit(5)->paginate();
	}

	public function showAction($id){
		$h = Blog::buildModel();

		return $h->where("id = ".$id)->get();
	}

	public function deleteAction($id){
		$h = Blog::buildModel();
		return $h->where("id=".$id)->delete();
	}

	public function updateAction($id, array $param){
		$h = Blog::buildModel();
		// $blog = $h->newModel();
		return  $h->where("id=".$id)->update($param);
	}
}
?>