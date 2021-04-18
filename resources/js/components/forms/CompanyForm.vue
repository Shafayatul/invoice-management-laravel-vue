<template>
  <div class="ml-4">
    <v-form
      lazy-validation
      v-model="isValid"
      ref="employeeForm"
      @submit.prevent="onSubmit"
    >
      <v-row>
        <v-col cols="12" md="12" lg="12">
          <v-text-field
            v-model="company.name"
            label="Name"
            v-bind="fieldOptions"
          />
        </v-col>
        <v-col cols="12" md="12" lg="12">
          <v-textarea
            v-model="company.address"
            v-bind="fieldOptions"
            label="Address"
          ></v-textarea>
        </v-col>
      </v-row>
    </v-form>
  </div>
</template>

<script>

import formFieldMixin from "@/mixins/formFieldMixin";
export default {
    name: "CompanyForm",
    mixins: [formFieldMixin],
    props:{
        isUpdate:Boolean,
        data:Object
    },

    data() {
        return {
            isValid: false,
            company:{
               name: '',
               address: '',
            }
        };
    },
    watch: {
        data:{
      deep: true,
      immediate: true,
      handler(v) {
        if (!this.isUpdate) return;
        this.company = {
          name: v.name,
          address: v.address,
        };
      },
    }
    },
    
};
</script>

<style></style>
