<template>
  <div >
    <v-card-title>
     <span class="mx-auto"
        >{{ isUpdate ? "Edit Company" : "Create Company" }}
      </span>
        </v-card-title>
    <v-form
      lazy-validation
      v-model="isValid"
      ref="CompanyForm"
      @submit.prevent="handleCompany"
    >
      <v-row>
        <v-col cols="12" md="12" lg="12">
          <v-text-field
            v-model="company.name"
            :rules="[rules.required('Company Name')]"
            label="Company Name"
            :error-messages="errors.name && errors.name[0]"
            v-bind="fieldOptions"
          />
        </v-col>
        <v-col cols="12" md="12" lg="12">
          <v-textarea
            v-model="company.address"
            :rules="[rules.required('Address')]"
            :error-messages="errors.address && errors.address[0]"
            v-bind="fieldOptions"
            label="Address"
          ></v-textarea>
        </v-col>
      </v-row>
    </v-form>
    
      <v-btn block class="my-3" color="primary" @click="handleCompany"  >
        {{ isUpdate ? "Confirm" : "Add" }}
      </v-btn>
  </div>
</template>

<script>

import formFieldMixin from "@/mixins/formFieldMixin";
import { createFormMixin } from "@/mixins/form-mixin";
export default {
    name: "CompanyForm",
    mixins: [formFieldMixin,createFormMixin({
      rules: ["required"],
    })],
    props:{
        isUpdate:Boolean,
        data:Object,
        errors:Object
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
          company_id:v.id
        };
      },
    }
    },
    methods:{
      handleCompany(){
        if (this.$refs.CompanyForm.validate()) {
          this.isUpdate? this.$emit("editCompany", this.company) : this.$emit("addCompany", this.company)
        }
      }
    }
    
};
</script>

<style></style>
