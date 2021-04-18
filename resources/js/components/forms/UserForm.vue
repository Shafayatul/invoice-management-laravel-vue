<template>
  <div class="ml-4">
    <v-form
      lazy-validation
      v-model="isValid"
      ref="userForm"
      @submit.prevent="onSubmit"
    >
      <v-row>
        <v-col cols="12" md="12" lg="12">
          <v-text-field
            v-model="user.name"
            label="Name"
            v-bind="fieldOptions"
          />
        </v-col>
        <v-col cols="12" md="12" lg="12">
          <v-text-field
            v-model="user.email"
            v-bind="fieldOptions"
            label="Email"
          ></v-text-field>
        </v-col>
        <v-col cols="12" md="12" lg="12">
          <v-text-field
            v-model="user.password"
            v-bind="fieldOptions"
            label="Password"
          ></v-text-field>
        </v-col>
        <v-col cols="12" md="12" lg="12">
          <v-select
            v-model="user.company_id"
            v-bind="fieldOptions"
            label="Company"
          ></v-select>
        </v-col>
        <v-col cols="12" md="12" lg="12">
          <v-select
            v-model="user.role"
            v-bind="fieldOptions"
            label="Role"
          ></v-select>
        </v-col>
      </v-row>
    </v-form>
  </div>
</template>

<script>

import formFieldMixin from "@/mixins/formFieldMixin";
export default {
    name: "userForm",
    mixins: [formFieldMixin],
    props:{
        isUpdate:Boolean,
        data:Object
    },

    data() {
        return {
            isValid: false,
            user:{
               name: '',
               email:'',
               password:'',
               company_id:'',
               role:''
            }
        };
    },
    watch: {
        data:{
      deep: true,
      immediate: true,
      handler(v) {
        if (!this.isUpdate) return;
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
    
};
</script>

