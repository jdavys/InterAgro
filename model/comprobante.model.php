<?php
require 'lib/anexgrid.php';



class ComprobanteModel
{
    private $pdo;
   

    public function __CONSTRUCT()
    {
        try
        {
            $this->pdo = Database::Conectar();
        }
        catch(Exception $e)
        {
            die($e->getMessage());
        }
    }

    public function ListarFact()
    {
        try
        {
            $result = array();

            $stm = $this->pdo->prepare("SELECT f.id as Factura,name as Cliente,Tipo_pago,Moneda,Fecha,FechaVence,SubTotal,Descuento,Total,Estado FROM prefactura f,person c where cliente_id=c.id");
            $stm->execute();

            foreach($stm->fetchAll(PDO::FETCH_OBJ) as $r)
            {
                $fact = new Factura();

                $fact->__SET('Cliente', $r->Cliente);
                $fact->__SET('Factura', $r->Factura);
                $fact->__SET('TipoPago', $r->Tipo_pago);
                $fact->__SET('Moneda', $r->Moneda);
                $fact->__SET('Fecha', $r->Fecha);
                $fact->__SET('FechaVence', $r->FechaVence);
                $fact->__SET('SubTotal', $r->SubTotal);
                $fact->__SET('Descuento', $r->Descuento);
                $fact->__SET('Total', $r->Total);
                $fact->__SET('Estado', $r->Estado);

                $result[] = $fact;
            }

            return $result;
        }
        catch(Exception $e)
        {
            die($e->getMessage());
        }
    }
    public function Listar()
    {
        try
        {
            /* Instanciamos AnexGRID */
            $anexGrid = new AnexGrid();
            
            /* Contamos los registros*/
            $total = $this->pdo->query("
                SELECT COUNT(*) Total
                FROM prefactura
            ")->fetchObject()->Total;

            $wh=" id > 0 ";

            foreach ($anexGrid->filtros as $f) {
                if($f['columna'] == 'id' ) $wh .= " AND id=".$f['valor'];
                if($f['columna'] == 'Cliente_id') $wh.= "AND Cliente_id in (SELECT id FROM person WHERE name LIKE '%".$f['valor']."%')"; 
                 if($f['columna'] == 'estado') $wh .= "AND estado = '".$f['valor']."'";
                 if($f['columna'] == 'Moneda') $wh .= "AND moneda = '".$f['valor']."'"; 
            }

            /* Nuestra consulta dinámica */
            $registros = $this->pdo->query("
                SELECT * FROM prefactura
                WHERE $wh 
                ORDER BY $anexGrid->columna $anexGrid->columna_orden
                LIMIT $anexGrid->pagina,$anexGrid->limite")->fetchAll(PDO::FETCH_ASSOC
             );

            
            foreach($registros as $k => $r)
            {
                /* Traemos los clientes que tiene asignado cada comprobante */
                $cliente = $this->pdo->query("SELECT * FROM person c WHERE c.id = " . $r['Cliente_id'])
                                ->fetch(PDO::FETCH_ASSOC);

                $registros[$k]['Cliente'] = $cliente;
                
                /* Traemos el detalle */
                $registros[$k]['Detalle'][] = $this->pdo->query("SELECT * FROM detalle_prefactura cd WHERE cd.Comprobante_id = " . $r['id'])
                                                   ->fetch(PDO::FETCH_ASSOC);
                
                foreach($registros[$k]['Detalle'] as $k1 => $d)
                {
                    $registros[$k]['Detalle'][$k1]['Producto'] = $this->pdo->query("SELECT * FROM product p WHERE p.id = " . $d['Producto_id'])
                                                                      ->fetch(PDO::FETCH_ASSOC);
                }
            }
            
            header('Content-type: application/jason');
            print_r($anexGrid->responde($registros, $total));
        }
        catch(Exception $e)
        {
            die($e->getMessage());
        }
    }


    public function ListarCliente($idCliente)
    {
        try
        {
            /* Instanciamos AnexGRID */
            $anexGrid = new AnexGrid();
            
            /* Contamos los registros*/
            $total = $this->pdo->query("
                SELECT COUNT(*) Total
                FROM prefactura")->fetchObject()->Total;

            /* Nuestra consulta dinámica */
           $registros = $this->pdo->query("SELECT p.id,Cliente_id,IGV,subTotal,Total,Tipo_Pago,Moneda,descuento,fecha,fechaVence,estado FROM prefactura p, person c WHERE name = '{$idCliente}' and c.id=cliente_id
                           ORDER BY $anexGrid->columna $anexGrid->columna_orden
                           LIMIT $anexGrid->pagina,$anexGrid->limite")->fetchAll(PDO::FETCH_ASSOC);
                      


            foreach($registros as $k => $r)
            {
                /* Traemos los clientes que tiene asignado cada comprobante */
                $cliente = $this->pdo->query("SELECT * FROM person c WHERE c.id = " . $r['Cliente_id'])
                                ->fetch(PDO::FETCH_ASSOC);

                $registros[$k]['Cliente'] = $cliente;
                
                /* Traemos el detalle */
                $registros[$k]['Detalle'][] = $this->pdo->query("SELECT * FROM detalle_prefactura cd WHERE cd.Comprobante_id = " . $r['id'])
                                                   ->fetch(PDO::FETCH_ASSOC);
                
                foreach($registros[$k]['Detalle'] as $k1 => $d)
                {
                    $registros[$k]['Detalle'][$k1]['Producto'] = $this->pdo->query("SELECT * FROM product p WHERE p.id = " . $d['Producto_id'])->fetch(PDO::FETCH_ASSOC);
                }
            }
            
            return $anexGrid->responde($registros, $total);
        }
        catch(Exception $e)
        {
            die($e->getMessage());
        }
    }



    public function Obtener($id)
    {
        try
        {
            $stm = $this->pdo->prepare("SELECT * FROM preFactura WHERE id = ?");
            $stm->execute(array($id));
            
            $c = $stm->fetch(PDO::FETCH_OBJ);
            
            /* El cliente asignado */
            $c->{'Cliente'} = $this->pdo->query("SELECT * FROM person c WHERE c.id = " . $c->Cliente_id)
                                        ->fetch(PDO::FETCH_OBJ);

            /* Traemos el detalle */
            $c->{'Detalle'} = $this->pdo->query("SELECT * FROM detalle_prefactura cd WHERE cd.Comprobante_id = " . $c->id)
                                        ->fetchAll(PDO::FETCH_OBJ);

            foreach($c->Detalle as $k => $d)
            {
                $c->Detalle[$k]->{'Producto'} = $this->pdo->query("SELECT * FROM product p WHERE p.id = " . $d->Producto_id)
                                                          ->fetch(PDO::FETCH_OBJ);
            }
            
            return $c;
        }
        catch(Exception $e)
        {
            die($e->getMessage());
        }
    }

    public function Eliminar($id)
    {
        try 
        {


            $stm = $this->pdo->prepare("DELETE from  prefactura  WHERE id = ?");
            $stm->execute(array($id));

            /*$stm2 = $this->pdo->prepare("DELETE cuenta_cobrar  WHERE id_comprobante = ?");
            $stm2->execute(array($id));*/


            $stm4 = $this->pdo->prepare("ALTER TABLE prefactura AUTO_INCREMENT =".$id);
            $stm4->execute();

                           
        }
        catch (Exception $e) 
        {
            die($e->getMessage());
        }
    }

    public function EliminarF($id)
    {
            try 
            {

                 /* Nuestra consulta dinámica */
                $registros = $this->pdo->query("SELECT * FROM comprobante_detalle 
                    WHERE comprobante_id='{$id}'")->fetchAll(PDO::FETCH_ASSOC);

                
                //var_dump($registros);

            
                foreach ($registros as $det => $r) {
                
                    $cantDet=$r['Cantidad'];
                    
                    $P = $this->pdo->prepare("SELECT * FROM product WHERE id = ?");
                    $P->execute(array($r['Producto_id']));

                    $dp=$P->fetch(PDO::FETCH_ASSOC);

                    
                    /*$canTot=$dp['cantidadExiste']+$cantDet;

 

                    $sql = "UPDATE product set
                        cantidadExiste=?
                        WHERE id=?";
                    $this->pdo->prepare($sql)
                          ->execute(
                            array(
                                $canTot,
                                $dp['id']                                        
                            ));*/

                    $sql = "INSERT into operation (product_id,q,operation_type_id,bodega_id) 
                            VALUES ( ?, ?, ?, ?)";
                    $this->pdo->prepare($sql)
                              ->execute(
                                array(
                                        $r['Producto_id'],
                                        $cantDet,
                                        1,
                                        1
                                ));                   

                   
                    
                }
                
                $stm = $this->pdo->prepare("SELECT * FROM cuenta_cobrar WHERE id_comprobante = ?");
                $stm->execute(array($id));
                
                $c = $stm->fetch(PDO::FETCH_ASSOC);

                $saldo=0;
                if($c['tipo_pago']=='Credito'){
                    if($c['moneda']=="COLONES"){
                        $saldo=$c['saldo_Global'] - $c['saldo_Factura'];
                        $stmU = $this->pdo->prepare("UPDATE cuenta_cobrar set saldo_Global={$saldo} WHERE id_cliente = ?");
                        $stmU->execute(array($c['id_Cliente']));
                    }else{
                        $stmU = $this->pdo->prepare("UPDATE cuenta_cobrar set saldo_global_dolares={$saldo} WHERE id_cliente = ?");
                        $stmU->execute(array($c['id_Cliente']));
                    }
                }
                

                $stm0 = $this->pdo->prepare("DELETE from  prefactura  WHERE id = ?");
                $stm0->execute(array($id));

                $stm1 = $this->pdo->prepare("DELETE from cuenta_cobrar WHERE id_comprobante = ?");
                $stm1->execute(array($id));

                $stm = $this->pdo->prepare("DELETE from  comprobante  WHERE id = ?");
                $stm->execute(array($id));
                /*$est="ANULADO";
                $salF=0;
                $sql = "UPDATE prefactura set
                        estado=?
                        WHERE id=?";
                $this->pdo->prepare($sql)
                          ->execute(
                            array(
                                $est,
                                $id                                           
                            ));

                $sql = "UPDATE comprobante set
                        estado=?
                        WHERE id=?";
                $this->pdo->prepare($sql)
                          ->execute(
                            array(
                                $est,
                                $id                                           
                            ));
                $sql = "UPDATE cuenta_cobrar set
                        estado=?,
                        saldo_Factura=?
                        WHERE id_comprobante=?";
                $this->pdo->prepare($sql)
                          ->execute(
                            array(
                                $est,
                                $salF,
                                $id                                           
                            ));*/

                

                $stm3 = $this->pdo->prepare("ALTER TABLE prefactura AUTO_INCREMENT =".$id);
                $stm3->execute();
                $stm4 = $this->pdo->prepare("ALTER TABLE comprobante AUTO_INCREMENT =".$id);
                $stm4->execute();

                               
            }
            catch (Exception $e) 
            {
                die($e->getMessage());
            }
        }
        

    public function EliminarProdDeta($id,$idProd)
    {
        try 
        {

            $stm0 = $this->pdo->prepare("DELETE from  detalle_prefactura  WHERE Comprobante_id = ? and Producto_id= ?");
            $stm0->execute(array($id,
                                 $idProd));
                           
        }
        catch (Exception $e) 
        {
            die($e->getMessage());
        }
    }

    public function Registrar($comprobante)
    {
        try 
        {

            /* Registramos el comprobante */
            $sql = "INSERT INTO comprobante(Cliente_id, IGV, SubTotal, Total,Tipo_Pago,moneda,fecha,fechaVence) VALUES ( ?, ?, ?, ?, ?)";
            $this->pdo->prepare($sql)
                      ->execute(
                        array(
                            $comprobante['cliente_id'],
                            $comprobante['igv'],
                            $comprobante['subtotal'],
                            $comprobante['total'],
                            $comprobante['tipo_pago']                                                    
                        ));

            /* El ultimo ID que se ha generado */
            $comprobante_id = $this->pdo->lastInsertId();
            
            /* Recorremos el detalle para insertar */
            foreach($comprobante['items'] as $d)
            {
                $sql = "INSERT INTO comprobante_detalle (Comprobante_id,Producto_id,Presenta,Cantidad,PrecioUnitario,Descuento,Total) 
                        VALUES (?, ?, ?, ?, ?, ?, ?)";
                
                $this->pdo->prepare($sql)
                          ->execute(
                            array(
                                $comprobante_id,
                                $d['producto_id'],
                                $d['presenta'],
                                $d['cantidad'],
                                $d['precio'],
                                $d['descT'],
                                $d['total']
                            ));
            }

            return true;
        }
        catch (Exception $e) 
        {
            return false;
        }
    }

    public function EditarPre($comprobante)
    {
        try 
        {
            $stmF = $this->pdo->prepare("SELECT fecha  FROM prefactura WHERE id= ? ");
                    $stmF->execute(array($comprobante['comprob_id']));
                        $fecha=$stmF->fetch(PDO::FETCH_ASSOC);

            date_default_timezone_set('America/Costa_Rica');
            $p=$comprobante['plazo'];
            $fec=date('d-m-Y');//$fecha['fecha'];  //
            $fecPlazo=date('d-m-Y');
            $estC=$comprobante['estadoC'];
            if($p>0){
                //$fecPlazo=date($fec,strtotime("+$p day"));
                //var_dump($fecPlazo);
                $esCredito='Credito';
                $fecPlazo=date('d-m-Y',strtotime("+$p day"));

            }
            else{
                $fecPlazo=$fecha['fecha'];
            }
            $esCredito='Credito';
            $mone=$comprobante['moneda'];
            
            $cambioM=false;
            $totalC=0;
            $totalD=0;
            
            if($estC=='PREFACTURA'){
                $sql = "UPDATE prefactura set
                        moneda=?,
                        Tipo_Pago=?,
                        fecha=?,
                        fechaVence=?
                        WHERE id=?";
                $this->pdo->prepare($sql)
                          ->execute(
                            array(
                                $mone,
                                $esCredito,
                                $fec,
                                $fecPlazo,
                                $comprobante['comprob_id']                                           
                            ));
            }else{
                $sql = "UPDATE prefactura set
                        moneda=?,
                        Tipo_Pago=?,
                        fecha=?,
                        fechaVence=?
                        WHERE id=?";
                $this->pdo->prepare($sql)
                          ->execute(
                            array(
                                $mone,
                                $esCredito,
                                $fec,
                                $fecPlazo,
                                $comprobante['comprob_id']                                           
                            ));
                $sql = "UPDATE comprobante set
                        moneda=?,
                        Tipo_Pago=?,
                        fecha=?,
                        fechaVence=?
                        WHERE id=?";
                $this->pdo->prepare($sql)
                          ->execute(
                            array(
                                $mone,
                                $esCredito,
                                $fec,
                                $fecPlazo,
                                $comprobante['comprob_id']                                           
                            ));
                    $stm1 = $this->pdo->prepare("SELECT *  FROM cuenta_cobrar WHERE id_comprobante= ? ");
                    $stm1->execute(array($comprobante['comprob_id']));
                        $cue=$stm1->fetch(PDO::FETCH_ASSOC);
                          
                    if($cue['moneda'] != $mone){
                        $moneAnt=$cue['moneda'];
                        $cambioM=true;
                    }
                 
                if($esCredito =='Credito'){
                    $stm1 = $this->pdo->prepare("SELECT *  FROM cuenta_cobrar WHERE id_comprobante= ? ");
                    $stm1->execute(array($comprobante['comprob_id']));
                        $cue=$stm1->fetch(PDO::FETCH_ASSOC);
                       
                    if($cue['moneda'] != $mone){
                        $moneAnt=$cue['moneda'];
                        $cambioM=true;
                    }

                    $sql = "UPDATE cuenta_cobrar set
                            moneda=?,
                            Tipo_Pago=?,
                            fecha_Vence=?
                            WHERE id_comprobante=?";
                    $this->pdo->prepare($sql)
                              ->execute(
                                array(
                                    $mone,
                                    $esCredito,
                                    $fecPlazo,
                                    $comprobante['comprob_id']                                           
                                ));
                   
                
                    if($cambioM==false){
                        
                        $stm = $this->pdo->prepare("SELECT sum(saldo_Factura) as total FROM cuenta_cobrar WHERE id_cliente= ? and tipo_pago=?");
                        $stm->execute(array($comprobante['cliente_id'],
                            $esCredito));
                        $c = $stm->fetch(PDO::FETCH_ASSOC);
                        
                        if($mone=='COLONES'){
                             $sql = "UPDATE cuenta_cobrar set
                                    saldo_global=?
                                    WHERE id_cliente=?";
                           
                            $this->pdo->prepare($sql)
                                      ->execute(
                                        array(
                                            $c['total'] ,
                                            $comprobante['cliente_id']                                           
                                        ));

                        }else{
                             $sql = "UPDATE cuenta_cobrar set
                                    saldo_global_dolares=?
                                    WHERE id_cliente=?";
                        
                            $this->pdo->prepare($sql)
                                      ->execute(
                                        array(
                                            $c['total'],
                                            $comprobante['cliente_id']                                           
                                        ));
                        }// if moneda anterior

                    }else{// Si es un cambio de moneda TRUE

                        $stm = $this->pdo->prepare("SELECT saldo_Factura as saldo FROM cuenta_cobrar WHERE id_comprobante= ?");
                        $stm->execute(array($comprobante['comprob_id']));
                        
                        $sal = $stm->fetch(PDO::FETCH_ASSOC);

                        $stm2 = $this->pdo->prepare("SELECT moneda,saldo_Global,saldo_global_dolares  FROM cuenta_cobrar WHERE id_comprobante= ?");
                        $stm2->execute(array($comprobante['comprob_id']));

                        $glo = $stm2->fetch(PDO::FETCH_ASSOC);


                        if($moneAnt=='COLONES'){
                            $totalD=$glo['saldo_global_dolares'] + $sal['saldo'];
                             $sql = "UPDATE cuenta_cobrar set
                                    saldo_Global=0,
                                    saldo_global_dolares=?
                                    WHERE id_Cliente=?";  
                            
                            $this->pdo->prepare($sql)
                                      ->execute(
                                        array(
                                            $totalD,
                                            $comprobante['cliente_id']                                           
                                        ));

                        }else{
                            $totalC=$glo['saldo_Global'] + $sal['saldo'];
                             $sql = "UPDATE cuenta_cobrar set
                                    saldo_global_dolares=0,
                                    saldo_Global=?
                                    WHERE id_Cliente=?";  
                            
                            $this->pdo->prepare($sql)
                                      ->execute(
                                        array(
                                            $totalC,
                                            $comprobante['cliente_id']                                           
                                        ));
                        }

                    }
                }else{  //si NO ES CREDITO ES CONTADO
                    
                
                    $sql = "UPDATE cuenta_cobrar set
                            moneda=?,
                            Tipo_Pago=?,
                            fecha_Vence=?
                            WHERE id_comprobante=?";
                    $this->pdo->prepare($sql)
                              ->execute(
                                array(
                                    $mone,
                                    $esCredito,
                                    $fecPlazo,
                                    $comprobante['comprob_id']                                           
                                ));
                     
                    $stm = $this->pdo->prepare("SELECT saldo_Factura as saldo FROM cuenta_cobrar WHERE id_comprobante= ?");
                    $stm->execute(array($comprobante['comprob_id']));
                    
                    $sal = $stm->fetch(PDO::FETCH_ASSOC);
                    
                    $stm2 = $this->pdo->prepare("SELECT moneda,saldo_Global,saldo_global_dolares  FROM cuenta_cobrar WHERE id_comprobante= ?");
                    $stm2->execute(array($comprobante['comprob_id']));
                    
                    $glo = $stm2->fetch(PDO::FETCH_ASSOC);
                    
                    if($glo['saldo_Global']>0){
                        $totalC=$glo['saldo_Global'] - $sal['saldo'];
                    }
                    if($glo['saldo_global_dolares']>0){
                        $totalD=$glo['saldo_global_dolares'] - $sal['saldo'];
                    }
                    
                    if($cambioM==false){

                        if($glo['moneda']=="COLONES"){
                             $sql = "UPDATE cuenta_cobrar set
                                    saldo_Global=?
                                    WHERE id_Cliente=?";  
                            
                            $this->pdo->prepare($sql)
                                      ->execute(
                                        array(
                                            $totalC,
                                            $comprobante['cliente_id']                                           
                                        ));

                                 
                        }else{
                             $sql = "UPDATE cuenta_cobrar set
                                    saldo_global_dolares=?
                                    WHERE id_Cliente=?";
                        
                            $this->pdo->prepare($sql)
                                      ->execute(
                                        array(
                                            $totalD,
                                            $comprobante['cliente_id']                                           
                                        ));
                               
                        }
                    }else{ //si no cambio moneda de contado
                        if($moneAnt=="COLONES"){
                             $sql = "UPDATE cuenta_cobrar set
                                    saldo_Global=?
                                    WHERE id_Cliente=?";  
                            
                            $this->pdo->prepare($sql)
                                      ->execute(
                                        array(
                                            $totalC,
                                            $comprobante['cliente_id']                                           
                                        ));
                            
                        }else{
                             $sql = "UPDATE cuenta_cobrar set
                                    saldo_global_dolares=?
                                    WHERE id_Cliente=?";
                        
                            $this->pdo->prepare($sql)
                                      ->execute(
                                        array(
                                            $totalD,
                                            $comprobante['cliente_id']                                           
                                        ));
                            
                        }


                    }
                    
                }
            }

            return true;
                 
        }
        catch (Exception $e) 
        {
            return false;
        }
    }

    public function RegistraPreFactura($comprobante)
    {
        try 
        {
            date_default_timezone_set('America/Costa_Rica');
            $p=$comprobante['plazo'];
            $fec=date('d-m-Y');
            $fecPlazo=date('d-m-Y',strtotime("+$p day"));
            $desc=0;

            /* Registramos el comprobante */
            $sql = "INSERT INTO prefactura(Cliente_id, IGV, SubTotal, Total,Tipo_Pago,moneda,fecha,fechaVence) VALUES (?, ?, ?, ?, ?, ?,?,?)";
            $this->pdo->prepare($sql)
                      ->execute(
                        array(
                            $comprobante['cliente_id'],
                            $comprobante['igv'],
                            $comprobante['subtotal'],
                            $comprobante['total'],
                            $comprobante['tipo_pago'],
                            $comprobante['moneda'],
                            $fec,
                            $fecPlazo                                                    
                        ));

            /* El ultimo ID que se ha generado */
            $comprobante_id = $this->pdo->lastInsertId();
            
            /* Recorremos el detalle para insertar */
            foreach($comprobante['items'] as $d)
            {
                $sql = "INSERT INTO detalle_prefactura (Comprobante_id,Producto_id,Presenta,Cantidad,PrecioUnitario,Descuento,Total) 
                        VALUES (?, ?, ?, ?, ?, ?, ?)";

                $desc+=$d['descT']/100*($d['precio']*$d['cantidad'])    ;
                
                $this->pdo->prepare($sql)
                          ->execute(
                            array(
                                $comprobante_id,
                                $d['producto_id'],
                                $d['presenta'],
                                $d['cantidad'],
                                $d['precio'],
                                $d['descT'],
                                $d['total']
                            ));
            }

            $sql = "UPDATE prefactura set   
                    descuento=?
                    WHERE id=?";
            $this->pdo->prepare($sql)
                      ->execute(
                        array(
                            $desc, 
                            $comprobante_id                                          
                        ));



            return true;
        }
        catch (Exception $e) 
        {
            return false;
        }
    }
}