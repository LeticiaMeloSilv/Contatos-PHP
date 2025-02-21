<?php 
    include_once ("./controller/resources.php");
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
    header("Access-Control-Allow-Headers: Content-Type, Origin");
    header('content-Type: application/json');
    
    if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS')   exit;

    $method = $_SERVER['REQUEST_METHOD'];
    if (isset ( $_GET['url'] ) ) {
        $paths = explode('/', trim($_GET['url']));
        $resource   = array_shift($paths);
        $id         = array_shift($paths);
        routes($method, $resource, $id);
    }
    echo$_GET['url'];

    function routes($method, $resource, $id) {

        $AllowResouces = ['contatos', 'profissoes'];
        $AllowMethod = ['POST','PUT','DELETE','GET'];
        
        if (in_array($method,$AllowMethod)){
            if (in_array ( $resource, $AllowResouces)){
                if($method=="Get" && $id){
                    call_user_func_array("_GetId", args: array("tbl_".$resource, $id));
                }
                call_user_func_array("_" . $method, args: array("tbl_".$resource, $id));
            
            }else
                echo json_encode(array("status"=>"error","data"=>"Recurso não encontrado","recursos disponiveis"=>implode("|",$AllowResouces)));
                header('HTTP/1.1 404 Not Found');
                header('Resouces: ' . implode(",",$AllowResouces));
                exit;
        } else {
            echo json_encode(array("status"=>"error","data"=>"Metodo não permitido","Metodos permitidos"=>"POST, PUT, DELETE, GET"));
            header('HTTP/1.1 405 Method Not Allowed');
            header('Allow: POST, PUT, DELETE, GET');
            exit;
        }
    }