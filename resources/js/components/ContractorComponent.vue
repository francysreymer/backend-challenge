<template>
  <v-card>
      <v-card-title>
      <span class="headline">Novo Contrato</span>
      </v-card-title>

      <v-card-text>
      <v-container>
          <v-row>
          <v-col
              cols="12"
              sm="12"
              md="12"
          >
              <v-combobox
                v-model="property"
                :items="combobox"
                :rules="[rules.required]"
                :error="error.owner_email"
                item-text="description"
                item-value="id"
                label="Imóvel"              
              ></v-combobox>
          </v-col>
          <v-col
              cols="12"
              sm="12"
              md="12"
          >
            <v-select
              v-model="editedItem.kind_of_person"
              :items="items"
              :rules="[rules.required]"
              @change="changeKindOfPerson"
              label="Pessoa Física ou Pessoa Jurídica"
            ></v-select>
          </v-col>
          <v-col
              cols="12"
              sm="12"
              md="12"
              v-show="showDocument"
          >
              <v-text-field
              v-model="editedItem.document"
              v-mask="maskDocument"
              :rules="[rules.required]"
              :label="msgDocument + ' do Contratante'"
              ></v-text-field>
          </v-col>
          <v-col
              cols="12"
              sm="12"
              md="12"
          >
              <v-text-field
              v-model="editedItem.contractor_email"
              :rules="[rules.required, rules.email]"
              :error="error.contractor_email"
              label="Email do Contratante"
              ></v-text-field>
          </v-col>
          <v-col
              cols="12"
              sm="12"
              md="12"
          >
              <v-text-field
              v-model="editedItem.contractor_full_name"
              :rules="[rules.required]"
              :error="error.contractor_full_name"
              label="Nome Completo do Contratante"
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
          @click="cancel"
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
</template>

<script>
  import {TheMask} from 'vue-the-mask'
  Vue.directive(TheMask)

  export default {
    name: 'Contractor',
    model: {
      prop: 'properties',
      event: 'changeModel'
    },
    props: {
      properties: {
        type: Array,
        default: null,
      },
    },
    mounted: function() {
      console.log('mounted properties: ', this.properties)
      for(let i=0; i<this.properties.length; i++) {
        if(!this.properties[i].hired && this.properties[i].active) {
          this.properties[i].description = this.properties[i].address + ',' + (this.properties[i].number ? this.properties[i].number: '')  + ',' + (this.properties[i].complement ? this.properties[i].complement : '') + ',' + this.properties[i].neighborhood;
          this.combobox.push(this.properties[i]);
        }
      }
    },
    data: () => ({
      rules: {
        required: value => !!value || 'Obrigatório.',
        email: value => {
          const pattern = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/
          return pattern.test(value) || 'E-mail inválido.'
        },
      },
      property: [],
      combobox: [],
      error: {
        property_id: false,
        kind_of_person: false,
        document: false,
        contractor_email: false,
        contractor_full_name: false,
      },
      defaultError: {
        property_id: false,
        kind_of_person: false,
        document: false,
        contractor_email: false,
        contractor_full_name: false,
      },
      editedItem: {
        property_id: '',
        kind_of_person: '',
        document: '',
        contractor_email: '',
        contractor_full_name: '',
      },
      defaultItem: {
        property_id: '',
        kind_of_person: '',
        document: '',
        contractor_email: '',
        contractor_full_name: '',
      },
      showDocument: false,
      msgDocument: 'Documento',
      maskDocument: '###.###.###-##',
      items: ['PF', 'PJ'],
    }),

    methods: {

      changeKindOfPerson(value) {
        if(value === 'PF') {
          this.maskDocument = '###.###.###-##'
          this.msgDocument = 'CPF'
          this.showDocument = true
        } else if (value === 'PJ') {
          this.maskDocument = '##.###.###/####-##'
          this.msgDocument = 'CNPJ'
          this.showDocument = true
        }
      },

      validateEmail(email) {
        console.log('email: ', email)
        return /^[^@]+@\w+(\.\w+)+\w$/.test(email)
      },

      cancel() {
        this.$emit('cancel-contract');
      },

      async save () {

        let isInvalidForm = false
        if(!this.property.id){
          alert('Não existe imóvel para ser contratado!')
          return
        }
        if(!this.property){
          this.error.property_id = true;
          isInvalidForm = true
        }
        if(!this.editedItem.kind_of_person){
          this.error.kind_of_person = true;
          isInvalidForm = true
        }
        if(!this.editedItem.document){
          this.error.document = true;
          isInvalidForm = true
        }
        if(!this.editedItem.contractor_email || !this.validateEmail(this.editedItem.contractor_email)){
          this.error.contractor_email = true;
          isInvalidForm = true
        }
        if(!this.editedItem.contractor_full_name){
          this.error.contractor_full_name = true;
          isInvalidForm = true
        }

        if(isInvalidForm) {
          return;
        }
        this.editedItem.property_id = this.property.id

        let contractor = await axios.post('/api/contracts/store', this.editedItem);
        console.log('save() contractor: ', contractor)
        if(contractor.status === 201) {
            this.editedItem.id = contractor.data.contractor_id;
            this.$emit('save-contract', this.editedItem);
        } else {
            alert('Não foi possível salvar o contrato!')
        }
      },
    },
  }
</script>
