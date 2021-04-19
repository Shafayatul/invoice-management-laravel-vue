<template>
  <div class="ml-3">
    <v-dialog @input="onInputCompanyDialog" v-model="cmDialog" :width="this.$vuetify.breakpoint.mdAndUp? '30vw' :'80vw'">
      <v-card>
        <v-card-text>
          <InvoiceForm :isUpdate='update.dialog' :data='update.data' />
        </v-card-text>
      </v-card>
    </v-dialog>
    <v-card>
      <v-card-title>
        Invoice
        <v-spacer></v-spacer>
        <v-text-field
          v-model="search"
          append-icon="mdi-magnify"
          label="Search"
          single-line
          hide-details
        ></v-text-field>
      </v-card-title>
      <v-data-table
        :loading="cardloader"
        loading-text="Loading... Please wait"
        :headers="headers"
        :items="desserts"
        :search="search"
      >
        <template v-slot:item.actions="{ item }">
          <v-menu down left nudge-left="7rem">
            <template v-slot:activator="{ on, attrs }">
              <v-btn icon color="primary" dark v-bind="attrs" v-on="on">
                <v-icon> mdi-dots-vertical </v-icon>
              </v-btn>
            </template>

            <v-list>
              <v-list-item @click="initUpdate(item)" dense link>
                <v-icon  class="mr-2">mdi-pencil</v-icon>
                Edit
              </v-list-item>
              <v-divider></v-divider>
              <v-list-item dense link>
                <v-icon class="mr-2">mdi-delete</v-icon>
                Delete
              </v-list-item>
            </v-list>
          </v-menu>
        </template>
      </v-data-table>
    </v-card>
    <fabCreateButton @click="initCreate()"/>
  </div>
</template>

<script>
import InvoiceForm from "@/components/forms/InvoiceForm.vue";
import fabCreateButton from "@/components/button/fabCreateButton";
import crudMixin from "@/mixins/crud-mixin"
export default {
  mixins:[crudMixin],
  components:{
    InvoiceForm, fabCreateButton,
  },
  data(){
    return{
      cardloader:false,
      search:'',
       headers: [
          {
            text: 'Client',
            align: 'start',
            sortable: false,
            value: 'client',
          },
          { text: 'Compnay', value: 'company_id',sortable: false },
          { text: 'Type', value: 'sending_type', sortable: false },
          { text: 'Date', value: 'sending_date',sortable: false },
          { text: 'Recurring period', value: 'recurring_period', sortable: false },
          { text: 'Created by', value: 'created_by', sortable: false }
          
        
        ],
        desserts: [
        {
          name: 'Lorem Ipsum',
          address: 'lorem ipsum dolor sit',
        },
        ]
    }
  },
  methods:{
    click(){
      this.dialog=true;
    },
    onInputInvoiceDialog(){
       if(!dialog){
        this.resetUpdate()
        this.resetCreate()
      }
    }
  }
}
</script>

<style>

</style>