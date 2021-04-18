<template>
  <div class="ml-4">
    <v-form
      lazy-validation
      v-model="isValid"
      ref="ExpenseForm"
      @submit.prevent="onSubmit"
    >
      <v-row>
        <v-col cols="12" md="12" lg="12">
          <v-text-field
            v-model="expenses.user_id"
            label="Name"
            v-bind="fieldOptions"
          />
        </v-col>
        <v-col cols="12" md="12" lg="12">
          <!-- <v-text-field
            v-model="expenses.email"
            v-bind="fieldOptions"
            label="Email"
          ></v-text-field> -->
        </v-col>
        <v-col cols="12" md="12" lg="12">
          <v-text-field
            v-model="expenses.type"
            v-bind="fieldOptions"
            label="Password"
          ></v-text-field>
        </v-col>
        <v-col cols="12" md="12" lg="12">
          <v-select
            v-model="expenses.amount"
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
               category_id: '',
               date:'',
               type:'',
               amount:'',
               billFile: '',
               role
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

