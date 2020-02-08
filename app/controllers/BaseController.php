<?php
namespace Website\Controllers;

use Website\StatusCode;

class BaseController extends \SVCodebase\Controllers\BaseController
{
    public function indexAction()
    {
        die('plan service');
    }

    public function error404Action()
    {
        $this->outputJSON(['error' => StatusCode::NOT_FOUND, 'msg' => 'Yêu cầu không hợp lệ.'], StatusCode::NOT_FOUND);
    }
}