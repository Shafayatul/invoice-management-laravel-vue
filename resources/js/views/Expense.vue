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
          <!-- <v-toolbar-items>
            <v-btn dark text @click="dialog = false"> Save </v-btn>
          </v-toolbar-items> -->
        </v-toolbar>
        <v-card-text>
          <!-- <v-card-title> Create Expense </v-card-title> -->
          <ExpenseForm
            v-if="cmDialog"
            :isUpdate="update.dialog"
            @editExpense="handleEditExpense"
            @addExpense="handleAddExpense"
            :data="update.data"
          />
        </v-card-text>
        <!-- <v-card-actions>
          <v-btn class="ml-6 mb-2" color="primary" outlined> ADD </v-btn>
        </v-card-actions> -->
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
  </div>
</template>

<script>
import { mapActions, mapGetters } from "vuex";
import ExpenseForm from "@/components/forms/ExpenseForm.vue";
import fabCreateButton from "@/components/button/fabCreateButton";
import confirm from "@/components/dialog/confirm.vue";
import CircleLoader from "@/components/customs/CircleLoader";
import crudMixin from "@/mixins/crud-mixin";
export default {
    mixins: [crudMixin],
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
                    value: "user.name"
                },
                { text: "Company", value: "company.name", sortable: false },
                { text: "Amount", value: "expenseAmount", sortable: false },
                { text: "Bills File", value: "billsFile", sortable: false },
                { text: "Date", value: "expenseDate", sortable: false },
                {
                    text: "Expense Type",
                    value: "expense_type",
                    sortable: false
                },
                { text: "Actions", value: "actions", sortable: false }
     
            ],
            desserts: [
                {
                    user: "Lorem Ipsum",
                    category: "lorem ipsum dolor sit",
                    company: "",
                    bills_file: "",
                    date: "",
                    expense_type: ""
                }
            ]
        };
    },
    computed: {
        ...mapGetters("EXPENSE", ["$expense"]),
        ...mapGetters("COMPANY", ["$companyList"])
    },
    methods: {
      ...mapActions("EXPENSE", [
            "fetchExpense",
            "deleteExpense",
            "addExpense",
            "updateExpense",
        ]),
        ...mapActions("COMPANY",["fetchCompanyList"]),
        click() {
            this.dialog = true;
        },
        async onfetchExpense() {
            this.tableLoader = true;
            await this.fetchExpense();
            this.tableLoader = false;
        },
           async handleDeleteExpense() {
            this.loading = true;
            let res = await this.deleteExpense(this.deletee.id);
            if (res.error){
             console.log(res.error);
             this.enableSnackbar('failed','An error ocured when deleting Expense')
            } 
            else {
                this.enableSnackbar('success','Expense deleted successfully')
            }
            this.resetDelete();
            this.loading = false;
        },
        async handleAddExpense(){
            console.log('adding',user);
            this.loading = true;
            // this.create.loading = true;
            let res = await this.addExpense(expense);
            if (res.error){
             console.log(res.error);
             this.enableSnackbar('failed','An error ocured when creating user')
            } 
            else {
                this.enableSnackbar('success','User created successfully')
            }
            this.resetCreate();
            this.loading = false;
        }
    },
    created() {
        this.onfetchExpense();
        // this.CompanyList()
   
    }
};
</script>

<style></style>
