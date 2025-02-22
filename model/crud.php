<?php


include_once("./config/connnection.php");
function getSelect ($resource, $id=null) {
            $conn =  conectar();
    $sql = "SELECT * FROM $resource WHERE ?='' or id=?";
    $cmd = $conn->prepare($sql);
    $cmd->bind_param('ss',$id,$id);
    $result = $cmd->execute();
    return $result ? $cmd->get_result()->fetch_all(MYSQLI_ASSOC) : $result;
}
    function setInsert ($resource, $data) {

        $conn =  conectar();

        // if (!($conn instanceof mysqli)) {
        //     var_dump($conn);
        // }
        // if ($conn->ping()) {
        //     echo "A conexão ainda está ativa.<br>";
        // } else {
        //     echo "A conexão foi encerrada.<br>";
        // }

        // if (isset($stmt) && $stmt !== false) {
        //     $stmt->close(); // Fecha o prepared statement anterior
        // }
        
        
        $fields = implode (",", array_keys($data));
        $records = array_values($data);
        $values = implode(',', array_fill(0, count($data), '?'));
        $sql = "INSERT INTO $resource ( $fields ) VALUES ( $values )";
        $types = str_repeat('s', count($data));
        $cmd = $conn->prepare($sql);
        $cmd->bind_param($types,...$records);
        $result = $cmd->execute();
        
        // if (!$stmt) {
        //     die("Erro ao preparar a consulta: " . mysqli_error($conn));
        // }
    
        // // Executar a query
        // if (!mysqli_stmt_execute($stmt)) {
        //     die("Erro ao executar a consulta: " . mysqli_stmt_error($stmt));
        // }

        return $result ;
    }

    
    function setUpdate ($resource, $id, $data) {
                $conn =  conectar();

        $update = implode ("=?,", array_keys($data)) . "=?";
        $records = array_values($data);
        $types = str_repeat('s', count($data));
        $sql = "UPDATE $resource SET $update WHERE id=$id";

        $cmd = $conn->prepare($sql);
        $cmd->bind_param($types,...$records);

        $result = $cmd->execute();
        return $result ;
        
    }

    function setDelete ( $resource, $id ) {
        $conn = conectar();
        $sql = "DELETE FROM $resource WHERE id=?";
        
        $cmd = $conn->prepare($sql);
        $cmd->bind_param('s',$id);
        $result = $cmd->execute();

        return $result;
        
    }

    function validarDados($data, $resource) {
        // $conn = include_once("./config/connnection.php");
      
        $conn =  conectar();
        // sleep(1);

        $sql = "SELECT * FROM $resource WHERE email = ? OR celular = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $data['email'], $data['celular']);
        $stmt->execute();
        $result = $stmt->get_result();
       
        // $conn->close();
        // $stmt->close();

        return ($result->num_rows === 0);
    }

    function validarData($data) {
        $dataAtual = date("Y-m-d");
        echo strtotime($data['data_nascimento']) < strtotime($dataAtual);
        return strtotime($data['data_nascimento']) < strtotime($dataAtual);
    }
    