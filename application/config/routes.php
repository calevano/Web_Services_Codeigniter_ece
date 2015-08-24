<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


$route['default_controller'] = "webservices_controlador";
$route['tablet-login'] = "webservices_controlador/login";
$route['tablet-version'] = "webservices_controlador/version";
$route['tablet-padron'] = "webservices_controlador/padron";
$route['tablet-sincronizar-padron'] = "webservices_controlador/sincronizar_postulante";
$route['404_override'] = '';


/* End of file routes.php */
/* Location: ./application/config/routes.php */