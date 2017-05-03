<?php
require_once 'model/comprobante.model.php';
require_once 'model/producto.model.php';
require_once 'model/cliente.model.php';

class ComprobanteController{
    
    private $model;
    private $pmodel;
    private $cmodel;
    
    public function __CONSTRUCT(){
        $this->model  = new ComprobanteModel();
        $this->pmodel = new ProductoModel();
        $this->cmodel = new ClienteModel();
    }
    
    public function Index(){
        require_once 'view/header.php';
        require_once 'view/comprobante/index.php';
        require_once 'view/footer.php';
    }
    
    public function Crud(){

        require_once 'view/header.php';
        require_once 'view/comprobante/editar.php';
        require_once 'view/footer.php';
    }
    
    public function Ver(){
        
        $comprobante = $this->model->Obtener($_REQUEST['id']);
        
        require_once 'view/header.php';
        require_once 'view/comprobante/ver.php';
        require_once 'view/footer.php';
    }

    public function Modificar(){
        
        $comprobante = $this->model->Obtener($_REQUEST['id']);
        require_once 'view/header.php';
        require_once 'view/comprobante/modificar.php';
        require_once 'view/footer.php';

        
    }

    public function ModiDetaPre(){
         $comprobante = $this->model->Obtener($_REQUEST['idC']);
        if($comprobante==false){
            require_once 'view/header.php';
            require_once 'view/comprobante/index.php';
            require_once 'view/footer.php';
        }else{
            require_once 'view/comprobante/pedido.php';
        }
    }
    
    public function Guardar()
    {

        print_r(json_encode( $this->model->Registrar( $_POST ) ));

    }

    public function EditarPre()
    {

        /*print_r(json_encode($this->model->EditarPre( $_POST ) ));*/
        if($this->model->EditarPre( $_POST ) ){
            require_once 'view/header.php';
            require_once 'view/comprobante/index.php';
            require_once 'view/footer.php';
        }else{
            require_once 'view/header.php';
            require_once 'view/comprobante/index.php';
            require_once 'view/footer.php';
        }


    }

    public function GuardaPreFactura()
    {

        print_r(json_encode( $this->model->RegistraPreFactura( $_POST )));

    }
    
    public function EliminarProdDeta(){
        $this->model->EliminarProdDeta($_REQUEST['id'],$_REQUEST['idProd']);
        require_once 'view/header.php';
        require_once 'view/footer.php';
        header('Location: view/comprobante/modificar.php');
    }

    public function Eliminar(){
        $this->model->Eliminar($_REQUEST['id']);
        header('Location: index.php');
    }

    public function EliminarF(){
       if($this->model->EliminarF($_REQUEST['id'])){
           require_once 'view/header.php';
                       require_once 'view/comprobante/index.php';
                       require_once 'view/footer.php';
                   }else{
                       require_once 'view/header.php';
                       require_once 'view/comprobante/index.php';
                       require_once 'view/footer.php';
                   }
    }
    
    public function ClienteBuscar()
    {
        print_r(json_encode(
            $this->cmodel->Buscar($_REQUEST['criterio'])
        ));
    }
    
    public function ProductoBuscar()
    {
        print_r(json_encode(
            $this->pmodel->Buscar($_REQUEST['criterio'])
        ));
    }
    
    public function Listar()
    {
        print_r($this->model->Listar());  
    }

    public function ListarCliente()
    {
        print_r($this->model->ListarCliente($_GET['idC']));  
    }
}