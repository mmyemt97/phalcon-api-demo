<?php
namespace Website\Controllers;

use Website\StatusCode;

class IndexController extends BaseController
{
    public function indexAction()
    {

        xxx('start index controller');
        die('plan service');
    }

    public function error404Action()
    {
        // $this->outputJSON(['error' => StatusCode::NOT_FOUND, 'msg' => 'Yêu cầu không hợp lệ.'], StatusCode::NOT_FOUND);
    }
}