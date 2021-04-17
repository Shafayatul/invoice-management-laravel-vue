<template>
  <div class="ml-4">
    <v-form
      lazy-validation
      v-model="isValid"
      ref="IncomeForm"
      @submit.prevent="onSubmit"
    >
      <v-row>
        <v-col cols="12" md="12" lg="12">
          <v-select
            v-model="expenses.category_id"
            label="Name"
            v-bind="fieldOptions"
          />
        </v-col>
        <v-col cols="12" md="12" lg="12">
          <v-text-field
            v-model="expenses.client_id"
            v-bind="fieldOptions"
            label="Email"
          ></v-text-field>
        </v-col>
        <v-col cols="12" md="12" lg="12">
          <v-text-field
            v-model="expenses.invoice_id"
            v-bind="fieldOptions"
            label="Password"
          ></v-text-field>
        </v-col>
        <v-col cols="12" md="12" lg="12">
          <v-text-field
            v-model="expenses.name"
            v-bind="fieldOptions"
            label="Company"
          ></v-text-field>
        </v-col>
      </v-row>
    </v-form>
  </div>
</template>

<script>

import formFieldMixin from "@/mixins/formFieldMixin";
export default {
    name: "expensesForm",
    mixins: [formFieldMixin],
    props:{
        isUpdate:Boolean,
        data:Object
    },

    data() {
        return {
            isValid: false,
            expenses:{
               category_id: '',
               client_id:'',
               invoice_id:'',
               name: '',
               expenseDate:'',
               expenseType:'',
               expenseAmount:'',
            }
        };
    },
    watch: {
        data:{
      deep: true,
      immediate: true,
      handler(v) {
        if (!this.isUpdate) return;
        this.expenses = {
          name: v.name,
          address: v.address,
        };
      },
    }
    },
    
};
</script>

