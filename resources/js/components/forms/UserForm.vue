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
        <v-col v-if="!reAssign" cols="12" md="12" lg="12">
          <v-text-field
            v-model="user.name"
            label="Name"
            :rules="[rules.required('Name')]"
            v-bind="fieldOptions"
          />
        </v-col>
        <v-col v-if="!reAssign"  cols="12" md="12" lg="12">
          <v-select
            @change="CheckIsClient(user.role)"
            :items="['client', 'admin', 'employee']"
            v-model="user.role"
            :rules="[rules.required('Role')]"
            v-bind="fieldOptions"
            label="Role"
          ></v-select>
        </v-col>
        <v-col v-if="!reAssign"  cols="12" md="12" lg="12">
          <v-text-field
            v-model="user.email"
            :rules="[rules.required('Email'), rules.email]"
            v-bind="fieldOptions"
            label="Email"
          ></v-text-field>
        </v-col>
        <v-col v-if="!reAssign"  cols="12" md="12" lg="12">
          <v-text-field
            v-model="user.phone"
            :rules="[rules.required('phone')]"
            v-bind="fieldOptions"
            label="Phone"
            hide-details="auto"
          ></v-text-field>
        </v-col>
        <v-col  v-if="!isClient && !reAssign " cols="12" md="12" lg="12">
          <v-text-field
            label="Password"
            v-bind="fieldOptions"
            v-model="user.password"
            :rules=" isUpdate?[]:[rules.required('Password'),rules.password]"
            prepend-inner-icon="mdi-lock"
            :type="password.show ? 'text' : 'password'"
            :append-icon="password.show ? 'mdi-eye' : 'mdi-eye-off'"
            @click:append="password.show = !password.show"
          ></v-text-field>
        </v-col>
        <v-col  v-if="!isClient && !reAssign " cols="12" md="12" lg="12">
          <v-text-field
            :type="password.show ? 'text' : 'password'"
            :append-icon="password.show ? 'mdi-eye' : 'mdi-eye-off'"
            prepend-inner-icon="mdi-lock"
            v-bind="fieldOptions"
            @click:append="password.show = !password.show"
            v-model="user.password_confirmation"
            :rules=" isUpdate?[]:[
              (v) => password_confirmation(v, user.password),
              rules.required('Confirm Password'),
            ]"
            label="Confirm Password"
          />
        </v-col>
        <v-col cols="12" md="12" lg="12">
          <v-select
            :items="companyList"
            item-text="name"
            item-value="id"
            v-model="user.company_id"
            :rules="[rules.required('Company')]"
            v-bind="fieldOptions"
            label="Company"
          ></v-select>
        </v-col>
      </v-row>
    </v-form>
    <v-btn v-if="!this.reAssign" block @click="handleUser" class="my-3" color="primary"> ADD </v-btn>
    <v-btn v-else block @click="handleReassignUser" class="my-3" color="primary"> Re-Assign Company </v-btn>
  </div>
</template>

<script>
import formFieldMixin from "@/mixins/formFieldMixin";
import { createFormMixin } from "@/mixins/form-mixin";
const initialUser = () => ({
    name: "",
    email: "",
    phone: "",
    companyId: "",
    role: "",
    password: "",
    password_confirmation: ""
});
export default {
    name: "userForm",
    mixins: [
        formFieldMixin,
        createFormMixin({
            rules: ["required", "email", "password"]
        })
    ],
    props: {
        isUpdate: Boolean,
        data: Object,
        companyList: Array,
        reAssign:Boolean
    },

    data() {
        return {
            isValid: false,
            isClient:false,
            password: {
                show: false,
                confirmed: false
            },
            user: {}
        };
    },
    watch: {
        data: {
            deep: true,
            immediate: true,
            handler(v) {
                console.log(v);
                if (!this.isUpdate) this.user = initialUser();
                this.user = {
                    name: v.name,
                    user_id:v.id,
                    email: v.email,
                    password: '',
                    company_id: v.companyId,
                    role: v.role,
                    phone:v.phone,
                    password_confirmation:''
                };
            }
        }
    },
    methods: {
        handleUser() {
            if (this.$refs.userForm.validate()) {
                this.isUpdate  ? this.$emit("editUser", this.user) : this.$emit("addUser", this.user)
                console.log('handleUser');
            }
        },
        password_confirmation(cPass, pass) {
            return cPass === pass || "Password not matched";
        },
        CheckIsClient(item){
          item==='client'? (this.isClient=true,this.user.password='',this.user.password_confirmation='') : this.isClient=false
        },
        handleReassignUser(){
          if (this.$refs.userForm.validate()) {
                this.$emit("reAssignUser", this.user) 
  
            }
        }
    }
};
</script>
