<template>
  <div class="ml-4">
    <v-form
      lazy-validation
      v-model="isValid"
      ref="ExpenseDetailsForm"
      @submit.prevent="onSubmit"
    >
      <v-row>
        <v-col cols="12" md="12" lg="12">
          <v-select
            v-model="expenses.category_id"
            :rules="[rules.required('Category')]"
            label="Category"
            v-bind="fieldOptions"
          />
        </v-col>
        <v-col cols="12" md="12" lg="12">
          <v-text-field
            v-model="expenses.category_id"
            :rules="[rules.required('Email')]"
            v-bind="fieldOptions"
            label="Email"
          ></v-text-field>
        </v-col>
        <v-col cols="12" md="12" lg="12">
          <v-text-field
            v-model="expenses.company_id"
            :rules="[rules.required('Email')]"
            v-bind="fieldOptions"
            label="Password"
          ></v-text-field>
        </v-col>
        <v-col cols="12" md="12" lg="12">
          <v-select
            v-model="expenses.billFile"
            v-bind="fieldOptions"
            label="Company"
          ></v-select>
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
               user_id: '',
               category_id:'',
               company_id:'',
               billFile: '',
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

