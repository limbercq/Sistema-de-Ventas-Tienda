<template>
   <main class="main">
            <!-- Breadcrumb -->
            <ol class="breadcrumb">
                 <li class="breadcrumb-item"><a href="/">Escritorio</a></li>
            </ol>
            <div class="container-fluid">
                <!-- Ejemplo de tabla Listado -->
                <div class="card">
                    <div class="card-header">
                        <i class="fa fa-align-justify"></i> Ingresos
                        <button type="button" @click="mostrarDetalle()" class="btn btn-secondary">
                            <i class="icon-plus"></i>&nbsp;Nuevo
                        </button>
                    </div>
                    <!--Listado -->
                    <template v-if="listado">
                        <div class="card-body">
                            <div class="form-group row">
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <select class="form-control col-md-3" v-model="criterio">
                                        <option value="tipo_comprobante">Tipo Comprobante</option>
                                        <option value="num_comprobante">Nro Comprobante</option>
                                        <option value="fecha_hora">Fecha-Hora</option>                                      
                                        </select>
                                        <input type="text" v-model="buscar" @keyup.enter="listarIngreso(1,buscar,criterio)" class="form-control" placeholder="Texto a buscar">
                                        <button type="submit" @click="listarIngreso(1,buscar,criterio)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                    </div>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-sm">
                                    <thead>
                                        <tr>
                                            <th>Opciones</th>
                                            <th>Usuario</th>
                                            <th>Proveedor</th>
                                            <th>Tipo Comprobante</th>
                                            <th>Serie Comprobante</th>
                                            <th>Numero Comprobante</th>
                                            <th>Fecha Hora</th>
                                            <th>Total</th>
                                            <th>Impuesto</th>
                                            <th>Estado</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="ingreso in arrayIngreso" :key="ingreso.id"> 
                                            <td>
                                                <button type="button" @click="abrirModal('ingreso','actualizar',ingreso)" class="btn btn-success btn-sm">
                                                    <i class="icon-eye"></i>
                                                </button> &nbsp; 
                                                <template v-if="ingreso.estado == 'Registrado'">
                                                    <button type="button" class="btn btn-danger btn-sm" @click="desactivarIngreso(ingreso.id)">
                                                        <i class="icon-trash"></i>
                                                    </button>
                                                </template>                                                                               
                                            </td>
                                            <td v-text="ingreso.usuario"></td>
                                            <td v-text="ingreso.nombre"></td>
                                            <td v-text="ingreso.tipo_comprobante"></td>
                                            <td v-text="ingreso.serie_comprobante"></td>
                                            <td v-text="ingreso.num_comprobante"></td>
                                            <td v-text="ingreso.fecha_hora"></td>
                                            <td v-text="ingreso.total"></td> 
                                            <td v-text="ingreso.impuesto"></td>
                                            <td v-text="ingreso.estado"></td>
                                        </tr>                                
                                    </tbody>
                                </table>
                            </div>
                        
                            <nav>
                                <ul class="pagination">
                                    <li class="page-item" v-if="pagination.current_page > 1">
                                        <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page - 1,buscar,criterio)">Ant</a>
                                    </li>
                                    <li class="page-item" v-for="page in pagesNumber" :key="page" :class="[page == isActived ? 'actve' : '']">
                                        <a class="page-link" href="#" @click.prevent="cambiarPagina(page,buscar,criterio)" v-text="page"></a>
                                    </li>                               
                                    <li class="page-item" v-if="pagination.current_page < pagination.last_page">
                                        <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page + 1,buscar,criterio)">Sig</a>
                                    </li>
                                </ul>
                            </nav>
                        </div>
                    </template>
                    <!-- Fin Listado -->
                    <!--Detalle -->
                    <template v-else>
                        <div class="card-body">
                            <div class="form-group row border">
                                <div class="col-md-9">
                                    <div class="form-group">
                                        <label for="">Proveedor (*)</label>
                                        <v-select
                                            :on-search="selectProveedor"
                                            label="nombre"
                                            :options="arrayProveedor"
                                            placeholder="Buscar Proveedores..."
                                            :onChange="getDatosProveedor"
                                        >

                                        </v-select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <label for="">Impuesto (*)</label>
                                    <input type="text" class="form-control" v-model="impuesto">
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label >Tipo Comprobante</label>
                                        <select class="form-control" v-model="tipo_comprobante">
                                            <option value="0">Seleccione</option>
                                            <option value="BOLETA">Boleta</option>
                                            <option value="FACTURA">Factura</option>
                                            <option value="TICKET">Ticket</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label >Serie Comprobante</label>
                                        <input type="text" class="form-control" v-model="serie_comprobante" placeholder="000x">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-control">
                                        <label >Numero Comprobante (*)</label>
                                        <input type="text" class="form-control" v-model="num_comprobante" placeholder="000xx">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row border">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Artículo <span style="color:red;" v-show="idarticulo==0">(*Seleccione un articulo)</span></label>
                                        <div class="form-inline">
                                            <input type="text" class="form-control" v-model="codigo" @keyup.enter="busarArticulo" placeholder="Ingrese artículo">
                                            <button @click="abrirModal()" class="btn btn-primary">...</button>
                                            <input type="text" readonly class="form-control" v-model="articulo">
                                        </div>                                    
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>Precio <span style="color:red;" v-show="precio==0">(*ingrese Precio)</span></label>
                                        <input type="number" value="0" step="any" class="form-control" v-model="precio">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>Cantidad <span style="color:red;" v-show="cantidad==0">(*ingrese)</span></label>
                                        <input type="number" value="0" class="form-control" v-model="cantidad">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <button @click="agregarDetalle()" class="btn btn-success form-control btnagregar">
                                            <i class="icon-plus"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row border">
                                <div class="table-responsive col-md-12">
                                    <table class="table table-bordered table-striped table-sm">
                                        <thead>
                                            <tr>
                                                <th>Opciones</th>
                                                <th>Artículo</th>
                                                <th>Precio</th>
                                                <th>Cantidad</th>
                                                <th>Subtotal</th>
                                            </tr>
                                        </thead>
                                        <tbody v-if="arrayDetalle.length">
                                            <tr v-for="(detalle,index) in arrayDetalle" :key="detalle.id">
                                                <td>
                                                    <button @click="elimnarDetalle(index)" type="button" class="btn btn-danger btn-sm">
                                                        <i class="icon-close"></i>
                                                    </button>
                                                </td>
                                                <td v-text="detalle.articulo"></td>
                                                <td>
                                                    <input v-model="detalle.precio" type="number" value="3" class="form-control">
                                                </td>
                                                <td>
                                                    <input v-model="detalle.cantidad" type="number" value="2" class="form-control">
                                                </td>
                                                <td>
                                                    {{detalle.precio*detalle.cantidad}}                                                    
                                                </td>
                                            </tr>                                            
                                            <tr style="background-color: #CEECF5;">
                                                <td colspan="4" align="right"><strong>Total Parcial:</strong></td>
                                                <td>Bs. {{totalParcial= (total-totalImpuesto).toFixed(2)}} </td>
                                            </tr>
                                            <tr style="background-color: #CEECF5;">
                                                <td colspan="4" align="right"><strong>Total Impuesto:</strong></td>
                                                <td>Bs. {{totalImpuesto=((total*impuesto)/(1+impuesto)).toFixed(2)}} </td>
                                            </tr>
                                            <tr style="background-color: #CEECF5;">
                                                <td colspan="4" align="right"><strong>Total Neto:</strong></td>
                                                <td>Bs. {{total=calcularTotal}} </td>
                                            </tr>
                                        </tbody> 
                                        <tbody v-else>
                                            <tr>
                                                <td colspan="5">
                                                    No hay articulos agregados
                                                </td>
                                            </tr>
                                        </tbody>                                   
                                    </table>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-12">
                                    <button type="button" @click="OcultarDetalle()" class="btn btn-secondary">Cerrar</button>
                                    <button type="button" class="btn btn-primary" @click="registrarIngreso()">Registrar Compra</button>
                                </div>
                            </div>
                        </div>
                    </template>
                    <!-- fin Detalle -->
                </div>
                <!-- Fin ejemplo de tabla Listado -->
            </div>
            <!--Inicio del modal agregar/actualizar-->
            <div class="modal fade" tabindex="-1" :class="{'mostrar' : modal}"  role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
                <div class="modal-dialog modal-primary modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" v-text="tituloModal"></h4>
                            <button type="button" class="close" @click="cerrarModal()" aria-label="Close">
                              <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group row">
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <select class="form-control col-md-3" v-model="criterioA">
                                        <option value="nombre">Nombre</option>
                                        <option value="descripcion">Descripción</option>
                                        <option value="codigo">Codigo</option>
                                        </select>
                                        <input type="text" v-model="buscarA" @keyup.enter="listarArticulo(buscarA,criterioA)" class="form-control" placeholder="Texto a buscar">
                                        <button type="submit" @click="listarArticulo(buscarA,criterioA)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                    </div>
                                </div>
                            </div>
                           <div class="table-responsive">
                               <table class="table table-bordered table-striped table-sm">
                                    <thead>
                                        <tr>
                                            <th>Opciones</th>
                                            <th>Codigo</th>
                                            <th>Nombre</th>
                                            <th>Categoria</th>
                                            <th>Precio Ventas</th>
                                            <th>Stock</th>
                                            <th>Estado</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="articulo in arrayArticulo" :key="articulo.id"> 
                                            <td>
                                                <button type="button" @click="agregarDetalleModal(articulo)" class="btn btn-success btn-sm">
                                                <i class="icon-check"></i>
                                                </button>
                                            </td>
                                            <td v-text="articulo.codigo"></td>
                                            <td v-text="articulo.nombre"></td>
                                            <td v-text="articulo.nombre_categoria"></td>
                                            <td v-text="articulo.precio_venta"></td>
                                            <td v-text="articulo.stock"></td>
                                            <td>
                                                <div v-if="articulo.condicion">
                                                    <span class="badge badge-success">Activo</span>
                                                </div>
                                                <div v-else>
                                                    <span class="badge badge-danger">Desactivado</span>
                                                </div>
                                            </td>
                                        </tr>                                
                                    </tbody>
                               </table>
                           </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                            <button type="button" v-if="tipoAccion==1" class="btn btn-primary" @click="registrarPersona()" >Guardar</button>
                            <button type="button" v-if="tipoAccion==2" class="btn btn-primary" @click="actualizarPersona()">Actualizar</button>
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
            <!--Fin del modal-->            
    </main>
</template>

<script>
    import vSelect from 'vue-select';
    export default {
        data (){
            return{
                ingreso_id : 0,
                idproveedor : '',
                tipo_comprobante : 'FACTURA',
                num_comprobante : '',
                serie_comprobante : '',
                impuesto : '0.18',
                total : '0.0', 
                totalImpuesto: 0.0,
                totalParcial: 0.0,               
                arrayIngreso :[],
                arrayProveedor:[],
                arrayDetalle:[],
                listado : 1,
                modal : 0,
                tituloModal : '',
                tipoAccion : 0,
                errorIngreso : 0,
                errorMostrarMsjIngreso : [],
                pagination : {
                    'total': 0,
                    'current_page' : 0,
                    'per_page' : 0,
                    'last_page' : 0,
                    'from' : 0,
                    'to' : 0,
                 },
                offest : 3,
                criterio : 'num_comprobante',
                buscar : '',
                arrayArticulo:[],
                idarticulo: 0,
                codigo: '',
                articulo:'',
                precio: 0,
                cantidad: 0,
                buscarA: '',
                criterioA: 'nombre'
            }
        },
        components:{
            vSelect
        },
        computed:{
            isActived : function(){
                return this.pagination.current_page;
            },
            // calcula los elementos de la paginacion
            pagesNumber: function(){
                if(!this.pagination.to){
                    return [];
                }

                var from = this.pagination.current_page - this.offest;
                if(from < 1){
                    from = 1;
                }

                var to = from + (this.offest * 2);
                if(to  >= this.pagination.last_page){
                    to = this.pagination.last_page;
                }

                var pagesArray = [];
                while (from <= to) {
                    pagesArray.push(from);
                    from++;
                }
                return pagesArray;
            },          
            calcularTotal: function(){
                var resustado = 0.0;
                for (var i = 0; i < this.arrayDetalle.length; i++) {
                    resustado = resustado + (this.arrayDetalle[i].precio * this.arrayDetalle[i].cantidad);
                }
                return resustado;
            }
        },
        methods : {
            listarIngreso (page,buscar,criterio){
                let me=this;
                var url= '/ingreso?page=' + page + '&buscar='+buscar + '&criterio='+criterio;
                axios.get(url).then(function (response) {                    
                    var respuesta= response.data;                    
                    me.arrayIngreso = respuesta.ingresos.data;                    
                    me.pagination= respuesta.pagination;
                })
                .catch(function (error) {
                    console.log(error);
                });
            }, 
            selectProveedor(search,loading){
                let me=this;
                loading(true)

                var url= '/proveedor/selectProveedor?filtro='+search;
                axios.get(url).then(function (response) {                    
                    var respuesta= response.data;                    
                    q:search
                    me.arrayProveedor = respuesta.proveedores;                                     
                    loading(false)
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            getDatosProveedor(val1){
                let me = this;
                me.loading = true;
                me.idproveedor = val1.id;
            },
            busarArticulo(){
                let me = this;
                var url = '/articulo/buscarArticulo?filtro='+me.codigo;

                axios.get(url).then(function (response){
                    var respuesta = response.data;
                    me.arrayArticulo = respuesta.articulos;

                    if(me.arrayArticulo.length > 0){
                        me.articulo = me.arrayArticulo[0]['nombre'];
                        me.idarticulo = me.arrayArticulo[0]['id'];
                    }else{
                        me.articulo = 'No existe articulo';
                        me.idarticulo = 0;
                    }
                })
                .catch(function (error) {
                    console.log(error);
                    
                });
            },
            cambiarPagina(page,buscar,criterio){
                let me = this;
                //Actualiza la página actual
                me.pagination.current_page = page;
                //Envia la petición para visualizar la data de esa página
                me.listarIngreso(page,buscar,criterio);
            },
            encuentra(id){
                var sw=0;
                for (var i = 0; i < this.arrayDetalle.length; i++) {
                    if(this.arrayDetalle[i].idarticulo == id){
                        sw= true;
                    }                    
                }
                return sw;
            },
            elimnarDetalle(index){
                let me = this;
                me.arrayDetalle.splice(index,1);
            },
            agregarDetalle(){
                let me = this;
                if (me.idarticulo == 0 || me.cantidad==0 || me.precio==0) {
                    
                }else{
                    if (me.encuentra(me.idarticulo)) {
                        swal({
                            type: 'error',
                            title: 'Error...',
                            text: 'Este articulo ya se encuentra agreagado!',                   
                            })
                    } else {
                         me.arrayDetalle.push({
                            idarticulo: me.idarticulo,
                            articulo: me.articulo,
                            cantidad: me.cantidad,
                            precio: me.precio
                        });
                        me.codigo="";
                        me.idarticulo=0;
                        me.precio=0;
                        me.articulo="";
                        me.cantidad=0;
                    }                   
                }
                
            },
            agregarDetalleModal(data = []){                
                let me = this;
                if (me.encuentra(data['id'])) {
                        swal({
                            type: 'error',
                            title: 'Error...',
                            text: 'Este articulo ya se encuentra agreagado!',                   
                            })
                    } else {
                         me.arrayDetalle.push({
                            idarticulo: data['id'],
                            articulo: data['nombre'],
                            cantidad: 1,
                            precio: 1
                        });                        
                    }       
            },
            listarArticulo (buscar,criterio){
                let me=this;
                var url= '/articulo/listarArticulo?buscar='+buscar + '&criterio='+criterio;
                axios.get(url).then(function (response) {
                    var respuesta= response.data;                    
                    me.arrayArticulo = respuesta.articulos.data;    
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            registrarPersona(){
                if(this.validarPersona()){
                    return;
                }
                
                let me = this;

                axios.post('/user/registrar',{
                    'nombre': this.nombre,
                    'tipo_documento': this.tipo_documento,
                    'num_documento' : this.num_documento,
                    'direccion' : this.direccion,
                    'telefono' : this.telefono,
                    'email' : this.email,
                    'usuario': this.usuario,
                    'password': this.password,
                    'idrol':this.idrol

                }).then(function (response) {
                    me.cerrarModal();
                    me.listarPersona(1,'','nombre');
                }).catch(function (error) {
                    console.log(error);
                });
            },            
            validarPersona(){
                this.errorPersona=0;
                this.errorMostrarMsjPersona=[];

                if(!this.nombre) this.errorMostrarMsjPersona.push("El nombre de la persona no puede estar vacio");
                if(!this.usuario) this.errorMostrarMsjPersona.push("El nombre del usuario no puede estar vacio");
                if(!this.password) this.errorMostrarMsjPersona.push("El password no puede estar vacio");
                if(this.idrol==0) this.errorMostrarMsjPersona.push("Debes seleccionar un rol para el usuario");

                if(this.errorMostrarMsjPersona.length) this.errorPersona = 1;
                
                return this.errorPersona;
            },
            mostrarDetalle(){
                this.listado=0;
            },
            OcultarDetalle(){
                this.listado=1;
            },
            cerrarModal(){
                this.modal=0;
                this.tituloModal='';                
            },
            abrirModal(){ 
                this.arrayArticulo = [];              
                this.modal = 1;
                this.tituloModal = 'Seleccione uno o varios articulos';             
            },
            desactivarUsuario(id){
                const swalWithBootstrapButtons = swal.mixin({
                confirmButtonClass: 'btn btn-success',
                cancelButtonClass: 'btn btn-danger',
                buttonsStyling: false,
                })

                swalWithBootstrapButtons({
                title: 'Esta seguro de desactivar este Usuario?',
                type: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Aceptar',
                cancelButtonText: 'Cancelar',
                reverseButtons: true
                }).then((result) => {
                if (result.value) {
                    let me=this;

                    axios.put('/user/desactivar',{                        
                        'id':id
                    }).then(function (response) {                        
                        me.listarPersona(1,'','nombre');
                        swalWithBootstrapButtons(
                        'Desactivado!',
                        'El Usuario ha sido desactivado con exito.',
                        'success'
                        )
                    })
                    .catch(function (error) {
                        console.log(error);
                    });
                    
                } else if (
                    // Read more about handling dismissals
                    result.dismiss === swal.DismissReason.cancel
                ) {/*
                    swalWithBootstrapButtons(
                    'Cancelled',
                    'Your imaginary file is safe :)',
                    'error'
                    )*/
                }
                })
            },
            activarUsuario(id){
                const swalWithBootstrapButtons = swal.mixin({
                confirmButtonClass: 'btn btn-success',
                cancelButtonClass: 'btn btn-danger',
                buttonsStyling: false,
                })

                swalWithBootstrapButtons({
                title: 'Esta seguro de activar este Usuario?',
                type: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Aceptar',
                cancelButtonText: 'Cancelar',
                reverseButtons: true
                }).then((result) => {
                if (result.value) {
                    let me=this;

                    axios.put('/user/activar',{                        
                        'id':id
                    }).then(function (response) {                        
                        me.listarPersona(1,'','nombre');
                        swalWithBootstrapButtons(
                        'Activado!',
                        'El Usuario ha sido activado con exito.',
                        'success'
                        )
                    })
                    .catch(function (error) {
                        console.log(error);
                    });
                    
                } else if (
                    // Read more about handling dismissals
                    result.dismiss === swal.DismissReason.cancel
                ) {/*
                    swalWithBootstrapButtons(
                    'Cancelled',
                    'Your imaginary file is safe :)',
                    'error'
                    )*/
                }
                })
            }
        },
        mounted() {
           this.listarIngreso(1,this.buscar,this.criterio);
        }
    }
</script>
<style>
    .modal-content{
        width: 100% !important;
        position: absolute !important;
    }
    .mostrar{
        display: list-item !important;
        opacity: 1 !important;
        position: absolute !important;
        background-color: #3c29297a !important;
    }
    .div-error{
        display: flex;
        justify-content: center;
    }
    .text-error{
        color: red !important;
        font-weight: bold;
    }
    @media (min-width: 600px) {
        .btnagregar {
            margin-top: 2rem;
        }
    }
</style>
