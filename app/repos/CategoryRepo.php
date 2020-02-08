<?php 
namespace Website\Repos;
use Website\Models\Category;

class CategoryRepo extends BaseRepo{

	public function indexAction(){
		$h = Category::buildModel();

		return $h->limit(5)->paginate();
	}

	public function createAction(array $param){
		$h = Category::buildModel();

		$cat = $h->newModel();
		$stored = $cat->create($param);
		if($stored == true){
			return $stored;			
		}else{
			
			$messages = $cat->getMessages();
			$err = "Some err: ";
			foreach ($messages as $message){
				$err .= $message.', ';
			}

			return $err;
		}
	}

	public function showAction($id){
		$h = Category::buildModel();

		$item = $h->where("id='".$id."'")->get();

		if(count($item) >= 1){
			return $item;
		}else if(count($item) == 0){
			$err = "Không tồn tại bài viết này!";
			
			return $err;
		}
	}

	public function updateAction($id, array $param){
		$h = Category::buildModel();

		$count = $h->where("id=".$id)->get();

		// xxx(count($count));

		if(count($count) >= 1){
			$update = $h->where("id=".$id)->update($param);
			return $update;
		}else if(count($count) == 0){
			return FALSE;
		}

		
	}

	public function destroyAction($id){
		$h = Category::buildModel();

		$item = $h->where("id='".$id."'")->delete();

		return $item;
	}
}
?>