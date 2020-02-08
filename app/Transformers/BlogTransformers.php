<?php 
namespace Website\Transformers;

use League\Fractal\TransformerAbstract;

class BlogTransformers extends TransformerAbstract{
	public function transform($item)
    {
        return [
            'id' => $item['id'],
            'blog_name' => $item['blog_name'],
            'sapo' => $item['sapo'],
            'created_at' => date("d-m H:i", strtotime($item['created_at']))
        ];
    }
}

?>