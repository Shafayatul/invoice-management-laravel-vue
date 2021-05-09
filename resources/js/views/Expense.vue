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
          <v-toolbar-title>Expense</v-toolbar-title>
          <v-spacer></v-spacer>
        </v-toolbar>
        <v-card-text>
          <!-- <v-card-title> Create Expense </v-card-title> -->
          <ExpenseForm
            v-if="cmDialog"
            :isUpdate="update.dialog"
            :errors="errors"
            @editExpense="handleEditExpense"
            @addExpense="handleAddExpense"
            :paymentList="$paymentList"
            :data="update.data"
          />
        </v-card-text>
      </v-card>
    </v-dialog>
    <v-card>
      <v-card-title>
        Expenses
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
        :items="$expense"
        :search="search"
        hide-default-footer
        :items-per-page="+$pagination.perPage"
      >
        <template v-slot:item.expenseDate="{ item }">
          {{ $m(item.expenseDate).format("ll") }}
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
        @yes="handleDeleteExpense"
        @no="resetDelete"
      />
    </v-snackbar>
    <v-dialog
      :width="this.$vuetify.breakpoint.mdAndUp ? '30vw' : '80vw'"
      v-model="viewDetails"
    >
      <v-card v-if="viewExpenseInfo">
        <v-card-title><span class="mx-auto">View Details</span></v-card-title>
        <v-card-text>
          <v-form readonly>
            <v-row>
              <v-col cols="12">
                <v-text-field
                  v-bind="fieldOptions"
                  v-model="viewExpenseInfo.category.name"
                  label="Payment Category"
                ></v-text-field>
              </v-col>
              <v-col cols="12">
                <v-text-field
                  v-bind="fieldOptions"
                  v-model="viewExpenseInfo.createdAt"
                  label="Created by"
                ></v-text-field>
              </v-col>
              <v-col cols="12">
                <v-text-field
                  v-bind="fieldOptions"
                  v-model="viewExpenseInfo.expenseDate"
                  label="Expense Date"
                ></v-text-field>
              </v-col>
              <v-col cols="12">
                <v-text-field
                  v-bind="fieldOptions"
                  v-model="viewExpenseInfo.expenseAmount"
                  label="Expense amount"
                ></v-text-field>
              </v-col>
              <v-col cols="12">
                <v-text-field
                  v-bind="fieldOptions"
                  v-model="viewExpenseInfo.user.name"
                  label="Expensed by"
                ></v-text-field>
              </v-col>
              <v-col cols="12">
                <v-chip
                  dark
                  outlined
                  color="primary"
                  v-if="viewExpenseInfo.billsFile"
                >
                  <v-icon class="mr-1">mdi-cloud-download</v-icon
                  ><a download :href="'/storage/' + viewExpenseInfo.billsFile"
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
import ExpenseForm from "@/components/forms/ExpenseForm.vue";
import fabCreateButton from "@/components/button/fabCreateButton";
import confirm from "@/components/dialog/confirm.vue";
import CircleLoader from "@/components/customs/CircleLoader";
import crudMixin from "@/mixins/crud-mixin";
export default {
    mixins: [crudMixin,formFieldMixin],
    name:'Expenses',
    components: {
        ExpenseForm,
        fabCreateButton,
        CircleLoader,
        confirm
    },
    data() {
        return {
            cardloader: false,
            search: "",
            loading:false,
            viewDetails:false,
             viewExpenseInfo:{
             category:{
               name:'',
               createdAt:''
             },
             user:{
               name:''
             }
            },
            errors:{},
             snackbar: {
                action: false,
                text: "",
                color: "success",
                timeout: "3000"
            },
            headers: [
                {
                    text: "Expensed by",
                    align: "start",
                    sortable: false,
                    value: "user.name"
                },
                { text: "Company", value: "company.name", sortable: false },
                { text: "Amount", value: "expenseAmount", sortable: false },
                { text: "Category", value: "category.name", sortable: false },
                { text: "Date", value: "expenseDate", sortable: false },
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
        ...mapGetters("EXPENSE", ["$expense","$pagination"]),
        ...mapGetters("PAYMENT", ["$paymentList"])
        // ...mapGetters("COMPANY", ["$companyList"])
    },
    methods: {
      ...mapActions("EXPENSE", [
            "fetchExpense",
            "deleteExpense",
            "addExpense",
            "updateExpense",
        ]),
        // ...mapActions("COMPANY",["fetchCompanyList"]),
        ...mapActions("PAYMENT",["fetchPaymentList"]),
        async onChangePage(page){
          this.tableLoader = true;
            await this.fetchExpense({page,per_page:10});
            this.tableLoader = false;
        },
        click() {
            this.dialog = true;
        },
        handleView(item){
         this.viewDetails=true,
         this.viewExpenseInfo=item
         this.viewExpenseInfo.createdAt= moment(this.viewExpenseInfo.createdAt).format('ll')
         this.viewExpenseInfo.expenseDate= moment(this.viewExpenseInfo.expenseDate).format('ll')
        },
        async onfetchExpense() {
            this.tableLoader = true;
            await this.fetchExpense({per_page:10,page:1});
            this.tableLoader = false;
        },
           async handleDeleteExpense() {
            this.loading = true;
            let res = await this.deleteExpense(this.deletee.id);
            if (res.error){

             this.enableSnackbar('failed','An error ocured when deleting Expense')
             
            } 
            else {
                this.enableSnackbar('success','Expense deleted successfully')
            }
            this.resetDelete();
            this.loading = false;
        },
        async handleAddExpense(addExpense){
 
            this.loading = true;
            this.create.loading = true;
            let res = await this.addExpense(addExpense);
            if (res.error){

             this.enableSnackbar('failed','An error ocured when creating expense')
             this.errors = res.errors;
            } 
            else {
                this.enableSnackbar('success','Expense created successfully')
                this.resetCreate();
            }
            this.loading = false;
        },
        onInputUserDialog(dialog) {
            if (!dialog) {
                this.resetUpdate();
                this.resetCreate();
            }
            this.errors={}
        },
        // handleAddExpense(){

        // },
       async handleEditExpense(editData){

            this.loading = true;
            // this.create.loading = true;
            let res = await this.updateExpense(editData);
            if (res.error){

             this.enableSnackbar('failed','An error ocured when updating expense')
             this.errors = res.errors;
            } 
            else {
                this.enableSnackbar('success','Expense updated successfully')
                this.resetUpdate();
            }
            this.loading = false

        },
        async onCreate(){
          this.loading=true
          await this.onfetchExpense();
          await this.fetchPaymentList()
          this.loading=false
        }
    },
    created() {
       
        this.onCreate()
    }
};
</script>

<style></style>
