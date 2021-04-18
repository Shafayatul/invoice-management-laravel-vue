<template>
  <v-card class="mt-2">
    <v-card-title>Create Expense</v-card-title>
    <v-card-text>
      <v-card
        class="mb-2 pa-2 pt-6"
        v-for="(expense, index) in expenses"
        :key="index"
        outlined
      >
        <!-- <v-card-title>Create Expense</v-card-title> -->
        <v-btn
          class="fab-btn-right ma-1"
          @click="expenses.splice(index, 1)"
          icon
        >
          <v-icon color="red">mdi-close</v-icon>
        </v-btn>
        <v-card-text>
          <v-form
            lazy-validation
            v-model="isValid"
            ref="ExpenseForm"
            @submit.prevent="onSubmit"
          >
            <v-row>
              <!-- <v-col cols="12" sm="6" md="3">
                <v-select
                  v-model="expense.expense_id"
                  label="Expense"
                  v-bind="fieldOptions"
                />
              </v-col> -->
              <v-col cols="12" sm="6" md="2">
                <v-select
                  v-model="expense.category_id"
                  label="Category"
                  v-bind="fieldOptions"
                />
              </v-col>
              <v-col cols="12" sm="6" md="2">
                <v-text-field
                  v-model="expense.type"
                  label="Type"
                  v-bind="fieldOptions"
                />
              </v-col>
              <v-col cols="12" sm="6" md="2">
                <v-menu
                  :ref="'menu-' + index"
                  v-model="expense.menu"
                  :close-on-content-click="false"
                  :return-value.sync="expense.date"
                  transition="scale-transition"
                  offset-y
                  min-width="auto"
                >
                  <template v-slot:activator="{ on, attrs }">
                    <v-text-field
                      v-model="expense.date"
                      dense
                      outlined
                      hide-details="auto"
                      label="Picker in menu"
                      prepend-innder-icon="mdi-calendar"
                      readonly
                      v-bind="attrs"
                      v-on="on"
                    ></v-text-field>
                  </template>
                  <v-date-picker v-model="expense.date" no-title scrollable>
                    <v-spacer></v-spacer>
                    <v-btn text color="primary" @click="expense.menu = false">
                      Cancel
                    </v-btn>
                    <!-- $refs['menu-'+index][0].$el -->
                    <v-btn
                      text
                      color="primary"
                      @click="$refs['menu-' + index][0].save(expense.date)"
                    >
                      OK
                    </v-btn>
                  </v-date-picker>
                </v-menu>
              </v-col>
              <v-col cols="12" sm="6" md="3">
                <v-text-field
                  v-model="expenses.amount"
                  v-bind="fieldOptions"
                  label="Amount"
                ></v-text-field>
              </v-col>
              <v-col cols="12" sm="6" md="3">
                <v-file-input
                  v-model="expenses.billFile"
                  v-bind="fieldOptions"
                  label="Bills file"
                ></v-file-input>
              </v-col>
            </v-row>
          </v-form>
        </v-card-text>
      </v-card>
    </v-card-text>
    <v-card-actions  class="mb-1 justify-end">
      <v-btn class="mr-2" @click="onAddMoreExpense" color="primary">
        <v-icon class="mr-1">mdi-plus-box</v-icon> ADD More</v-btn
      >
    </v-card-actions>
  </v-card>
</template>

<script>
import formFieldMixin from "@/mixins/formFieldMixin";
const newExpenseItem = ()=> ({
                type: "",
                amount: "",
                billFile: "",
                category_id:'',
                role: "",
                date: new Date().toISOString().substr(0, 10),
                menu:false
            })
                // expense_id:'',
export default {
    name: "ExpensesForm",
    mixins: [formFieldMixin],
    props: {
        isUpdate: Boolean,
        data: Object
    },
    mounted(){
       console.log(this.$refs);
    },

    data() {
        return {
            isValid: false,
            expenses: [newExpenseItem()],
            date: new Date().toISOString().substr(0, 10),
            menu: false,
        };
    },
      watch: {
        data: {
            deep: true,
            immediate: true,
            handler(v) {
                if (!this.isUpdate) return;
                this.expenses = {
                    name: v.name,
                    address: v.address
                };
            }
        }
    },
    created(){
      
    },
    methods:{
      onAddMoreExpense(){
        this.expenses.push(newExpenseItem())
      }
    },
  
};
</script>

<style >
.fab-btn-right{
   position: absolute;
   top: 0;
   right: 0;
}

</style>