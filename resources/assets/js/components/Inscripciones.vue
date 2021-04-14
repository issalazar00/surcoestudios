<template>
    <div class="container">
        <div class="row mt-5" v-if="$gate.isAdminOrAuthor()">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Incripciones</h3>
<!--
                        <div class="card-tools">
                             <button class="btn btn-success" @click="newModal">Empresa <i class="fas fa-user-plus fa-fw"></i></button>
                        </div>
                        -->
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        Tipo 
                    </div>
                    <div class="card-body table-responsive p-0">
                        <table class="table table-hover">
                            <tbody>
                                <tr>
                                    <th>ID</th>
                                    <th>Alumno</th>                                   
                                    <th>Curso</th>
                                    <th>Fecha Solicita</th>
                                    <th>Estado</th>
                                    <th>Fecha Acepta</th>                                    
                                    <th>Fecha Cancelado</th>
                                    <th>Fecha Finalizado</th>
                                    <th></th>
                                </tr>
                                <tr v-for="inscripcion in inscripciones.data" :key="inscripcion.id">
                                    <td>{{inscripcion.id}}</td>
                                    
                                    <td>{{inscripcion.name}}</td>
                                    <td>{{inscripcion.curso}}</td>
                                    <td>{{inscripcion.fecha}} {{inscripcion.estado }}</td>
                                    <td v-if="inscripcion.estado==1">En solicitud</td>
                                    <td v-if="inscripcion.estado==2">En progreso</td>
                                    <td v-if="inscripcion.estado==3">Finalizado</td>
                                    <td v-if="inscripcion.estado==0">Cancelado</td>
                                    <td v-if="inscripcion.fec_activa || inscripcion.fec_cancela || inscripcion.fec_termina">{{inscripcion.fec_activa}}</td>
                                    <td v-if="inscripcion.estado == 1">
                                        <button type="button" class="btn btn-success" @click="aceptarInscripcion(inscripcion.id)">Aceptar</button>
                                    </td>
                        
                                    <td v-if="inscripcion.estado == 1 || inscripcion.estado == 2">
                                        <button type="button" class="btn btn-danger" @click="cancelarInscripcion(inscripcion.id)">Cancelar</button>
                                    </td>
                                    <td v-else>{{inscripcion.fec_cancela}}</td>
                                    <td>{{inscripcion.fec_termina}} </td>
                                    <td >
                                         <a target="_blank" v-if="inscripcion.fec_termina" class="btn btn-info" :href="aux_ruta + 'cepsas/newdiploma.php?id=' + inscripcion.id_alumno + '&curso=' + inscripcion.id+ '&inscripcion=' + inscripcion.id_curso">Certificado Curso</a>  
                                    </td>
 <!--
                                    <td>
                                        <a href="#" @click="editar(user)">
                                            <i class="fa fa-edit blue"></i>
                                        </a>
                                        /
                                        <a href="#" @click="deleteUser(user.id)">
                                            <i class="fa fa-trash red"></i>
                                        </a>

                                    </td> -->
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer">
                        <pagination :data="inscripciones" @pagination-change-page="getResults"></pagination>
                    </div>
                </div>
            <!-- /.card -->
            </div>
        </div>

        <div v-if="!$gate.isAdminOrAuthor()">
            <not-found></not-found>
        </div>

    <!-- Modal 
        <div class="modal fade" id="addNew" tabindex="-1" role="dialog" aria-labelledby="addNewLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" v-show="!editmode" id="addNewLabel">Agregar Usuario</h5>
                        <h5 class="modal-title" v-show="editmode" id="addNewLabel">Actualizar Usuario</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form @submit.prevent="editmode ? updateUser() : createUser()">
                        <div class="modal-body">
                            <div class="form-group">
                                <input v-model="form.name" type="text" name="name"
                                    placeholder="Nombre"
                                    class="form-control" :class="{ 'is-invalid': form.errors.has('name') }">
                                <has-error :form="form" field="name"></has-error>
                            </div>

                            <div class="form-group">
                                <input v-model="form.email" type="email" name="email"
                                    placeholder="Correo"
                                    class="form-control" :class="{ 'is-invalid': form.errors.has('email') }">
                                <has-error :form="form" field="email"></has-error>
                            </div>

                            <div class="form-group">
                                <textarea v-model="form.bio" name="bio" id="bio"
                                placeholder="Informacón (Opcional)"
                                class="form-control" :class="{ 'is-invalid': form.errors.has('bio') }"></textarea>
                                <has-error :form="form" field="bio"></has-error>
                            </div>


                            <div class="form-group">
                                <select name="type" v-model="form.type" id="type" class="form-control" :class="{ 'is-invalid': form.errors.has('type') }">
                                    <option value="">Selecione el rol de usuario</option>
                                    <option value="admin">Administrador</option>
                                    <option value="user">Estudiante</option>
                                    <option value="author">Tutor</option>
                                </select>
                                <has-error :form="form" field="type"></has-error>
                            </div>

                            <div class="form-group">
                                <label for='password'>Clave</label>
                                <input v-model="form.password" type="password" name="password" id="password"
                                class="form-control" :class="{ 'is-invalid': form.errors.has('password') }">
                                <has-error :form="form" field="password"></has-error>
                            </div>

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
                            <button v-show="editmode" type="submit" class="btn btn-success">Actualizar</button>
                            <button v-show="!editmode" type="submit" class="btn btn-primary">Crear</button>
                        </div>

                    </form>

                </div>
            </div>
        </div>-->
    </div>
</template>

<script>
    export default {
        data() {
            return {
                editmode: false,
                inscripciones : {},
                aux_ruta: '',
              /*  form: new Form({
                    id:'',
                    id_alumno : '',
                    id_curso: '',
                    password: '',
                    type: '',
                    bio: '',
                    photo: ''
                })*/
            }
        },
        methods: {
            getResults(page = 1) {
                axios.get(this.$parent.ruta + 'api/inscripcion?page=' + page)
                .then(response => {
                    this.inscripciones = response.data;
                });
            },
           
            loadInscripciones(){
                
                if(this.$gate.isAdminOrAuthor()){
                    axios.get(this.$parent.ruta + "api/getInscripciones").then(({ data }) => (this.inscripciones = data.inscripciones));
                    console.log(this.inscripciones);
                }
            },
            cancelarInscripcion(id){
                swal({
                    title: 'Estas seguro?',
                    text: "No podras revertir este proceso!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Si, cancelarla!'
                    }).then((result) => {

                        // Send request to the server
                         if (result.value) {
                                axios.delete(this.$parent.ruta + 'api/inscripcion/'+id).then(()=>{
                                        swal(
                                        'Borrado!',
                                        'Tu registro ha sido cancelado.',
                                        'Exito'
                                        )
                                    Fire.$emit('AfterCreate');
                                }).catch(()=> {
                                    swal("Failed!", "Algo ha salido mal.", "warning");
                                });
                         }
                    })
            },
            aceptarInscripcion(id){

                let me = this;
                swal({
                    title: 'Estas seguro de aceptar esta inscripción?',
                    text: "Tu no podras revertir esta acción!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Si, Aceptar!'
                    }).then((result) => {

                        // Send request to the server
                         if (result.value) {
                            axios.get(this.$parent.ruta +"api/activaInscripcion/"+id)
                            .then(function (data) {
                                me.inscripciones = data.inscripciones;
                                swal(
                                    'Activada!',
                                    'La inscripción ha sido activada.',
                                    'success'
                                    )
                                Fire.$emit('AfterCreate');
                            });
                             
                         }
                    })
            },
            createUser(){
                this.$Progress.start();
                this.aux_ruta = this.$parent.ruta;
                this.form.post(this.$parent.ruta +'api/user')
                .then(()=>{
                    Fire.$emit('AfterCreate');
                    $('#addNew').modal('hide')

                    toast({
                        type: 'success',
                        title: 'Usuario Creado correctamnte'
                        })
                    this.$Progress.finish();

                })
                .catch(()=>{

                })
            }
        }, 
        created() {
            Fire.$on('searching',() => {
                let query = this.$parent.search;
                axios.get(this.$parent.ruta +'api/findInscripcion?q=' + query)
                .then((data) => {
                    this.inscripciones = data.data
                })
                .catch(() => {

                })
            })
           this.loadInscripciones();
           Fire.$on('AfterCreate',() => {
               this.loadInscripciones();
           });
        //    setInterval(() => this.loadUsers(), 3000);
        }

    }
</script>