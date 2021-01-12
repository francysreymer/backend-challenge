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
              Ocorreu algum <strong>erro</strong> ao salvar o imóvel.
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
                    <v-toolbar-title>Imóveis</v-toolbar-title>
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
                        @click="newContract = false"
                        >
                        Novo Imóvel
                        </v-btn>
                        <v-divider
                        class="mx-4"
                        inset
                        vertical
                        ></v-divider>
                        <v-btn
                        color="success"
                        dark
                        class="mb-2"
                        v-bind="attrs"
                        v-on="on"
                        @click="newContract = true"
                        >
                        Novo Contrato
                        </v-btn>
                    </template>
                    <template v-if="newContract">
                      <contractor-component
                        :properties="tbody"
                        @save-contract="saveContract"
                        @cancel-contract="cancelContract"
                      >
                      </contractor-component>
                    </template>
                    <v-card v-else>
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
                                v-model="editedItem.owner_email"
                                :rules="[rules.required, rules.email]"
                                :error="error.owner_email"
                                label="Email do Proprietário"
                                ></v-text-field>
                            </v-col>
                            <v-col
                                cols="12"
                                sm="6"
                                md="6"
                            >
                                <v-text-field
                                v-model="editedItem.address"
                                :rules="[rules.required]"
                                :error="error.address"
                                label="Endereço"
                                ></v-text-field>
                            </v-col>
                            <v-col
                                cols="12"
                                sm="6"
                                md="6"
                            >
                                <v-text-field
                                v-model="editedItem.number"
                                label="Número"
                                ></v-text-field>
                            </v-col>
                            <v-col
                                cols="12"
                                sm="12"
                                md="12"
                            >
                                <v-text-field
                                v-model="editedItem.complement"
                                :rules="[rules.required]"
                                :error="error.complement"
                                label="Complemento"
                                ></v-text-field>
                            </v-col>
                            <v-col
                                cols="12"
                                sm="12"
                                md="12"
                            >
                                <v-text-field
                                v-model="editedItem.neighborhood"
                                :rules="[rules.required]"
                                :error="error.neighborhood"
                                label="Bairro"
                                ></v-text-field>
                            </v-col>
                            <v-col
                                cols="12"
                                sm="6"
                                md="6"
                            >
                                <v-text-field
                                v-model="editedItem.city"
                                :rules="[rules.required]"
                                :error="error.city"
                                label="Cidade"
                                ></v-text-field>
                            </v-col>
                            <v-col
                                cols="12"
                                sm="6"
                                md="6"
                            >
                                <v-text-field
                                v-model="editedItem.state"
                                :rules="[rules.required]"
                                :error="error.state"
                                label="Estado"
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
                    <v-dialog v-model="dialogDelete" max-width="500px">
                    <v-card>
                        <v-card-title class="headline">Tem certeza que deseja excluir este item?</v-card-title>
                        <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn color="blue darken-1" text @click="closeDelete">Cancelar</v-btn>
                        <v-btn color="blue darken-1" text @click="deleteItemConfirm">OK</v-btn>
                        <v-spacer></v-spacer>
                        </v-card-actions>
                    </v-card>
                    </v-dialog>
                </v-toolbar>
                </template>
                <template v-slot:item="props">
                  <tr>
                    <td>{{ props.item.owner_email }}</td>
                    <td>{{ props.item.address + ', ' + props.item.number + ', ' + props.item.city + ', ' + props.item.state }}</td>
                    <td>{{ props.item.hired ? 'Contratado' : 'Não Contratado' }}</td>
                    <td>
                      <v-btn
                        depressed
                        color="error"
                        small
                        @click="deleteItem(props.item)"
                      >
                        Excluir
                      </v-btn>
                    </td>                   
                  </tr>
                </template>
                <template v-slot:no-data>
                  Nenhum imóvel encontrado!
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
      dialogDelete: false,
      newContract: false,
      textMsg: '',
      rules: {
        required: value => !!value || 'Obrigatório.',
        email: value => {
          const pattern = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/
          return pattern.test(value) || 'E-mail inválido.'
        },
      },
      headers: [
        { text: 'E-mail do Proprietário', value: 'owner_email' },
        { text: 'Endereço', value: 'address' },
        { text: 'Status', value: 'hired' },
        { text: 'Ações', value: 'actions' },
      ],
      tbody: [],
      editedIndex: -1,
      error: {
        owner_email: false,
        address: false,
        number: false,
        neighborhood: false,
        city: false,
        state: false,
        complement: false,
      },
      defaultError: {
        owner_email: false,
        address: false,
        number: false,
        neighborhood: false,
        city: false,
        state: false,
        complement: false,
      },
      editedItem: {
        owner_email: '',
        address: '',
        number: '',
        neighborhood: '',
        city: '',
        state: '',
        complement: '',
      },
      defaultItem: {
        owner_email: '',
        address: '',
        number: '',
        neighborhood: '',
        city: '',
        state: '',
        complement: '',
      },
    }),

    computed: {
      formTitle () {
        return this.editedIndex === -1 ? 'Novo Imóvel' : 'Editar Imóvel'
      },
    },

    watch: {
      dialog (val) {
        val || this.close()
      },
      dialogDelete (val) {
        val || this.closeDelete()
      },
    },

    created () {
      this.initialize()
    },

    methods: {

      cancelContract() {
        this.dialog = false
        this.newContract = false
      },

      saveContract() {
        this.dialog = false
        this.newContract = false

        this.displayMsg = true 
        this.statusSuccess = true
        this.textMsg = "O contrato foi registrado com sucesso."
        setTimeout(function(){ 
          this.displayMsg = false 
          this.statusSuccess = false
        }.bind(this), 3000);

        this.initialize()
      },

      validateEmail(email) {
        console.log('email: ', email)
        return /^[^@]+@\w+(\.\w+)+\w$/.test(email)
      },

      async initialize () {
        try {
          this.loading = true
            let properties = await axios.get('/api/properties');
            this.tbody = []
            for (const element of properties.data) {
                this.tbody.push(element);
            }
            this.loading = false
        } catch (error) {
            console.error(error.response.data);
            console.error(error.response.status);
            alert('Houve algum erro!');
        }
      },

      deleteItem (item) {
        this.editedIndex = this.tbody.indexOf(item)
        this.editedItem = Object.assign({}, item)
        this.dialogDelete = true
      },

      async deleteItemConfirm () {
        this.tbody.splice(this.editedIndex, 1)

        this.loading = true

        try {
            let properties = await axios.get('/api/properties/delete/'+this.editedItem.id);
        } catch (error) {
            console.error(error.response.data);
            console.error(error.response.status);
            alert('Houve algum erro!');
        }

        this.loading = false

        this.dialogDelete = false
        this.$nextTick(() => {
          this.editedItem = Object.assign({}, this.defaultItem)
          this.editedIndex = -1
        })
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

      async closeDelete () {
        this.dialogDelete = false
        this.$nextTick(() => {
          this.editedItem = Object.assign({}, this.defaultItem)
          this.editedIndex = -1
        })
      },

      async save () {

        let isInvalidForm = false
        if(!this.editedItem.owner_email || !this.validateEmail(this.editedItem.owner_email)){
          this.error.owner_email = true;
          isInvalidForm = true
        }
        if(!this.editedItem.address){
          this.error.address = true;
          isInvalidForm = true
        }
        if(!this.editedItem.neighborhood){
          this.error.neighborhood = true;
          isInvalidForm = true
        }
        if(!this.editedItem.city){
          this.error.city = true;
          isInvalidForm = true
        }
        if(!this.editedItem.state){
          this.error.state = true;
          isInvalidForm = true
        }
        if(isInvalidForm) {
          return;
        }

        let property = await axios.post('/api/properties/store', this.editedItem);
        console.log('save() property: ', property)
        if(property.status === 201) {
            this.textMsg = "O imóvel foi registrado com sucesso."
            this.displayMsg = true 
            this.statusSuccess = true
            this.tbody.push({active: true, hired: false, id: property.data.property_id, ...this.editedItem});
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
