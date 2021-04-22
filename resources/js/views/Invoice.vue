<template>
  <div class="ml-3">
    <v-dialog
      @input="onInputInvoiceDialog"
      v-model="cmDialog"
      v-if="cmDialog"
      :width="this.$vuetify.breakpoint.mdAndUp ? '30vw' : '80vw'"
    >
      <v-card>
        <v-card-text>
          <InvoiceForm
            v-if="cmDialog"
            :errors='errors'
            @editInvoice="handleEditInvoice"
            :companyList="$companyList"
            @addInvoice="handleAddInvoice"
            :isUpdate="update.dialog"
            :data="update.data"
          />
        </v-card-text>
      </v-card>
    </v-dialog>
    <v-card>
      <v-card-title>
        Invoice
        <v-spacer></v-spacer>
        <v-text-field
          v-model="search"
          outlined
          dense
          prepend-inner-icon="mdi-magnify"
          label="Search"
          single-line
          hide-details
        ></v-text-field>
      </v-card-title>
      <v-data-table
        :loading="cardloader"
        loading-text="Loading... Please wait"
        :headers="headers"
        :items="$invoice"
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
                <v-icon class="mr-2">mdi-pencil</v-icon>
                Edit
              </v-list-item >
              <v-divider></v-divider>
              <v-list-item @click="initDelete(item.id)" dense link>
                <v-icon class="mr-2">mdi-delete</v-icon>
                Delete
              </v-list-item>
            </v-list>
          </v-menu>
        </template>
      </v-data-table>
    </v-card>
    <fabCreateButton @click="initCreate()" />
    <v-snackbar
      v-model="snackbar.action"
      :timeout="snackbar.timeout"
      :color="snackbar.color"
      top
      right
      class="font-weight-bold"
    >
      {{ snackbar.text }}
    </v-snackbar>
    <CircleLoader center v-if="loading" size="84" speed="1" border-width="3" />
    <!-- Start Confirm delete action -->
    <confirm
      title="Are you sure to delete?"
      subtitle="Once you delete, this action can't be undone"
      v-model="deletee.dialog"
      :loading="deletee.loading"
      @yes="handleDeleteInvoice"
      @no="resetDelete"
    />
    <!-- End Confirm delete action -->
  </div>
</template>

<script>
import { mapActions, mapGetters } from "vuex";
import confirm from "@/components/dialog/confirm.vue";
import CircleLoader from "@/components/customs/CircleLoader";
import InvoiceForm from "@/components/forms/InvoiceForm.vue";
import fabCreateButton from "@/components/button/fabCreateButton";
import crudMixin from "@/mixins/crud-mixin"
export default {
  name:"invoice",
  mixins:[crudMixin],
  components:{
    InvoiceForm, fabCreateButton,confirm,CircleLoader
  },
  data(){
    return{
      cardloader:false,
      loading: false,
      tableLoader: false,
      search:'',
      errors: {},
      snackbar: {
                action: false,
                text: "",
                color:"success",
                timeout:'3000'
            },
       headers: [
          {
            text: 'Client',
            align: 'start',
            sortable: false,
            value: 'client.name',
          },
          { text: 'Compnay', value: 'companies.name',sortable: false },
          { text: 'Type', value: 'sendingType', sortable: false },
          { text: 'Date', value: 'sendingDate',sortable: false },
          { text: 'Recurring period', value: 'recurringPeriod', sortable: false },
          { text: 'Created by', value: 'createdBy.name', sortable: false },
          { text: "Actions", value: "actions", sortable: false },
          
        
        ],
        desserts: [
        {
          name: 'Lorem Ipsum',
          address: 'lorem ipsum dolor sit',
        },
        ]
    }
  },
  computed:{
    ...mapGetters("INVOICE", ["$invoice"]),
    ...mapGetters("COMPANY", ["$companyList"])
  },
  methods:{
    ...mapActions("INVOICE", [
            "fetchInvoice",
            "deleteInvoice",
            "addInvoice",
            "updateInvoice"
        ]),
    ...mapActions("COMPANY",["fetchCompanyList"]),
    click(){
      this.dialog=true;
    },
    onInputInvoiceDialog(dialog) {
            if (!dialog) {
                this.resetUpdate();
                this.resetCreate();
            }
        },
    async CompanyList(){
            this.loader=true
            await this.fetchCompanyList()
            this.loading=false
        },
    async onFetchInvoice() {
            this.tableLoader = true;
            await this.fetchInvoice();
            this.tableLoader = false;
        },
        async handleAddInvoice(invoiceDetails) {
            this.loading = true;
            console.log(invoiceDetails);
            // this.create.loading = true;
            let res = await this.addInvoice(invoiceDetails);
            if (res.error){
             console.log(res.error);
             this.enableSnackbar('failed','An error ocured when creating Invoice')
             console.log(res,'errr response');
             this.errors = res.errors;
            } 
            else {
                this.enableSnackbar('success','Invoice created successfully')
                this.resetCreate();
            }
            this.loading = false;
        },
        async handleEditInvoice(invoiceDetails) {
            this.loading = true;
            console.log(invoiceDetails);
            // this.create.loading = true;
            let res = await this.updateInvoice(invoiceDetails,);
            if (res.error){
             console.log(res.error);
             this.enableSnackbar('failed','An error ocured when editing Invoice')
            } 
            else {
                this.enableSnackbar('success','Invoice updated successfully')
            }
            this.resetUpdate();
            this.loading = false;
        },
        async handleDeleteInvoice() {
            this.loading = true;
            let res = await this.deleteInvoice(this.deletee.id);
            if (res.error){
             console.log(res.error);
             this.enableSnackbar('failed','An error ocured when deleting Invoice')
            } 
            else {
                this.enableSnackbar('success','Invoice deleted successfully')
            }
            this.resetDelete();
            this.loading = false;
        }
  },
   created() {
        this.onFetchInvoice();
        this.CompanyList()
   
    }
}
</script>

<style>

</style>