<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Main extends CI_Controller
{

    public function __construct()
    {
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method");
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
        header("Allow: GET, POST, OPTIONS, PUT, DELETE");
        $method = $_SERVER['REQUEST_METHOD'];
        if ($method == "OPTIONS") {
            die();
        };
        parent::__construct();

        $this->load->helper('url');
        $this->load->library('session');
        
        $this->load->model('MainModel');
    }
    public function index()
    {
        echo "entra index aaaaaaaaaaaaaaaaa";
    }
    public function test()
    {
        echo "entra";
    }
    public function Validate()
    {
        $username = $this->input->post('user');
        $pass = $this->input->post('pass');
        $rol = $this->input->post('rol');
        $res = $this->MainModel->login($username, $pass, $rol);
        if ($res) {
            $data_res['status'] = True;
            $data_res['id_usuario'] = $res->id_usuario;
            $data_res['rol']= $res->rol;
            //$data_res['url']= base_url('Dashboard');
            $data = array(
                "id" => $res->id_usuario,
                "nombre_usuario" => $res->nombre,
                "is_loged" => TRUE
            );
            $this->session->set_userdata($data);
        } else {
            $data_res['status'] = False;
            $data_res['msg'] = 'Usuario o contraseña incorrectos';
        }
        /*
		var_dump($res->id_usuario);
		die;
		*/
        echo json_encode($data_res);
        die;
    }
    public function Register(){
        $usuario = $this->input->post('username');
        $contraseña = $this->input->post('pass');
        echo json_encode($this->MainModel->registerUser($usuario,$contraseña));
    }

    public function addCategoriaPadre()
    {
        $nombre = $this->input->post('categoriaP');
        echo json_encode($this->MainModel->addCategoriaPadre($nombre));
        die;
    }
    public function addCategoria()
    {
        

        if (isset($_FILES["image"]["name"])) {

            $config['upload_path'] = $_SERVER['DOCUMENT_ROOT']."/uploads/";
            $config['allowed_types'] = 'jpg|png|jpeg';
            $config['encrypt_name'] = TRUE;


            $this->load->library('upload', $config);
            $this->upload->initialize($config);

            if (!$this->upload->do_upload('image')) {
                //no sube el archivo
                echo "entraaaaaaaa";
                print_r($this->upload->display_errors());

            } else {
                //si lo sube

                $data = $this->upload->data();
                $urlFile = base_url() . 'uploads/' . $data["file_name"];

                $nombre = $this->input->post('categoria');
                $idCatPadre = $this->input->post('id_catPadre');

                echo json_encode($this->MainModel->addCategoria($nombre, $urlFile, $idCatPadre));
            }
        }else{
            echo "error: ";
            print_r($_FILES);
        }
        die;
        /*
        */
    }
    public function addProducto()
    {
        
        $nombre = $this->input->post('nombre');
        $precio = $this->input->post('precio');
        $precioDolar = $this->input->post('precioDolar');
        $descripcion = $this->input->post('descripcion');
        $id_categoria = $this->input->post('id_categoria');
        $resProd = $this->MainModel->addProducto($nombre, $precio, $precioDolar, $descripcion, $id_categoria);

        
        //echo json_encode();
        
        if ($resProd) {
            
            if (isset($_FILES["images"]['name'])) {
                

                $config['upload_path'] = './uploads/';
                $config['allowed_types'] = 'jpg|png';


                $this->load->library('upload', $config);
                $file = count($_FILES['images']['name']);
             
                $varFile = $_FILES;
                for ($i = 0; $i < $file; $i++) {

                    $_FILES['images']['name'] = $varFile['images']['name'][$i];
                    $_FILES['images']['type'] = $varFile['images']['type'][$i];
                    $_FILES['images']['tmp_name'] = $varFile['images']['tmp_name'][$i];
                    $_FILES['images']['error'] = $varFile['images']['error'][$i];
                    $_FILES['images']['size'] = $varFile['images']['size'][$i];
                    if ($this->upload->do_upload('images')) {
                        //sube el archivo
                        //print_r($this->upload->display_errors());
                        

                        $urlFile = base_url() . 'uploads/' . $varFile['images']['name'][$i];
                        if($this->MainModel->addImagenProducto($nombre,$urlFile,$i)){
                            $response = true;
                        }else{
                            $response= false;
                        }
                    } else{
                        $response=false;
                    }
                   
                    
                }
            }
        }else{
            $response=false;
           
        }
        echo json_encode($response);
    }
    public function getDetailProducto(){
        $id_producto = $this->input->post('id_producto');
        echo json_encode($this->MainModel->getDetailProducto($id_producto));
        die;
    }

    public function listarCategoriasPadre()
    {
        echo json_encode($this->MainModel->listarCategoriasPadre());
        die;
    }
    public function listarCategorias()
    {
        echo json_encode($this->MainModel->listarCategorias());
        die;
    }
    public function getAllProductos(){
        echo json_encode($this->MainModel->getAllProductos());
        die;
    }
    public function getProductos(){
     
        $id_categoria = $this->input->post('id_categoria');
      
        echo json_encode($this->MainModel->getProductos($id_categoria));
        die;
    }

    public function getCategorias(){
        $idCatPadre = $this->input->post('idCatPadre');
        echo json_encode($this->MainModel->getCategorias($idCatPadre));
        die;
    }
}
