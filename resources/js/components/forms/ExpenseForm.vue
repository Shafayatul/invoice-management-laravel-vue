<template>
  <v-card class="mt-2">
    <v-card-title
      ><span class="mx-auto"
        >{{ isUpdate ? "Edit Expense" : "Create Expense" }}
      </span></v-card-title
    >
    <v-card-text>
      <v-form
        lazy-validation
        v-model="isValid"
        ref="ExpenseForm"
        @submit.prevent="onSubmit"
      >
        <v-card
          class="mb-2 pa-2 pt-6"
          v-for="(expense, index) in expenses"
          :key="index"
          outlined
        >
          <v-btn
            class="fab-btn-right ma-1"
            v-if="expenses.length > 1"
            @click="expenses.splice(index, 1)"
            icon
          >
            <v-icon color="red">mdi-close</v-icon>
          </v-btn>
          <v-card-text>
            <v-row>
              <v-col cols="12" sm="6" md="3">
                <v-select
                  v-model="expense.category_id"
                  :rules="[rules.required('Category')]"
                  :error-messages="errors.categoryId && errors.categoryId[0]"
                  :items="paymentList"
                  item-text="name"
                  item-value="id"
                  label="Category"
                  v-bind="fieldOptions"
                />
              </v-col>

              <v-col cols="12" sm="6" md="3">
                <v-menu
                  :ref="'menu-' + index"
                  v-model="expense.menu"
                  :close-on-content-click="false"
                  :return-value.sync="expense.expense_date"
                  transition="scale-transition"
                  offset-y
                  min-width="auto"
                >
                  <template v-slot:activator="{ on, attrs }">
                    <v-text-field
                      v-model="expense.expense_date"
                      :rules="[rules.required('Expense Date')]"
                      :error-messages="
                        errors.expenseDate && errors.expenseDate[0]
                      "
                      dense
                      outlined
                      hide-details="auto"
                      label="Expense Date"
                      prepend-innder-icon="mdi-calendar"
                      readonly
                      v-bind="attrs"
                      v-on="on"
                    ></v-text-field>
                  </template>
                  <v-date-picker
                    v-model="expense.expense_date"
                    no-title
                    scrollable
                  >
                    <v-spacer></v-spacer>
                    <v-btn text color="primary" @click="expense.menu = false">
                      Cancel
                    </v-btn>
                    <!-- $refs['menu-'+index][0].$el -->
                    <v-btn
                      text
                      color="primary"
                      @click="
                        $refs['menu-' + index][0].save(expense.expense_date)
                      "
                    >
                      OK
                    </v-btn>
                  </v-date-picker>
                </v-menu>
              </v-col>
              <v-col cols="12" sm="6" md="3">
                <v-text-field
                  type="number"
                  :min="0"
                  v-model="expense.expense_amount"
                  :rules="[rules.required('Amount'), rules.min(0)]"
                  :error-messages="
                    errors.expenseAmount && errors.expenseAmount[0]
                  "
                  v-bind="fieldOptions"
                  label="Amount"
                ></v-text-field>
              </v-col>
              <v-col cols="12" sm="6" md="3">
                <v-file-input
                  @change="fileInput($event, index)"
                  :error-messages="errors.billFile && errors.billFile[0]"
                  v-bind="fieldOptions"
                  label="Bills files"
                ></v-file-input>
              </v-col>
            </v-row>
          </v-card-text>
        </v-card>
      </v-form>
    </v-card-text>
    <v-card-actions class="mb-1 justify-end">
      <v-btn @click="handleExpense" color="success">Confirm</v-btn>
      <v-btn class="mr-2" @click="onAddMoreExpense" color="primary">
        <v-icon class="mr-1">mdi-plus-box</v-icon> ADD More</v-btn
      >
    </v-card-actions>
  </v-card>
</template>

<script>
import formFieldMixin from "@/mixins/formFieldMixin";
import { createFormMixin } from "@/mixins/form-mixin";
const newExpenseItem = () => ({
    expense_amount: null,
    bill_file: "",
    category_id: null,
    expense_date: new Date().toISOString().substr(0, 10),
    menu: false
});
// expense_id:'',
export default {
    name: "ExpensesForm",
  mixins: [formFieldMixin,createFormMixin({
      rules: ["min","required"],
    })],
    props: {
        isUpdate: Boolean,
        data: Object,
        paymentList: Array,
        errors:Object
    },

    data() {
        return {
            isValid: false,
            expenses: [newExpenseItem()],
            date: null,
            menu: false,
            ExpensetList: [
                {
                    name: "One time",
                    id: "one_time"
                },
                {
                    name: "Recurring",
                    id: "recurring"
                }
            ]
        };
    },
    watch: {
        data: {
            deep: true,
            immediate: true,
            handler(v) {
                if (!this.isUpdate) return;
                // let x={
                //     expense_amount: v.expenseAmount,
                //     category_id: v.categoryId,
                //     expense_date:new Date(v.expenseDate).toISOString().substr(0, 10),
                //     bill_file:v.billsFile
                // };
                this.expenses[0].expense_amount=v.expenseAmount,
                this.expenses[0].category_id=v.categoryId
                this.expenses[0].expense_date=new Date(v.expenseDate).toISOString().substr(0, 10),
                this.expenses[0].bill_file=v.billsFile
            }
        }
    },
    created() {},
    methods: {
        onAddMoreExpense() {
            this.expenses.push(newExpenseItem());
        },
        fileInput(event, id) {

            this.expenses[id].bill_file = event;
        },
        handleExpense() {
            if (this.$refs.ExpenseForm.validate()) {
                // let addExpense = this.expenses.reduce(
                //     (acc, val) => {
                //         Object.entries(val).forEach(([key, value]) => {
                //             if (acc[key]) acc[key].push(value);
                //         });
                //         return acc;
                //     },
                //     { expense_amount: [], bill_file: [], expense_date: [], category_id: [] }
                // );
                let data = {}
              //  let addExpense=  this.expenses.map((y,index)=> Object.entries(y).reduce((acc,[key, value]) => ({...acc,[`${key}[${index}]`]:value}),{}))
              this.expenses.forEach((expense, index) => {
                Object.entries(expense).forEach(([key, value])=>{
                  if(key !== 'menu'){
                     data[`${key}[${index}]`] = value
                  }
                 
                })
              })
              let editData={}
              this.expenses.forEach((expense,index) => {
                Object.entries(expense).forEach(([key, value])=>{
                  if(key === 'menu') return
                  if(index) editData[`${key}[]`] = value
                  else editData[`${key}[${this.data.id}]`] = value
                })
              })

              
                this.isUpdate
                    ? this.$emit("editExpense", editData)
                    : this.$emit("addExpense", data);
            }
        }
    }
};
</script>

<style>
.fab-btn-right {
    position: absolute;
    top: 0;
    right: 0;
}
</style>
