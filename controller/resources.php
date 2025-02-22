<?php

    include_once ("./model/crud.php");
    

    function _Get ($resource, $id=null) {
        
        $results = getSelect($resource, $id);
        $msgErro = $id ? "id não encontrado" : "Recurso não encontrado";
        if ( $results ) {
            echo json_encode(array("status"=>"success","data"=>$results));
        } else{
            header('HTTP/1.1 404 Not Found');
            echo json_encode(array("status"=>"error","data"=>$msgErro));
        }
        
    }

    function _Post($resource) {
        $data = json_decode(file_get_contents('php://input'), true);
    
        if ($data) {
            if (!validarDados($data, $resource)) {
                
                header('HTTP/1.1 400 Bad Request');
                echo json_encode(array("status" => "error", "data" => "Este endereço de e-mail ou celular já está sendo utilizado."));
            }
            elseif (!validarData($data)) {
                header('HTTP/1.1 400 Bad Request');
                echo json_encode(array("status" => "error", "data" => "A data de nascimento não pode ser hoje ou uma data futura."));
            }
        elseif (empty($data['email'])||empty($data['celular'])||empty($data['nome'])||empty($data['data_nascimento'])||empty($data['id_profissao'])) {
            header('HTTP/1.1 400 Bad Request');
            echo json_encode(array("status" => "error", "data" => "Existem campos requeridos que não foram preenchidos"));
                } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                    header('HTTP/1.1 400 Bad Request');
                    echo json_encode(array("status" => "error", "data" => "O email inserido não é valido"));        
                }
            else {
                $result = setInsert($resource, $data);
                if ($result) {
                    header('HTTP/1.1 201 Created');
                    echo json_encode(array("status" => "success", "data" => $data));
                } else {
                    header('HTTP/1.1 400 Bad Request');
                    echo json_encode(array("status" => "error", "data" => "Erro ao inserir os dados."));
                }
            }
        } else {
            header('HTTP/1.1 400 Bad Request');
            echo json_encode(array("status" => "error", "data" => "Faltam dados para cadastrar."));
        }
        exit;
    }
    

    function _Put ( $resource, $id ) {
        $data = json_decode(file_get_contents('php://input'),true);
        
        if ($data) {
            if (!validarDados($data, $resource)) {
                header('HTTP/1.1 400 Bad Request');
                echo json_encode(array("status" => "error", "data" => "Este endereço de e-mail ou celular já está sendo utilizado."));
            }
            elseif (!validarData($data)) {
                header('HTTP/1.1 400 Bad Request');
                echo json_encode(array("status" => "error", "data" => "A data de nascimento não pode ser hoje ou uma data futura."));
            }
        elseif (empty($data['email'])||empty($data['celular'])||empty($data['nome'])||empty($data['data_nascimento'])||empty($data['id_profissao'])) {
            header('HTTP/1.1 400 Bad Request');
            echo json_encode(array("status" => "error", "data" => "Existem campos requeridos que não foram preenchidos"));
                } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                    header('HTTP/1.1 400 Bad Request');
                    echo json_encode(array("status" => "error", "data" => "O email inserido não é valido"));        
                }
            else {
            
        
            $result = setUpdate ( $resource, $id, $data );
            $msgErro = $id ? "id não encontrado" : $data;
    
            if ( $result ) {
                header('HTTP/1.1 200 Updated');
                echo json_encode(array("status"=>"success","data"=>$data));
            }else{
                header('HTTP/1.1 400 Bad Request');
                echo json_encode(array("status"=>"error","data"=>"Falta dados para atualizar $msgErro"));
                 
            }
            }
            
        
    
    }else{
        header('HTTP/1.1 400 Bad Request');
        echo json_encode(array("status"=>"error","data"=>"Falta dados para cadastrar $data"));
    }
    exit;
}

    function _Delete ( $resource, $id ) {

        $result = setDelete ( $resource, $id );

        if ( $result ) {
            header('HTTP/1.1 200 Deleted');
            echo json_encode(array("status"=>"success","data"=>"Registro excluido"));
        }else{
            header('HTTP/1.1 400 Bad Request');
            echo json_encode(array("status"=>"error","data"=>"ID não encontrado"));
        }
        exit;
    }