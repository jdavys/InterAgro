<?php
$c=mysql_connect("127.0.0.1","root","");
mysql_select_db("facturador");

$orden1="SELECT * FROM person"; 
$paquete1=mysql_query($orden1);

?>
<h1 class="page-header">
    <a class="btn btn-primary pull-right btn-lg" href="?c=Comprobante&a=crud">Nuevo comprobante</a>
    Comprobantes
</h1>
<div class="row">
    
      
            <a class="btn btn-primary pull-right btn-lg" href="reportFact.php">Exportar Excel</a>
     

</div>


<!--<div class="row">
                    <div class="col-xs-4">
                        <div class="form-group">
                            
                              <label for="tags">CLIENTE: </label>
                              <input autocomplete="off" class="form-control" id="tagsC">
                              <input  type="hidden" id="cliente">
                        </div>                      

                    </div>
                    <div class="col-xs-2">-->
                        <!--type="button" href="Cliente/index.php"-->
                           <!-- <div class="form-group">
                                <label type="hidden"></label>
                                <button class="btn btn-primary form-control" id="btnCli" type="button">
                                <i>BUSCAR</i>
                                </button>
                            </div>
                    </div>
</div>-->

<div id="list"></div>

<!--<script>
    $(document).ready(function(){
        
        $('#btnCli').click(function(){
            //alert($('#tagsC').val());
            var agrid=$("#list").anexGrid({
            class: 'table-striped table-bordered',
            columnas: [
                { leyenda: 'Cliente', style: 'width:200px;', columna: 'Cliente_id', ordenable: true },
                { leyenda: 'Factura', style: 'width:30px;', columna: 'id', ordenable: true },
                { leyenda: 'Tipo Pago', style: 'width:80px;', columna: 'Tipo_Pago', ordenable: true },
                { leyenda: 'Moneda', style: 'width:60px;', columna: 'Moneda', ordenable: true },
                { leyenda: 'Fecha', style: 'width:80px;', columna: 'fecha', ordenable: true  },
                { leyenda: 'FecVence', style: 'width:80px;', columna: 'fechaVence', ordenable: true  },
                { leyenda: 'SubTotal', style: 'width:60px;', columna: 'subTotal', ordenable: true  },
                { leyenda: 'Desc', style: 'width:60px;', columna: 'descuento', ordenable: true  },
                { leyenda: 'Total', style: 'width:50px;', columna: 'Total', ordenable: true  },
                { leyenda: 'Estado', style: 'width:50px;', columna: 'estado', ordenable: true  },
                { style: 'width:60px;' },
                //{ style: 'width:60px;' },
            ],
            modelo: [
                { formato: function(tr, obj, valor){
                    return anexGrid_link({
                        href: '?c=comprobante&a=ver&id=' + obj.id,
                        contenido: obj.Cliente.Nombre
                    });
                }},
                { propiedad: 'id', class: 'text-right', },
                { propiedad: 'Tipo_Pago', class: 'text-right', },
                { propiedad: 'Moneda', class: 'text-right', },
                { propiedad: 'fecha', class: 'text-right', },
                { propiedad: 'fechaVence', class: 'text-right', },
                { propiedad: 'subTotal', class: 'text-right', },
                { propiedad: 'descuento', class: 'text-right', },
                { propiedad: 'Total', class: 'text-right', },
                { propiedad: 'estado', class: 'text-right', },
                { formato: function(tr, obj, celda){
                    return anexGrid_link({
                        class: 'btn-primary btn-xs btn-block',
                        contenido: 'Editar',
                        href: '?c=Comprobante&a=modificar&id=' + obj.id
                    });    
                }},
               /* { formato: function(tr, obj, celda){
                    return anexGrid_boton({
                        class: 'btn-danger btn-xs btn-block btn-eliminar',
                        contenido: 'Eliminar',
                        value: tr.data('fila')
                    });    
                }},*/
            ],
            url:'?c=comprobante&a=ListarCliente&idC='+ $('#tagsC').val(),
            limite: 10,
            columna: 'id',
            columna_orden: 'DESC',
            paginable: true
        });
        });
    });
</script>-->
<script>
    $(document).ready(function(){
        var agrid=$("#list").anexGrid({
            class: 'table-striped table-bordered',
            columnas: [
                { leyenda: 'Cliente', style: 'width:200px;', columna: 'Cliente_id', ordenable: true, filtro:true },
                { leyenda: 'Factura', style: 'width:30px;', columna: 'id', ordenable: true,filtro:true },
                { leyenda: 'Tipo Pago', style: 'width:80px;', columna: 'Tipo_Pago', ordenable: true ,filtro:true},
                { leyenda: 'Moneda', style: 'width:60px;', columna: 'Moneda', ordenable: true,filtro:true },
                { leyenda: 'Fecha', style: 'width:80px;', columna: 'fecha', ordenable: true  },
                { leyenda: 'Fecha Vence', style: 'width:80px;', columna: 'fechaVence', ordenable: true  },
                { leyenda: 'Sub Total', style: 'width:60px;', columna: 'SubTotal', ordenable: true  },
                { leyenda: 'Desc', style: 'width:60px;', columna: 'descuento', ordenable: true  },
                { leyenda: 'Total', style: 'width:50px;', columna: 'Total', ordenable: true  },
                { leyenda: 'Estado', style: 'width:50px;', columna: 'estado', ordenable: true ,filtro:true },
                { style: 'width:60px;' },
                { style: 'width:60px;' },
            ],
            modelo: [
                { formato: function(tr, obj, valor){
                    return anexGrid_link({
                        href: '?c=comprobante&a=ver&id=' + obj.id,
                        contenido: obj.Cliente.name
                    });
                }},
                { propiedad: 'id', class: 'text-right', },
                { propiedad: 'Tipo_Pago', class: 'text-right', },
                { propiedad: 'Moneda', class: 'text-right', },
                { propiedad: 'fecha', class: 'text-right', },
                { propiedad: 'fechaVence', class: 'text-right', },
                { propiedad: 'SubTotal', class: 'text-right', },
                { propiedad: 'descuento', class: 'text-right', },
                { propiedad: 'Total', class: 'text-right', },
                { propiedad: 'estado', class: 'text-right', },
                { formato: function(tr, obj, celda){
                    return anexGrid_link({
                        class: 'btn-primary btn-xs btn-block',
                        contenido: 'Editar',
                        href: '?c=Comprobante&a=modificar&id=' + obj.id
                    });    
                }},
                { formato: function(tr, obj, celda){
                    return anexGrid_link({
                        class: 'btn-danger btn-xs btn-block btn-eliminar',
                        contenido: 'Pedido',
                        href: '?c=Comprobante&a=ModiDetaPre&idC=' + obj.id
                    });    
                }},
            ],
            url:'?c=Comprobante&a=Listar',
            limite: [10,20,50],
            columna: 'id',
            columna_orden: 'DESC',
            paginable: true,
            filtrable: true
        });

        

        /*agrid.tabla().on('click', '.btn-eliminar', function(){
            if(!confirm('¿Esta seguro de eliminar este registro?')) return;
            
            Obtiene el objeto actual de la fila seleccionada 
            var fila = agrid.obtener($(this).val());
            
            Petición ajax al servidor 
            $.post('?c=Comprobante&a=EliminarF', {
                id: fila.id
            }, function(r){
                if(r) agrid.refrescar();
            }, 'json')
            
            return false;
        })*/
        
    });
    
    
</script>
<script src="assets/scripts/comprobante.js"></script>