<template>
  <v-card class="mt-2">
    <v-card-title
      ><span class="mx-auto"
        >{{ isUpdate ? "Edit Income" : "Create Income" }}
      </span></v-card-title
    >
    <v-card-text>
      <v-form
        lazy-validation
        v-model="isValid"
        ref="IncomeForm"
        @submit.prevent="onSubmit"
      >
        <v-card
          class="mb-2 pa-2 pt-6"
          v-for="(incom, index) in income"
          :key="index"
          outlined
        >
          <v-btn
            class="fab-btn-right ma-1"
            @click="income.splice(index, 1)"
            icon
          >
            <v-icon color="red">mdi-close</v-icon>
          </v-btn>
          <v-card-text>
            <v-row>
              <v-col cols="12" sm="6" md="4">
                <v-select
                  v-model="incom.category_id"
                  :rules="[rules.required('Category')]"
                  :items="paymentList"
                  item-text="name"
                  item-value="id"
                  label="Category"
                  v-bind="fieldOptions"
                />
              </v-col>
              <v-col cols="12" sm="6" md="4">
                <v-select
                  v-model="incom.client_id"
                  :rules="[rules.required('Client')]"
                  :items="clientList"
                  item-text="name"
                  item-value="id"
                  label="Client"
                  v-bind="fieldOptions"
                />
              </v-col>
              <v-col cols="12" sm="6" md="4">
                <v-text-field
                  v-model="incom.income_amount"
                  :rules="[rules.required('Amount')]"
                  v-bind="fieldOptions"
                  label="Amount"
                ></v-text-field>
              </v-col>
            </v-row>
          </v-card-text>
        </v-card>
      </v-form>
    </v-card-text>
    <v-card-actions class="mb-1 justify-end">
      <v-btn @click="handleIncome" color="success">Confirm</v-btn>
      <v-btn class="mr-2" @click="onAddMoreIncome" color="primary">
        <v-icon class="mr-1">mdi-plus-box</v-icon> ADD More</v-btn
      >
    </v-card-actions>
  </v-card>
</template>

<script>
import formFieldMixin from "@/mixins/formFieldMixin";
import { createFormMixin } from "@/mixins/form-mixin";
const newIncomeItem = () => ({
    income_amount:null,
    client_id: null,
    category_id: null,
});
// expense_id:'',
export default {
    name: "incomeForm",
    mixins: [formFieldMixin,createFormMixin({
      rules: ["required"],
    })],
    props: {
        isUpdate: Boolean,
        data: Object,
        paymentList: Array,
        clientList:Array,
    },
    mounted() {
        console.log(this.$refs);
    },

    data() {
        return {
            isValid: false,
            income: [newIncomeItem()],
            date: null,
            menu: false,
            // ExpensetList: [
            //     {
            //         name: "One time",
            //         id: "one_time"
            //     },
            //     {
            //         name: "Recurring",
            //         id: "recurring"
            //     }
            // ]
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
                this.income[0].income_amount=v.incomeAmount,
                this.income[0].category_id=v.categoryId,
                this.income[0].client_id=v.clientId
            }
        }
    },
    created() {},
    methods: {
        onAddMoreIncome() {
            this.income.push(newIncomeItem());
        },
        fileInput(event, id) {
            console.log(event, id, "sdda");
            console.log(this.income[id].bill_file, "sdsadsadsa");
            this.income[id].bill_file = event;
        },
        handleIncome() {
            if (this.$refs.IncomeForm.validate()) {
                console.log("handleInvoice");
                // let addExpense = this.income.reduce(
                //     (acc, val) => {
                //         Object.entries(val).forEach(([key, value]) => {
                //             if (acc[key]) acc[key].push(value);
                //         });
                //         return acc;
                //     },
                //     { expense_amount: [], bill_file: [], expense_date: [], category_id: [] }
                // );
                let data = {}
              //  let addExpense=  this.income.map((y,index)=> Object.entries(y).reduce((acc,[key, value]) => ({...acc,[`${key}[${index}]`]:value}),{}))
              this.income.forEach((expense, index) => {
                Object.entries(expense).forEach(([key, value])=>{
                  if(key !== 'menu'){
                     data[`${key}[${index}]`] = value
                  }
                 
                })
              })
              let editData={}
              this.income.forEach((expense) => {
                Object.entries(expense).forEach(([key, value])=>{
                  if(key !== 'menu'){
                     editData[`${key}[${this.data.id}]`] = value
                  }
                 
                })
              })

              console.log(data);
              
                this.isUpdate
                    ? this.$emit("editIncome", editData)
                    : this.$emit("addIncome", data);
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
