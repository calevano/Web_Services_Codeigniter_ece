<?php

if (!defined('BASEPATH'))
exit('No direct script access allowed');

class Webservices_controlador extends CI_Controller {

   public function __construct() {
		parent::__construct();
      $this->load->model('webservices_model', 'modeloTablet');
   }

   public function index() {
      echo "";
   }

   public function login() {
      $array = json_decode(trim(file_get_contents('php://input')), true);
      if(is_array($array)){
         $estado = $this->modeloTablet->logeo_acceso($array['password'],$array['username']);
         $this->verificar_estado($estado,'usuario');
      }
   }

   public function sincronizar_postulante(){
      $array = json_decode(trim(file_get_contents('php://input')), true);
      if(is_array($array)):
         $estado = $this->modeloTablet->save_postulante($array['data']);
         $this->verificar_estado($estado[0]);
         //$this->verificar_estado($array['data']);
      endif;
   }

   private function verificar_estado($estado,$accion=null){
      if (!is_null($estado)) :
         if(is_null($accion)) :
            $this->output
               ->set_content_type('application/json;charset=utf-8')
               ->set_status_header('200')
               ->set_output(json_encode($estado));
         else:
            $this->output
               ->set_content_type('application/json;charset=utf-8')
               ->set_status_header('200')
               ->set_output(json_encode(array($accion => $estado)));
         endif;
      else:
         $this->output
               ->set_content_type('application/json;charset=utf-8')
               ->set_status_header('400')
               ->set_output(json_encode(array("error"=>"No hay nada")));
      endif;
   }

   public function version() {
      $estado = $this->modeloTablet->get_version();
      $this->verificar_estado($estado,'version');
   }

   public function padron(){
      $array = json_decode(trim(file_get_contents('php://input')), true);
      if(is_array($array)){
         $estado = $this->modeloTablet->obtener_padron($array['idLocal']);
         $this->verificar_estado($estado,'padron');
      }
   }
}

/* End of file home.php */
/* Location: ./application/controllers/home.php */
