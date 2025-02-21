<?php

function getSelect ($resource, $id=null) {
    $conn =  include_once("./config/connnection.php");
    $sql = "SELECT * FROM $resource WHERE ?='' or id=?";
    $cmd = $conn->prepare($sql);
    $cmd->bind_param('ss',$id,$id);
    $result = $cmd->execute();
    return $result ? $cmd->get_result()->fetch_all(MYSQLI_ASSOC) : $result;
}
    function setInsert ($resource, $data) {
        $conn =  include_once("./config/connnection.php");

        $fields = implode (",", array_keys($data));
        $records = array_values($data);
        $values =  implode(',', array_fill(0, count($data), '?'));        
        $sql = "INSERT INTO $resource ( $fields ) VALUES ( $values )";
        $types = str_repeat('s', count($data));

        $cmd = $conn->prepare($sql);

        $cmd->bind_param($types,...$records);

        $result = $cmd->execute();
        return $result ;
    }

    
    function setUpdate ($resource, $id, $data) {
        $conn =  include_once("./config/connnection.php");

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
        $conn = include_once ("./config/connnection.php");
        $sql = "DELETE FROM $resource WHERE id=?";
        
        $cmd = $conn->prepare($sql);
        $cmd->bind_param('s',$id);
        $result = $cmd->execute();

        return $result;
        
    }




