<template>
    <div class="container">
        <v-app>
            <v-alert
              dense
              text
              type="success"
              v-if="displayMsg && statusSuccess"
            >
              {{textMsg}}
            </v-alert>
            <v-alert
              dense
              text
              type="error"
              v-if="displayMsg && statusError"
            >
              Ocorreu algum <strong>erro</strong> ao salvar o usuário.
            </v-alert>
            <v-data-table
                :headers="headers"
                :items="tbody"
                class="elevation-1"
                :loading="loading"
            >
                <template v-slot:top>
                <v-toolbar
                    flat
                >
                    <v-toolbar-title>Usuários</v-toolbar-title>
                    <v-divider
                    class="mx-4"
                    inset
                    vertical
                    ></v-divider>
                    <v-spacer></v-spacer>
                    <v-dialog
                    v-model="dialog"
                    max-width="500px"
                    >
                    <template v-slot:activator="{ on, attrs }">
                        <v-btn
                        color="primary"
                        dark
                        class="mb-2"
                        v-bind="attrs"
                        v-on="on"
                        >
                        Novo Usuário
                        </v-btn>
                    </template>
                    <v-card>
                        <v-card-title>
                        <span class="headline">{{formTitle}}</span>
                        </v-card-title>
                        <v-card-text>
                        <v-container>
                            <v-row>
                            <v-col
                                cols="12"
                                sm="12"
                                md="12"
                            >
                                <v-text-field
                                v-model="editedItem.name"
                                :rules="[rules.required]"
                                :error="error.name"
                                label="Nome"
                                ></v-text-field>
                            </v-col>
                            <v-col
                                cols="12"
                                sm="12"
                                md="12"
                            >
                                <v-text-field
                                v-model="editedItem.phone"
                                :rules="[rules.required]"
                                :error="error.phone"
                                label="Celular"
                                ></v-text-field>
                            </v-col>
                            <v-col
                                cols="12"
                                sm="12"
                                md="12"
                            >
                                <v-select
                                  v-model="editedItem.access_level"
                                  :rules="[rules.required]"
                                  :error="error.access_level"
                                  :items="items"
                                  label="Nível de Acesso"
                                  outlined
                                ></v-select>
                            </v-col>
                            <v-col
                                cols="12"
                                sm="12"
                                md="12"
                            >
                                <v-text-field
                                v-model="editedItem.password"
                                :rules="[rules.required]"
                                :error="error.password"
                                label="Senha"
                                ></v-text-field>
                            </v-col>
                            </v-row>
                        </v-container>
                        </v-card-text>

                        <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn
                            color="blue darken-1"
                            text
                            @click="close"
                        >
                            Cancelar
                        </v-btn>
                        <v-btn
                            color="blue darken-1"
                            text
                            @click="save"
                        >
                            Salvar
                        </v-btn>
                        </v-card-actions>
                    </v-card>
                    </v-dialog>
                </v-toolbar>
                </template>
                <template v-slot:item="props">
                  <tr>
                    <td>{{ props.item.name }}</td>
                    <td>{{ props.item.phone }}</td>
                    <td>{{ props.item.access_level }}</td>
                    <td>
                      <v-btn
                        depressed
                        color="error"
                        small
                        @click="upgrade(props.item)"
                        v-if="props.item.access_level === 'free'"
                      >
                        Upgrade
                      </v-btn>
                      <v-btn
                        depressed
                        color="primary"
                        small
                        @click="downgrade(props.item)"
                        v-else
                      >
                        Downgrade
                      </v-btn>
                    </td>                   
                  </tr>
                </template>
                <template v-slot:no-data>
                  Nenhum usuário encontrado!
                </template>
            </v-data-table>

        </v-app>
    </div>
</template>

<script>
  export default {
    name: 'Main',

    data: () => ({
      dialog: false,
      loading: false,
      statusSuccess: false,
      statusError: false,
      displayMsg: false,
      textMsg: '',
      items: ['free', 'premium'],
      rules: {
        required: value => !!value || 'Obrigatório.',
        email: value => {
          const pattern = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/
          return pattern.test(value) || 'E-mail inválido.'
        },
      },
      headers: [
        { text: 'Nome', value: 'name' },
        { text: 'Celular', value: 'phone' },
        { text: 'Nível de Acesso', value: 'access_level' },
        { text: 'Ações', value: 'actions' },
      ],
      tbody: [],
      editedIndex: -1,
      error: {
        name: false,
        phone: false,
        access_level: false,
        password: false,
      },
      defaultError: {
        name: false,
        phone: false,
        access_level: false,
        password: false,
      },
      editedItem: {
        name: '',
        phone: '',
        access_level: '',
        password: '',
        external_id: '',
      },
      defaultItem: {
        name: '',
        phone: '',
        access_level: '',
        password: '',
        external_id: '',
      },
    }),

    computed: {
      formTitle () {
        return this.editedIndex === -1 ? 'Novo Usuário' : 'Editar Usuário'
      },
    },

    watch: {
      dialog (val) {
        val || this.close()
      },
    },

    created () {
      this.initialize()
    },

    methods: {

      async upgrade(item) {
        console.log('upgrade item: ', item)
        item.access_level = "premium"

        let user = await axios.put('/api/users/update-access-level', item);
        console.log('upgrade() user: ', user)

        let headers = {'Authorization': `Bearer aSE1gIFBKbBqlQmZOOTxrpgPKgQkgshbLnt1NS3w`, "service-id": "qualifica", "app-users-group-id": 20 }
        let mLearnUser = await axios.put('https://api2.mlearn.mobi/integrator/qualifica/users/'+item.mlearn_id+'/upgrade', {}, {headers});
        console.log('upgrade() mLearnUser: ', mLearnUser)
      },

      async downgrade(item) {
        console.log('downgrade item: ', item)
        item.access_level = "free"

        let user = await axios.put('/api/users/update-access-level', item);
        console.log('downgrade() user: ', user)

        let headers = {'Authorization': `Bearer aSE1gIFBKbBqlQmZOOTxrpgPKgQkgshbLnt1NS3w`, "service-id": "qualifica", "app-users-group-id": 20 }
        let mLearnUser = await axios.put('https://api2.mlearn.mobi/integrator/qualifica/users/'+item.mlearn_id+'/downgrade', {}, {headers});
        console.log('downgrade() mLearnUser: ', mLearnUser)
      },

      async initialize () {
        try {
          this.loading = true
            let users = await axios.get('/api/users');
            this.tbody = []
            for (const element of users.data) {
                this.tbody.push(element);
            }
            this.loading = false
        } catch (error) {
            console.error(error.response.data);
            console.error(error.response.status);
            alert('Houve algum erro!');
        }
      },

      close () {
        this.dialog = false
        this.newContract = false
        this.$nextTick(() => {
          this.editedItem = Object.assign({}, this.defaultItem)
          this.editedIndex = -1
        })

        this.error = Object.assign({}, this.defaultError)
      },

      async save () {

        let isInvalidForm = false
        if(!this.editedItem.name){
          this.error.name = true;
          isInvalidForm = true
        }
        if(!this.editedItem.phone){
          this.error.phone = true;
          isInvalidForm = true
        }
        if(!this.editedItem.access_level){
          this.error.access_level = true;
          isInvalidForm = true
        }
        if(!this.editedItem.password){
          this.error.password = true;
          isInvalidForm = true
        }
        if(isInvalidForm) {
          return;
        }

        let user = await axios.post('/api/users/store', this.editedItem);
        console.log('save() user: ', user)
        if(user.status === 201) {

          let data = Object.assign({}, this.editedItem)
          data = {'msisdn': this.editedItem.phone, 'external_id': user.data.user_id, ...data}
          let headers = {'Authorization': `Bearer aSE1gIFBKbBqlQmZOOTxrpgPKgQkgshbLnt1NS3w`, "service-id": "qualifica", "app-users-group-id": 20 }
          let mLearnUser = await axios.post('https://api2.mlearn.mobi/integrator/qualifica/users', data, {headers});
          console.log('save() mLearnUser: ', mLearnUser)

          let updateMlearnId = await axios.put('/api/users/update-mlearn-id', {id: user.data.user_id, mlearn_id: mLearnUser.data.data.id});
          console.log('save() updateMlearnId: ', updateMlearnId)

          this.textMsg = "O usuário foi registrado com sucesso."
          this.displayMsg = true 
          this.statusSuccess = true
          this.tbody.push({id: user.data.user_id, ...this.editedItem});
          this.editedItem = Object.assign({}, this.defaultItem)
          setTimeout(function(){ 
            this.displayMsg = false 
            this.statusSuccess = false
          }.bind(this), 3000);
        } else {
            this.displayMsg = true 
            this.statusError = true
            setTimeout(function(){ 
              this.displayMsg = false 
              this.statusError = false
            }.bind(this), 3000);
        }
        this.close()
      },
    },
  }
</script>
