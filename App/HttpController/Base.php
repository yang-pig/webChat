<?php
namespace App\HttpController;
use App\ViewController;

class Base extends ViewController
{

    //用来返回错误信息（json）
    function error($code, $message){
        if(!$this->response()->isEndResponse()){
            $data = Array(
                "code"   => $code ,
                "result" => "",
                "msg"    => $message
            );
            $this->response()->write(json_encode($data,JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES));
            $this->response()->withHeader('Content-type','application/json;charset=utf-8');
            $this->response()->withStatus(200);
            return true;
        }else{
            trigger_error("response has end");
            return false;
        }
    }

    //用来返回成功信息（json）
    function success($result = '', $code=1000){
        if(!$this->response()->isEndResponse()){
            $data = Array(
                "code"=>$code,
                "result"=>$result
            );
            $this->response()->write(json_encode($data,JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES));
            $this->response()->withHeader('Content-type','application/json;charset=utf-8');
            $this->response()->withStatus(200);
            return true;
        }else{
            trigger_error("response has end");
            return false;
        }
    }


    // controller 类必须实现该抽象方法，不然会报错
    function index()
    {
        parent::index();

    }

//    function write($data) {
//        $this->response()->write($data);
//    }

}