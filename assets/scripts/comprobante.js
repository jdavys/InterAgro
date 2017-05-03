var descuento=0;
var facturador = {
    detalle: {
        descF:      0,
        igv:        0,
        total:      0,
        subtotal:   0,
        cliente_id: 0,
        tipo_pago : 0,
        moneda     :0,
        fecha      :0,
        plazo      :0,
        items:    []
    },

    /* Encargado de agregar un producto a nuestra colección */
    registrar: function(item)
    {

        //var existe = false;
        var desc=(item.cantidad * item.precio)*(item.descT/100);
        descuento+=desc;
        item.total = (item.cantidad * item.precio)-((item.cantidad * item.precio)*(item.descT/100));
        
        /*this.detalle.items.forEach(function(x){
            if(x.producto_id === item.producto_id) {
                x.cantidad = item.cantidad;
                x.total += item.total;
                //existe = true;
            }
        });*/
        this.detalle.items.push(item);
        /*if(!existe) {
            this.detalle.items.push(item);
        }*/
        
        this.refrescar();       
        
    },

    /* Encargado de actualizar el precio/cantidad de un producto */
    actualizar: function(id, row)
    {
         //console.log(id);
        descuento=0;
        /* Capturamos la fila actual para buscar los controles por sus nombres */
        row = $(row).closest('.list-group-item');
        //console.log(row.find("input[name='presenta']").val());
        /* Buscamos la columna que queremos actualizar */
        $(this.detalle.items).each(function(indice, fila){
            if(indice == id)
            {
                /* Agregamos un nuevo objeto para reemplazar al anterior */
                
                facturador.detalle.items[indice] = {
                    producto_id: parseInt(row.find("input[name='producto_id']").val()),
                    producto: row.find("input[name='producto']").val(),
                    presenta: row.find("input[name='presenta']").val(),
                    cantidad: parseFloat(row.find("input[name='cantidad']").val()),
                    precio:   parseFloat(row.find("input[name='precio']").val()),
                    descT: parseInt(row.find("input[name='descT']").val()),
                };

               
                descuento=((facturador.detalle.items[indice].precio * facturador.detalle.items[indice].cantidad)*(facturador.detalle.items[indice].descT/100.00));
                facturador.detalle.items[indice].total = parseFloat((facturador.detalle.items[indice].precio * facturador.detalle.items[indice].cantidad) - descuento);
               
                                                          
                return false;
            }
        })

        this.refrescar();
    },

    /* Encargado de retirar el producto seleccionado */
    retirar: function(id)
    {
        /* Declaramos un ID para cada fila */
        $(this.detalle.items).each(function(indice, fila){
            if(indice == id)
            {
                facturador.detalle.items.splice(id, 1);
                return false;
            }
        })

        this.refrescar();
    },

    /* Refresca todo los productos elegidos */
    refrescar: function()
    {
        this.detalle.total = 0;
        this.detalle.igv=0;
        var sub=0

        /* Declaramos un id y calculamos el total */
        $(this.detalle.items).each(function(indice, fila){
            facturador.detalle.items[indice].id = indice;
            facturador.detalle.total += fila.total;
            sub+=fila.precio*fila.cantidad;

        })

        if($('#impuestoV').val()>0){
            this.detalle.igv=$('#impuestoV').val()/100;
        }else{

        }
        
        /* Calculamos el subtotal e IGV */
        this.detalle.igv      = (this.detalle.total * this.detalle.igv).toFixed(2); // 18 % El IGV y damos formato a 2 deciamles
        this.detalle.descF      = descuento.toFixed(2); // DESCUENTO y damos formato a 2 deciamles
        this.detalle.subtotal = (sub ).toFixed(2); // Total - IGV y formato a 2 decimales
        this.detalle.total    = (this.detalle.total).toFixed(2);
               

        var template   = $.templates("#facturador-detalle-template");
        var htmlOutput = template.render(this.detalle);

        $("#facturador-detalle").html(htmlOutput);
    }
};

$(document).ready(function(){
    $("#btn-agregar").click(function(){

        var producto_id = $("#producto_id"),
            producto = $("#producto"),
            cantidad = $("#cantidad"),
            precio =   $("#precio"),
            descT= $("#descT"),
            presenta = $("#presenta");
            
        
        // Validaciones
        if(producto_id.val() === '0') {
            alert('Debe seleccionar un producto');
            return;
        }
        
        if(!isNumber(cantidad.val())) {
            alert('Debe ingresar una cantidad válida');
            return;
        } else if( parseInt(cantidad.val()) <= 0 ) {
            alert('Debe ingresar una cantidad válida');
            return;
        }

        if(!isNumber(descT.val())) {
            alert('Debe ingresar una cantidad válida');
            return;
        } else if( parseInt(cantidad.val()) <= 0 ) {
            alert('Debe ingresar una cantidad válida');
            return;
        }
        
        facturador.registrar({
            producto_id: producto_id.val(),
            producto: producto.val(),
            cantidad: cantidad.val(),
            precio: parseFloat(precio.val()),
            descT: parseInt(descT.val()),
            presenta: presenta.val(),
        });

        producto_id.val('0');
        producto.val('');
        cantidad.val('0');
        precio.val('0');
        descT.val('0');
        presenta.val('Presentacion');
    
    })
    
    $("#frm-comprobante").submit(function(){

        
        facturador.detalle.tipo_pago=  $("#tipo_pago").val();
        facturador.detalle.moneda=  $("#moneda").val();
        facturador.detalle.plazo=  $("#plazo").val();
        

        //facturador.detalle.igv=parseFloat($("#impuestoV").val());
        
        var form = $(this);
        
        if(facturador.detalle.cliente_id == 0)
        {
            alert('El Cliente No existe, Favor crearlo con el boton NUEVO');
           
        }
        else if(facturador.detalle.items.length == 0)
        {
            alert('Debe agregar por lo menos un detalle al comprobante');
        }else
        {
            $.ajax({
                dataType: 'JSON',
                type: 'POST',
                url: form.attr('action'),
                data: facturador.detalle,
                success: function (r) {
                    if(r) window.location.href = '?c=Comprobante'  
                    //console.log(           
                },
                error: function(jqXHR, textStatus, errorThrown){
                    console.log(errorThrown + ' ' + textStatus);
                    window.location.href = '?c=Comprobante';
                }   
            });            
        }
    
        return false;
    })

    /*$("#frm-comprobante-modi").submit(function(){

            var form = $(this);
            $.ajax({
                dataType: 'JSON',
                type: 'POST',
                url: form.attr('action'),
                data: facturador.detalle,
                success: function (r) {
                    if(r) window.location.href = '?c=Comprobante';
                    
                },
                error: function(jqXHR, textStatus, errorThrown){
                    console.log(errorThrown + ' ' + textStatus);
                    window.location.href = '?c=Comprobante';
                }   
            });            
            
        return false;
    })*/
    
    /* Autocomplete de cliente, jquery UI */
    $("#cliente").autocomplete({
        dataType: 'JSON',
        source: function (request, response) {
            jQuery.ajax({
                url: '?c=Comprobante&a=ClienteBuscar',
                type: "post",
                dataType: "json",
                data: {
                    criterio: request.term
                },
                success: function (data) {
                    
                    response($.map(data, function (item) {
                        return {
                            id: item.id,
                            value: item.Nombre,
                            direccion: item.Direccion,
                            ruc: item.RUC,
                        }
                    }))
                }
            })
        },
        select: function (e, ui) {
            $("#cliente_id").val(ui.item.id);
            $("#direccion").val(ui.item.direccion);
            $("#ruc").val(ui.item.ruc);
            $(this).blur();
            
            facturador.detalle.cliente_id = ui.item.id;
        }
        
    })
    
    /* Autocomplete de producto, jquery UI */
    $("#producto").autocomplete({
        dataType: 'JSON',
        source: function (request, response) {
            jQuery.ajax({
                url: '?c=Comprobante&a=ProductoBuscar',
                type: "post",
                dataType: "json",
                data: {
                    criterio: request.term
                },
                success: function (data) {
                    response($.map(data, function (item) {
                        return {
                            id: item.id,
                            value: item.Nombre,
                            precio: item.Precio,
                            descripcion: item.descripcion
                            
                                                    }
                    }))
                }
            })
        },
        select: function (e, ui) {
            $("#producto_id").val(ui.item.id);
            $("#precio").val(ui.item.precio);
            $("#presenta").val(ui.item.descripcion);
            $("#cantidad").focus();
         
        }
    })

     $("#tagsC").autocomplete({
        dataType: 'JSON',
        source: function (request, response) {
            jQuery.ajax({
                url: '?c=Comprobante&a=ClienteBuscar',
                type: "post",
                dataType: "json",
                data: {
                    criterio: request.term
                },
                success: function (data) {
                    
                     response($.map(data, function (item) {
                        return {
                            id: item.id,
                            value: item.Nombre,
                            
                        }
                    }))
                }
                
            })              
        },
        select: function (e, ui) {
            $("#cliente").val(ui.item.id);             
        }

    })
})

function isNumber(n) {
  return !isNaN(parseFloat(n)) && isFinite(n);
}