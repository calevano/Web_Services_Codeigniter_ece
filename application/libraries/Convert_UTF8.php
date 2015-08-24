<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Convert_UTF8 {

    public function convert_row($data) {

        $converted_data = array();
        foreach ($data->result_array() as $row) {
            $converted_data = array_map('utf8_encode', $row);
        }

        return $converted_data;
    }

    public function convert_result($data) {

        $converted_data = array();
        foreach ($data->result_array() as $row) {
            array_push($converted_data, array_map('utf8_encode', $row));
        }

        return $converted_data;
    }

    public function convert_resultado($data) {
        $converted_data = array();
        foreach ($data->result_array() as $row) :
            array_push($converted_data, array_map('verificar_resultado',$row));
        endforeach;
        return $converted_data;

    }

}

?>