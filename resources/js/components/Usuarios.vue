<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">Gestión de Usuarios</div>

                    <div class="card-body">
                        <div class="row mb-1">
                            <div class="col-12 ">
                                <button class="btn btn-success float-right" type="button" data-toggle="modal" data-target="#agregar" @click="limpiar()">
                                    Agregar
                                </button>
                            </div>
                        </div>
                        <div class="row">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Nombre</th>
                                        <th scope="col">Correo</th>
                                        <th scope="col">Estado</th>
                                        <th scope="col">Opciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="usuario in listado" :key="usuario.id">
                                        <td></td>
                                        <td v-text="usuario.name"></td>
                                        <td v-text="usuario.email"></td>
                                        <td v-text="usuario.estado == 1 ? 'Activo' : 'Inacitvo'">
                                        <td>
                                            <!-- <span style="font-size: 1.5em; color:#FFC107;"><i class="fas fa-user"></i></span>-->
                                            <button class="btn btn-light" @click="cargaEditar(usuario)" data-toggle="modal" data-target="#agregar">
                                                <span style="font-size: 1em; color:#28a745 ;"  >
                                                    <i class="fas fa-edit"></i>
                                                </span>
                                            </button>
                                            <button class="btn btn-light">
                                                <span style="font-size: 1em; color:#DC3545;">
                                                    <i class="fas fa-trash"></i>
                                                </span>
                                            </button>
                                        </td>
                                    </tr>
                                    <tr></tr>
                                    <tr></tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Modal -->
        <div class="modal fade" id="agregar" tabindex="-1" role="dialog" data-backdrop="static" aria-labelledby="Agregar/Editar" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle">Datos Usuario</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form method="POST" action="/vuejs/form" @submit.prevent="guardar" >
                        
                        <div class="modal-body">
                            
                            <div :class="['form-group row', form.errors().has('name') ? 'has-error' : '']" >
                                <label for="name" class="col-sm-12 col-md-4 col-form-label">Nombre</label> 
                                <div class="col-sm-12 col-md-8">
                                    <input id="name" name="name" value=""  autofocus="autofocus" class="form-control" type="text" v-model="form.name" required>
                                    <span v-if="form.errors().has('name')" class="label label-danger" v-text="form.errors().get('name')"></span>
                                </div>
                            </div> 
                            <div :class="['form-group row', form.errors().has('email') ? 'has-error' : '']" >
                                <label for="email" class="col-sm-12 col-md-4 col-form-label">Correo</label> 
                                <div class="col-sm-12 col-md-8">
                                    <input id="email" name="email"  class="form-control" type="email" v-model="form.email" required>
                                    <span v-if="form.errors().has('email')" class="label label-danger" v-text="form.errors().get('email')"></span>
                                </div>
                                
                            </div>
                            <div :class="['form-group row', form.errors().has('password') ? 'has-error' : '']" >
                                <label for="password" class="col-sm-12 col-md-4 col-form-label">Password</label> 
                                <div class="col-sm-12 col-md-8">
                                    <input id="password" name="password"  class="form-control" type="password" v-model="form.password" >
                                    <span v-if="form.errors().has('password')" class="label label-danger" v-text="form.errors().get('password')"></span>
                                </div>                                   
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-primary" v-text="editando==0 ? 'Guardar' : 'Actualizar'"></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>    
</template>

<script>
import form from 'vuejs-form'
    export default {
        data() {    
            return {
                editando: 0,
                id_edita: '',
                form: form.default({
                    name : '',
                    email : '',
                    password: '',
                    estado: 1,
                })
                .rules({
                    email: 'email|min:7|required',
                    name: 'required|min:5|',
                    
                })
                .messages({
                    'name.name': 'El nombre es requerido',
                    'email.email': 'Ingrese un correo válido',
                    
                }),
                errores: [],
                success : false,
                listado: []
            }
        },
       
        methods : {
                      
            guardar() {
                let me = this;
                if( !this.form.errors().any() ) {
                    if (this.editando == 0) {
                        if(this.password == '') {
                            alert("Debe digitar el password!!");
                        } else {
                            axios.post("api/usuarios",this.form.all())
                            .then(function (response) {
                                
                                me.form.email='';
                                me.form.name='';
                                me.form.password='';
                                $('#agregar').modal('hide');
                                me.listar();
                            })
                            .catch(function (error) {
                                console.log(error);
                            });
                        }                        
                    } else {
                        axios.put("api/usuarios/"+this.id_edita,this.form.all())
                        .then(function (response) {
                            
                            me.form.email='';
                            me.form.name='';
                            me.form.password='';
                            $('#agregar').modal('hide');
                            me.listar();
                        })
                        .catch(function (error) {
                            console.log(error);
                        });                        
                    }
                }
                else 
                {
                    console.log('errors: ', this.form.errors().all());
                }
                
                
            },
            limpiar() {
                this.editando = 0;
                this.id_edita = '';
                this.form.name = '';
                this.form.email = '';
                this.form.password = '';
            },
            listar(){
                let me = this;
                 axios.get("api/usuarios")
                    .then(function (response) {
                        //console.log("llega");
                        //console.log(response.data);
                        me.listado = response.data;
                        console.log(response.data);
                    });
                
            },
            cargaEditar(objeto){
                $('#agregar').modal('show');
                this.editando = 1;
                this.id_edita = objeto.id;
                this.form.name = objeto.name;
                this.form.email = objeto.email;
                this.form.password= '';

            }
        },
        mounted() {
            this.listar();
            //console.log('Component mounted.')
        }
    }
</script>
