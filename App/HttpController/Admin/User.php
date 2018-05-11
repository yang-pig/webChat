<?php
namespace App\HttpController\Admin;
use EasySwoole\Core\Http\AbstractInterface\Controller;

class User extends Controller
{

    function index()
    {

    }

    function add()
    {


        $this->response()->write('add');
    }
}