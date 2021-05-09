<template>
  <div class="ml-3">
    <v-dialog
      fullscreen
      v-model="cmDialog"
      v-if="cmDialog"
      hide-overlay
      @input="onInputUserDialog"
      transition="dialog-bottom-transition"
    >
      <v-card>
        <v-toolbar dark color="primary">
          <v-btn icon dark @click="cmDialog = false">
            <v-icon>mdi-close</v-icon>
          </v-btn>
          <v-toolbar-title>Incomes</v-toolbar-title>
          <v-spacer></v-spacer>
        </v-toolbar>
        <v-card-text>
          <!-- <v-card-title> Create Expense </v-card-title> -->
          <IncomeForm
            v-if="cmDialog"
            :isUpdate="update.dialog"
            @editIncome="handleEditIncome"
            @addIncome="handleAddIncome"
            :paymentList="$paymentList"
            :clientList="$clientList"
            :data="update.data"
            :errors="errors"
          />
        </v-card-text>
      </v-card>
    </v-dialog>
    <v-card>
      <v-card-title>
        Incomes
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
        :items="$income"
        :search="search"
        hide-default-footer
      >
        <template v-slot:item.createdAt="{ item }">
          {{ $m(item.createdAt).format("ll") }}
        </template>
        <template v-slot:item.actions="{ item }">
          <v-menu down left nudge-left="7rem">
            <template v-slot:activator="{ on, attrs }">
              <v-btn icon color="primary" dark v-bind="attrs" v-on="on">
                <v-icon> mdi-dots-vertical </v-icon>
              </v-btn>
            </template>

            <v-list>
              <v-list-item @click="handleView(item)" dense link>
                <v-icon size="20" color="error" class="mr-3">mdi-eye</v-icon>
                View
              </v-list-item>
              <v-divider></v-divider>
              <v-list-item @click="initUpdate(item)" dense link>
                <v-icon class="mr-2">mdi-pencil</v-icon>
                Edit
              </v-list-item>
              <v-divider></v-divider>
              <v-list-item @click="initDelete(item.id)" dense link>
                <v-icon class="mr-2">mdi-delete</v-icon>
                Delete
              </v-list-item>
            </v-list>
          </v-menu>
        </template>
      </v-data-table>
      <div class="text-center pt-2">
        <v-pagination
          :value="$pagination.currentPage"
          @input="onChangePage"
          :length="Math.ceil($pagination.totalPage / $pagination.perPage)"
        ></v-pagination>
      </div>
    </v-card>
    <fabCreateButton @click="initCreate()" />
    <CircleLoader center v-if="loading" size="84" speed="1" border-width="3" />
    <v-snackbar
      v-model="snackbar.action"
      :timeout="snackbar.timeout"
      :color="snackbar.color"
      top
      right
      class="font-weight-bold"
    >
      {{ snackbar.text }}
      <confirm
        title="Are you sure to delete?"
        subtitle="Once you delete, this action can't be undone"
        v-model="deletee.dialog"
        :loading="deletee.loading"
        @yes="handleDeleteIncome"
        @no="resetDelete"
      />
    </v-snackbar>
    <v-dialog
      :width="this.$vuetify.breakpoint.mdAndUp ? '30vw' : '80vw'"
      v-model="viewDetails"
    >
      <v-card v-if="viewIncomeInfo">
        <v-card-title><span class="mx-auto">View Details</span></v-card-title>
        <v-card-text>
          <v-form readonly>
            <v-row>
              <v-col cols="12">
                <v-text-field
                  v-bind="fieldOptions"
                  v-model="viewIncomeInfo.category.name"
                  label="Payment Category"
                ></v-text-field>
              </v-col>
              <v-col cols="12">
                <v-text-field
                  v-bind="fieldOptions"
                  v-model="viewIncomeInfo.createdBy.name"
                  label="Created by"
                ></v-text-field>
              </v-col>
              <v-col cols="12">
                <v-text-field
                  v-bind="fieldOptions"
                  v-model="viewIncomeInfo.createdBy.email"
                  label="Phone Number"
                ></v-text-field>
              </v-col>
              <v-col cols="12">
                <v-text-field
                  v-bind="fieldOptions"
                  v-model="viewIncomeInfo.createdBy.phone"
                  label="Phone Number"
                ></v-text-field>
              </v-col>

              <v-col cols="12">
                <v-text-field
                  v-bind="fieldOptions"
                  v-model="viewIncomeInfo.createdAt"
                  label="Created at"
                ></v-text-field>
              </v-col>
              <v-col cols="12">
                <v-text-field
                  v-bind="fieldOptions"
                  v-model="viewIncomeInfo.updatedAt"
                  label="Update at"
                ></v-text-field>
              </v-col>
              <v-col cols="12">
                <v-text-field
                  v-bind="fieldOptions"
                  v-model="viewIncomeInfo.incomeAmount"
                  label="Amount"
                ></v-text-field>
              </v-col>
              <v-col cols="12">
                <v-chip
                  dark
                  outlined
                  color="primary"
                  v-if="viewIncomeInfo.receiptFile"
                >
                  <v-icon class="mr-1">mdi-cloud-download</v-icon
                  ><a download :href="'/invoice-backend/public/storage/' + viewIncomeInfo.receiptFile"
                    >download file</a
                  >
                </v-chip>
              </v-col>
            </v-row>
          </v-form>
        </v-card-text>
      </v-card>
    </v-dialog>
  </div>
</template>

<script>
import moment from 'moment'
import formFieldMixin from "@/mixins/formFieldMixin";
import { mapActions, mapGetters } from "vuex";
import IncomeForm from "@/components/forms/IncomeForm.vue";
import fabCreateButton from "@/components/button/fabCreateButton";
import confirm from "@/components/dialog/confirm.vue";
import CircleLoader from "@/components/customs/CircleLoader";
import crudMixin from "@/mixins/crud-mixin";
export default {
    mixins: [crudMixin,formFieldMixin],
    name:'Income',
    components: {
        IncomeForm,
        fabCreateButton,
        CircleLoader,
        confirm
    },
    data() {
        return {
            cardloader: false,
            search: "",
            viewDetails:false,
            viewIncomeInfo:{
             category:{
               name:'',
               createdAt:''
             },
             createdBy:{
               name:''
             }
            },
            loading:false,
            errors:{},
             snackbar: {
                action: false,
                text: "",
                color: "success",
                timeout: "3000"
            },
            headers: [
                {
                    text: "User",
                    align: "start",
                    sortable: false,
                    value: "client.name"
                },
                { text: "Category", value: "category.name", sortable: false },
                { text: "Amount", value: "incomeAmount", sortable: false },
                { text: "Created by", value: "createdBy.name", sortable: false },
                { text: "Date", value: "createdAt", sortable: false },
                { text: "Actions", value: "actions", sortable: false }
     
            ],
            desserts: [
                {
                    user: "Lorem Ipsum",
                    category: "lorem ipsum dolor sit",
                    company: "",
                    // bills_file: "",
                    date: "",
                }
            ]
        };
    },
    computed: {
        ...mapGetters("INCOME", ["$income","$pagination"]),
        ...mapGetters("PAYMENT", ["$paymentList"]),
        ...mapGetters("CLIENT", ["$clientList"])
    },
    methods: {
      ...mapActions("INCOME", [
            "fetchIncome",
            "deleteIncome",
            "addIncome",
            "updateIncome",
        ]),
        ...mapActions("CLIENT",["fetchClientList"]),
        ...mapActions("PAYMENT",["fetchPaymentList"]),
        click() {
            this.dialog = true;
        },
        async onChangePage(page){
          this.tableLoader = true;
            await this.fetchIncome({page,per_page:10});
            this.tableLoader = false;
        },
        async onfetchIncome() {
            this.tableLoader = true;
            await this.fetchIncome({per_page:10,page:1});
            this.tableLoader = false;
        },
           async handleDeleteIncome() {
            this.loading = true;
            let res = await this.deleteIncome(this.deletee.id);
            if (res.error){

             this.enableSnackbar('failed','An error ocured when deleting Income')
            } 
            else {
                this.enableSnackbar('success','Income deleted successfully')
            }
            this.resetDelete();
            await this.onfetchIncome();
            this.loading = false;
        },
        async handleAddIncome(addExpense){

            this.loading = true;
            this.create.loading = true;
            let res = await this.addIncome(addExpense);
            if (res.error){
             this.enableSnackbar('failed','An error ocured when creating income')
             this.errors = res.errors;
            } 
            else {
                this.enableSnackbar('success','Income created successfully')
                this.resetCreate();
            }
           
            this.loading = false;
        },
          handleView(item){
         this.viewDetails=true,
         this.viewIncomeInfo=item
         this.viewIncomeInfo.createdAt= moment(this.viewIncomeInfo.createdAt).format('ll')
        },
        onInputUserDialog(dialog) {
            if (!dialog) {
                this.resetUpdate();
                this.resetCreate();
            }
            this.errors={}
        },
        // handleAddIncome(){

        // },
       async handleEditIncome(editData){
            this.loading = true;
            // this.create.loading = true;
            let res = await this.updateIncome(editData);
            if (res.error){
             this.enableSnackbar('failed','An error ocured when updating income')
             this.errors = res.errors;
            } 
            else {
                this.enableSnackbar('success','Income updated successfully')
                this.resetUpdate();
            }
            this.cmDialog=false
            this.loading = false

        },
        async onCreate(){
          this.loading=true
          await this.onfetchIncome();
          await this.fetchPaymentList()
          await this.fetchClientList()
          this.loading=false
        }
    },
    created() {
        this.onCreate()
    }
};
</script>

<style></style>
