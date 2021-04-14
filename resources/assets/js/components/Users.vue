<template>
    <div class="container">
        <div class="row mt-5" v-if="$gate.isAdminOrAuthor()">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Usuarios</h3>

                        <div class="card-tools">
                             <button class="btn btn-success" @click="newModal">Agregar Usuario <i class="fas fa-user-plus fa-fw"></i></button>
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        Tipo
                        <select name="type" v-model="filtro_tipo" id="type" class="form-control">
                            <option value="">Filtrar rol de usuario</option>
                            <option value="admin">Administrador</option>
                            <option value="user">Estudiante</option>
                            <option value="author">Tutor</option>
                        </select>
                    </div>
                    <div class="card-body table-responsive p-0">
                        <table class="table table-hover">
                            <tbody>
                                <tr>
                                    <th>ID</th>
                                    <th>Nombre</th>
                                    <th>Correo</th>
                                    <th>Registrado el</th>
                                    <th>Modificado el</th>
                                </tr>
                                <tr v-for="user in users.data" :key="user.id">
                                    <td>{{user.id}}</td>
                                    <td>{{user.name}}</td>
                                    <td>{{user.email}}</td>
                                    <td v-if="user.type == 'admin'" >Administrador</td>
                                    <td v-if="user.type == 'user'" >Estudiante</td>
                                    <td v-if="user.type == 'author'" >Tutor</td>
                                    <td>{{user.created_at | myDate}}</td>

                                    <td>
                                        <a href="#" @click="editModal(user)">
                                            <i class="fa fa-edit blue"></i>
                                        </a>
                                        /
                                        <a href="#" @click="deleteUser(user.id)">
                                            <i class="fa fa-trash red"></i>
                                        </a>
                                        <a href="#" @click="modalInscripcion(user.id)" class="btn btn-warning">
                                            Inscribir
                                        </a>

                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer">
                        <pagination :data="users" @pagination-change-page="getResults"></pagination>
                    </div>
                </div>
            <!-- /.card -->
            </div>
        </div>

        <div v-if="!$gate.isAdminOrAuthor()">
            <not-found></not-found>
        </div>

    <!-- Modal -->
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
        </div>

        <div class="modal fade" id="inscriptionModal" tabindex="-1" role="dialog" aria-labelledby="inscriptionModalLabel" aria-hidden="true">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="inscriptionModalLabel">Registrar inscripción </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <div class="form-group">
                  <label for="curso">Curso
                    <select class="form-control" id="curso" v-model="idCurso">
                      <option>1</option>
                    </select>
                  </label>
                </div>
                <div class="form-row">
                  <div class="form-group col-md-6">
                    <label for="puntaje">Puntaje
                      <input type="number" class="form-control" id="puntaje" placeholder="Puntaje">
                    </label>
                  </div>
                  <div class="form-group col-md-6">
                    <label for="fecha_inscripcion">Fecha Inscripción
                      <input type="date" class="form-control" id="fecha_inscripcion" placeholder="Fecha Inscripción">
                    </label>
                  </div>
                  <div class="form-group col-md-6">
                    <label for="fecha_activacion">Fecha Activación
                      <input type="date" class="form-control" id="fecha_activacion" placeholder="Fecha Activación">
                    </label>
                  </div>
                  <div class="form-group col-md-6">
                    <label for="fecha_termina">Fecha Termina
                      <input type="date" class="form-control" id="fecha_termina" placeholder="Fecha Termina">
                    </label>
                  </div>
                  <div class="form-group col-md-6">
                    <label for="fecha_vence">Fecha Vencimiento
                      <input type="date" class="form-control" id="fecha_vence" placeholder="Fecha Vencimiento">
                    </label>
                  </div>
                  <div class="form-group col-md-6">
                    <label for="duracion_nro">Duración
                      <input type="number" step="any" class="form-control" id="duracion_nro" placeholder="Duración">
                    </label>
                    <label for="duracion_tipo">En 
                      <select class="form-control" id="duracion_tipo">
                        <option>1</option>
                        <option>2</option>
                        <option>3</option>
                        <option>4</option>
                        <option>5</option>
                      </select>
                    </label>
                  </div>
                </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-success">Registrar</button>
              </div>
            </div>
          </div>
        </div>
    </div>
</template>

<script>
  export default {
    data() {
      return {
        editmode: false,
        users : {},
        filtro_tipo: '',
        form: new Form({
            id:'',
            name : '',
            email: '',
            password: '',
            type: '',
            bio: '',
            photo: ''
        }),

        //inscripción
        inscripcionShow : false,
        idCurso : 0,
        puntaje : 0,
        fechaInscripcion : 0,
        fechaActivacion : 0,
        fechaTermina : 0, 
        fechaVencimiento : 0,
        duraNro : 0,
        duraTipo : 0

      }
    },
    methods: {
        getResults(page = 1) {
            axios.get(this.$parent.ruta + 'api/user?page=' + page)
            .then(response => {
                this.users = response.data;
            });
        },
        updateUser(){
            this.$Progress.start();
            // console.log('Editing data');
            this.form.put(this.$parent.ruta + 'api/user/'+this.form.id)
            .then(() => {
              // success
              $('#addNew').modal('hide');
              swal(
                'Actualizado!',
                'La información ha sido actualizada',
                'success'
              )
              this.$Progress.finish();
              Fire.$emit('AfterCreate');
            })
            .catch(() => {
                this.$Progress.fail();
            });

        },
        editModal(user){
            this.editmode = true;
            this.form.reset();
            $('#addNew').modal('show');
            this.form.fill(user);
        },
        newModal(){
            this.editmode = false;
            this.form.reset();
            $('#addNew').modal('show');
        },
        deleteUser(id){
          swal({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
          }).then((result) => {

            // Send request to the server
            if (result.value) {
            this.form.delete(this.$parent.ruta + 'api/user/'+id).then(()=>{
              swal(
                'Deleted!',
                'Your file has been deleted.',
                'success'
              )
              Fire.$emit('AfterCreate');
            }).catch(()=> {
              swal("Failed!", "There was something wronge.", "warning");
            });
            }
          })
        },
        loadUsers(){
          if(this.$gate.isAdminOrAuthor()){
            axios.get(this.$parent.ruta + "api/user").then(({ data }) => (this.users = data));
          }
        },
        createUser(){
          this.$Progress.start();
          this.form.post(this.$parent.ruta + 'api/user')
          .then(()=>{
            Fire.$emit('AfterCreate');
            $('#addNew').modal('hide')

            toast({
              type: 'success',
              title: 'User Created in successfully'
              })
            this.$Progress.finish();
          })
          .catch(() => {})
        },
        modalInscripcion(){
          this.inscripcionShow = false;
          this.form.reset();
          $('#inscriptionModal').modal('show');
        },
    },
    created() {
      Fire.$on('searching',() => {
        let query = this.$parent.search;
        axios.get(this.$parent.ruta + 'api/findUser?q=' + query)
        .then((data) => {
          this.users = data.data
        })
        .catch(() => {})
      })
      this.loadUsers();
      Fire.$on('AfterCreate',() => {
        this.loadUsers();
      });
    }
  }
</script>
