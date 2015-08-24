<?php

if (!defined('BASEPATH'))
exit('No direct script access allowed');

class Webservices_model extends CI_Model {

   public function __construct() {
      parent::__construct();
   }

   public function logeo_acceso($pass,$user) {
      $query = $this->db->query("SELECT usu.idUsu,usu.idRol,usu.usuario,usu.estado,rol.rol,rol.descripcion,uloc.id_local
                              FROM
                                 usuario AS usu
                                 LEFT JOIN rol ON usu.idRol=rol.idRol
                                 LEFT JOIN usuario_local AS uloc ON usu.idUsu=uloc.idUsu
                              WHERE
                                 usu.usuario='{$user}'
                                 AND usu.clave='{$pass}'");
      if($query->num_rows()==1){
         return $query->row_array();
      }
      return NULL;
   }

   public function get_version(){
      $query = $this->db->query('SELECT
                  TOP 1
                  nro_version,
                  usuarioCrea,
                  CONVERT(VARCHAR,fechaCrea,120) AS fechaCrea
               FROM
                  version ORDER BY nro_version DESC');
      if($query->num_rows()==1){
         return $query->row_array();
      }
      return NULL;
   }

   public function save_postulante($postulant) {
      $array_postulante = array();
      $array_postula = array();

      if (isset($postulant['postulantes'])) :
         foreach ($postulant['postulantes'] as $doc) :

            $sqlFecha="SELECT m1_fecha FROM postulante WHERE dni='{$doc['dni']}' AND m1_fecha IS NULL";
            $queryFecha=$this->db->query($sqlFecha);
            if($queryFecha->num_rows()==1) :
               if ($doc['m1_estado'] == 1) :
                  $this->db->set('m1_estado', 2);
                  $this->db->set('m1_fecha', $doc['m1_fecha']);
               endif;
               $this->db->where('dni', $doc['dni']);
               $this->db->update('postulante');
            endif;
            $sql = "SELECT
                        CASE WHEN m1_estado IS NULL THEN 0 ELSE m1_estado END AS m1_estado,
                        CONVERT(VARCHAR,m1_fecha,120) AS m1_fecha,
                        dni
                     FROM
                        postulante
                     WHERE
                        dni='{$doc['dni']}'";
            $query = $this->db->query($sql);
            $array_postula = $this->convert_utf8->convert_resultado($query);
            array_push($array_postulante,$array_postula[0]);
         endforeach;
      endif;
      $padron['postulantes'] = $array_postulante;
      $datosTotales = array();
      array_push($datosTotales, $padron);
      return $datosTotales;
   }

   public function obtener_padron($id_local) {

      $sql_local="SELECT * FROM local AS loc WHERE loc.id_local={$id_local}";
      $query_local = $this->db->query($sql_local);
      if ($query_local->num_rows() > 0) :

         //--LOCAL
         $array_local = array();
         //$array_local = $query_local->result_array();
         //$array_local = $query_local->result_array();
         $array_local = $this->convert_utf8->convert_resultado($query_local);

         //--POSTULANTES
         $array_postulante=array();
         $sql_postulante   = "SELECT * FROM postulante WHERE id_local={$id_local}";
         $query_postulante = $this->db->query($sql_postulante);
         //$array_postulante = $query_postulante->result_array();
         $array_postulante = $this->convert_utf8->convert_resultado($query_postulante);

         //--ROL
         $array_rol = array();
         //$sql_rol = "SELECT idRol,rol FROM rol";
         $sql_rol = "SELECT * FROM rol";
         $query_rol = $this->db->query($sql_rol);
         //$array_rol = $query_rol->result_array();
         $array_rol = $this->convert_utf8->convert_resultado($query_rol);

         //--CARGO
         $array_cargo=array();
         $sql_cargo="SELECT * FROM cargo";
         $query_cargo=$this->db->query($sql_cargo);
         $array_cargo = $this->convert_utf8->convert_resultado($query_cargo);

         //--VERSION
         $array_version = array();
         $sql_version = "SELECT
                           TOP 1
                           nro_version,
                           usuarioCrea,
                           CONVERT(VARCHAR,fechaCrea,120) AS fechaCrea
                        FROM
                           version ORDER BY nro_version DESC";
         $query_version = $this->db->query($sql_version);
         $array_version = $query_version->row_array();
      endif;

      $padron['local'] = $array_local;
      $padron['postulantes'] = $array_postulante;
      $padron['rol'] = $array_rol;
      $padron['version'] = $array_version;
      $padron['cargo'] = $array_cargo;
      $datosTotales = array();
      array_push($datosTotales, $padron);
      return $datosTotales[0];
   }

}
