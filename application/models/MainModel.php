<?php
class MainModel extends CI_Model
{
    function __construct()
    {
        $this->load->database();
    }
    public function login($user, $pass, $rol)
    {
        $data = $this->db->get_where('usuarios', array('nombre' => $user, 'password' => $pass, 'rol' => $rol), 1);
        if ($data->result_array()) {

            return $data->row();
        } else {
            return false;
        }
    }
    public function registerUser($usuario,$contraseña){
        $data = $this->db->get_where('usuarios',array('nombre'=> $usuario),1);
       
        if(!$data->result_array()){
            $arrayInsert = array(
                'nombre'=>$usuario,
                'password'=>$contraseña,
                'rol'=>'user'
                
            );
            $this->db->insert('usuarios', $arrayInsert);
            $data2 = $this->db->get_where('usuarios',array('nombre'=> $usuario),1);
            $data2 = $data2->row();
           
            $response['status'] = true;
            $response['id_usuario']= $data2->id_usuario; 
            $response['rol']=$data2->rol;
            
        }else{
            
            $response['status']=false;
            $response['msg']= "Usuario no disponible.";
        }
        return $response;
        die;

    }

    public function addCategoriaPadre($nombre)
    {
        $arrayInsert = array(
            'nombre' => $nombre
        );
        $this->db->insert('categoriaPadre', $arrayInsert);

        if ($this->db->affected_rows() > 0) {



            $response['status'] = true;
        } else {
            $response['status'] = false;
            $response['msj'] = "error al crear la categoria.";
        }
        return $response;
    }
    public function addCategoria($nombre, $urlFile, $idCatPadre)
    {
        $arrayInsert = array(
            'nombre' => $nombre,
            'foto' => $urlFile,
            'id_categoriaPadre' => $idCatPadre
        );
        $this->db->insert('categoria', $arrayInsert);

        if ($this->db->affected_rows() > 0) {



            $response['status'] = true;
        } else {
            $response['status'] = false;
            $response['msj'] = "error al crear la categoria.";
        }
        return $response;
    }
    public function listarCategoriasPadre()
    {
        $data = $this->db->get('categoriaPadre');
        if ($data->result_array()) {

            return $data->result_array();
        } else {
            return false;
        }
    }
    public function listarCategorias()
    {
        $data = $this->db->get('categoria');
        if ($data->result_array()) {

            return $data->result_array();
        } else {
            return false;
        }
    }
    public function addProducto($nombre, $precio, $precioDolar, $descripcion, $id_categoria)
    {
        $arrayInsert = array(
            'nombre' => $nombre,
            'precio' => $precio,
            'precioDolar' => $precioDolar,
            'descripcion' => $descripcion,
            'id_categoria' => $id_categoria
        );
        $this->db->insert('productos', $arrayInsert);

        if ($this->db->affected_rows() > 0) {



            return true;
        } else {
            return false;
        }
    }
    public function addImagenProducto($nombreProducto,$urlFile,$bandera){
        $data = $this->db->get_where('productos',array('nombre'=> $nombreProducto),1);
        if($data->result_array()){
            
            $data = $data->row();
            $idProducto = $data->id_producto;
            switch($bandera){
                case 0:
                    $dataUpdate = array(
                        'url1' => $urlFile,
                        
                );
                
                    
            break;
            case 1:
                $dataUpdate = array(
                    'url2' => $urlFile,
                    
            );
            
                break;
                case 2:
                    $dataUpdate = array(
                        'url3' => $urlFile,
                        
                );
                
                break;
            }
            $this->db->where('id_producto', $idProducto);
            $this->db->update('productos', $dataUpdate);
            if ($this->db->affected_rows() > 0) {
    
    
    
                return true;
            } else {
                return false;
            }


        }else{
            
            return false;
        }
    }
    public function getAllProductos(){
        $query = $this->db->get('productos');
        if ($query->result_array()) {

            return $query->result_array();
        } else {
            return false;
        }
      
    }
    public function getProductos($id_categoria){
        $this->db->from('productos');
        $this->db->where('id_categoria',$id_categoria);
        $query = $this->db->get();
        if ($query->result_array()) {

            return $query->result_array();
        } else {
            return false;
        }


    }
    public function getDetailProducto($idProducto){
        $data = $this->db->get_where('productos', array('id_producto' => $idProducto), 1);
        if ($data->result_array()) {

            return $data->row();
        } else {
            return false;
        }

    }
    public function getCategorias($idCatPadre){
       
        $this->db->from('categoria');
        
        $this->db->where('id_categoriaPadre',$idCatPadre);
        $data = $this->db->get();
        if ($data->result_array()) {

            return $data->result_array();
        } else {
            return false;
        }

    }
}
