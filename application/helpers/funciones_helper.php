<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');



if (!function_exists('jsonRemoveUnicodeSequences')) {

    function jsonRemoveUnicodeSequences($struct) {
        return preg_replace("/\\\\u([a-f0-9]{4})/e", "iconv('UCS-4LE','UTF-8',pack('V', hexdec('U$1')))", json_encode($struct));
    }

}

if (!function_exists('mostrarTildesJson')) {

    function mostrarTildesJson($struct) {
        $rep = preg_replace("/\\\\u([a-f0-9]{4})/e", "iconv('UCS-4LE','UTF-8',pack('V', hexdec('U$1')))", json_encode($struct));
        return stripslashes($rep);
    }

}

if (!function_exists('verificar_resultado')) {

    function verificar_resultado($valor){
        if(is_int($valor)):
            return intval($valor);
        else:
            return utf8_encode($valor);
        endif;
    }
}