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
            v-if="income.length > 1 && index !==0"
            @click="income.splice(index, 1)"
            icon
          >
            <v-icon color="red">mdi-close</v-icon>
          </v-btn>
          <v-card-text>
            <v-row>
              <v-col cols="12" sm="6" md="3">
                <v-select
                  v-model="incom.category_id"
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
                <v-select
                  v-model="incom.client_id"
                  :rules="[rules.required('Client')]"
                  :error-messages="errors.clientId && errors.clientId[0]"
                  :items="clientList"
                  item-text="name"
                  item-value="id"
                  label="Client"
                  v-bind="fieldOptions"
                />
              </v-col>
              <v-col cols="12" sm="6" md="3">
                <v-text-field
                  :min="0"
                  v-model="incom.income_amount"
                  :rules="[rules.required('Amount'), rules.min(0)]"
                  :error-messages="
                    errors.incomeAmount && errors.incomeAmount[0]
                  "
                  v-bind="fieldOptions"
                  label="Amount"
                ></v-text-field>
              </v-col>
              <v-col cols="12" sm="6" md="3">
                <v-file-input
                  @change="fileInput($event, index)"
                  :error-messages="
                    errors[`receiptFile.${index}`]
                      ? errors[`receiptFile.${index}`][0]
                      : ''
                  "
                  v-bind="fieldOptions"
                  label="Bills files"
                  accept="image/png, image/jpeg,image/jpg,file/pdf"
                ></v-file-input>
                <div class="text-right"></div>
                <!-- <v-chip v-if="incom.receipt_file && isUpdate"  > <v-icon class="mr-1">mdi-cloud-download</v-icon><a download :href="incom.receipt_file" >download file</a> </v-chip>  -->
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
  income_amount: null,
  client_id: null,
  category_id: null
  // receipt_file:null
});
// expense_id:'',
export default {
  name: "incomeForm",
  mixins: [
    formFieldMixin,
    createFormMixin({
      rules: ["min", "required"]
    })
  ],
  props: {
    isUpdate: Boolean,
    data: Object,
    paymentList: Array,
    clientList: Array,
    errors: Object
  },

  data() {
    return {
      isValid: false,
      income: [newIncomeItem()],
      date: null,
      menu: false
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
        //     receipt_file:v.billsFile
        // };
        (this.income[0].income_amount = v.incomeAmount),
          (this.income[0].category_id = v.categoryId),
          (this.income[0].client_id = v.clientId);
      }
    }
  },
  created() {},
  methods: {
    onAddMoreIncome() {
      this.income.push(newIncomeItem());
    },
    fileInput(event, id) {
      this.income[id].receipt_file = event;
    },

    handleIncome() {
      if (this.$refs.IncomeForm.validate()) {
        if (this.isUpdate) {
          let editData = {};
          Object.entries(this.income[0]).forEach(([key, value]) => {
            if (key === "menu") return;
            else editData[`${key}[${this.data.id}]`] = value;
          });
          this.$emit("editIncome", editData);

          if (this.income.length === 1) return;
          let data = {};
          this.income.slice(1).forEach((expense, index) => {
            Object.entries(expense).forEach(([key, value]) => {
              if (
                key === "menu" ||
                (key === "receipt_file" && !value && !this.isUpdate)
              )
                return;
              data[`${key}[${index}]`] = value;
            });
          });
          this.$emit("addIncome", data);
        } else {
          let data = {};
          //  let addExpense=  this.income.map((y,index)=> Object.entries(y).reduce((acc,[key, value]) => ({...acc,[`${key}[${index}]`]:value}),{}))
          this.income.forEach((expense, index) => {
            Object.entries(expense).forEach(([key, value]) => {
              if (key === "menu" || (key === "receipt_file" && !value)) return;

              data[`${key}[${index}]`] = value;
            });
          });
          this.$emit("addIncome", data);
        }
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
