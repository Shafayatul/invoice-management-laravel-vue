<template>
  <div>
    <v-card-title>
      <span class="mx-auto">{{ isUpdate ? "Edit User" : "Create User" }} </span>
    </v-card-title>
    <v-form
      lazy-validation
      v-model="isValid"
      ref="userForm"
      @submit.prevent="handleUser"
    >
      <v-row>
        <v-col cols="12" md="12" lg="12">
          <v-text-field
            v-model="user.name"
            label="Name"
            :rules="[rules.required('Name')]"
            v-bind="fieldOptions"
          />
        </v-col>
        <v-col cols="12" md="12" lg="12">
          <v-text-field
            v-model="user.email"
            :rules="[rules.required('Email'), rules.email]"
            v-bind="fieldOptions"
            label="Email"
          ></v-text-field>
        </v-col>
        <v-col cols="12" md="12" lg="12">
          <v-text-field
            v-model="user.password"
            :rules="[rules.required('Password'), rules.password]"
            v-bind="fieldOptions"
            label="Password"
          ></v-text-field>
        </v-col>
        <v-col cols="12" md="12" lg="12">
          <v-text-field
            v-model="user.company_id"
            :rules="[rules.required('Company')]"
            v-bind="fieldOptions"
            label="Company"
          ></v-text-field>
        </v-col>
        <v-col cols="12" md="12" lg="12">
          <v-select
            v-model="user.role"
            :rules="[rules.required('Role')]"
            v-bind="fieldOptions"
            label="Role"
          ></v-select>
        </v-col>
      </v-row>
    </v-form>
    <v-btn block @click="handleUser" class="my-3" color="primary"> ADD </v-btn>
  </div>
</template>

<script>

import formFieldMixin from "@/mixins/formFieldMixin";
import { createFormMixin } from "@/mixins/form-mixin";
const initialUser = () => ({
               name: '',
               email:'',
               phone:'',
               company_id:'',
               role:''
})
export default {
    name: "userForm",
    mixins: [formFieldMixin, createFormMixin({
      rules: ["required", "email", "password"],
    }),],
    props:{
        isUpdate:Boolean,
        data:Object
    },

    data() {
        return {
            isValid: false,
            user:{}
        };
    },
    watch: {
        data:{
      deep: true,
      immediate: true,
      handler(v) {
        console.log(v);
        if (!this.isUpdate) this.user = initialUser();
        this.user = {
          name: v.name,
          email: v.email,
          password:v.password,
          company_id:v.company_id,
          role:v.role
        };
      },
    }
    },
    methods:{
      handleUser() {
      this.$refs.userForm.validate();
      if (!this.isValid) return;
      // this.$emit("handleUser", this.user);
    },
    }
    
};
</script>

