<?php

 	function bindQueryParams($sql, $param_type, $param_value_array) {
        $param_value_reference[] = & $param_type;

     //   print_r($param_value_reference);die;

        for($i=0; $i<count($param_value_array); $i++) {
            $param_value_reference[] = & $param_value_array[$i];
        }

        print_r($param_value_reference);die;

        call_user_func_array(array(
            $sql,
            'bind_param'
        ), $param_value_reference);
    }

    function insert($query, $param_type, $param_value_array) {
       // $sql = $this->conn->prepare($query);
        $sql = $query;
        bindQueryParams($sql, $param_type, $param_value_array);
        $sql->execute();
    }


    $query = "INSERT INTO tbl_token_auth (username, password_hash, selector_hash, expiry_date) values (?, ?, ?,?)";
	$result = insert($query, 'ssss', array('user_name', 'random_password_hash', 'random_selector_hash', 'expiry_date'));



?>