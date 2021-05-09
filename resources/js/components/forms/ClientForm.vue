<template>
  <div>
    <v-card-title>
      <span class="mx-auto"
        >{{ isUpdate ? "Edit Client" : "Create Client" }}
      </span>
    </v-card-title>
    <v-form
      lazy-validation
      v-model="isValid"
      ref="ClientForm"
      @submit.prevent="handleClient"
    >
      <v-row>
        <v-col v-if="!reAssign" cols="12" md="12" lg="12">
          <v-text-field
            v-model="Client.name"
            label="Name"
            :rules="[rules.required('Name')]"
            :error-messages="errors.name && errors.name[0]"
            v-bind="fieldOptions"
          />
        </v-col>
        <!-- <v-col v-if="!reAssign" cols="12" md="12" lg="12">
          <v-text-field
             disabled
            v-model="Client.role"
            :rules="[rules.required('Role')]"
            :error-messages="errors.role && errors.role[0]"
            v-bind="fieldOptions"
            label="Role"
          ></v-text-field>
        </v-col> -->
        <v-col v-if="!reAssign" cols="12" md="12" lg="12">
          <v-text-field
            v-model="Client.email"
            :rules="[rules.required('Email'), rules.email]"
             :error-messages="errors.email && errors.email[0]"
            v-bind="fieldOptions"
            label="Email"
          ></v-text-field>
        </v-col>
        <v-col v-if="!reAssign" cols="12" md="12" lg="12">
          <v-text-field
            v-model="Client.phone"
            :rules="[rules.required('phone')]"
            :error-messages="errors.phone && errors.phone[0]"
            v-bind="fieldOptions"
            label="Phone"
            hide-details="auto"
          ></v-text-field>
        </v-col>
        <!-- <v-col v-if="!isClient && !reAssign" cols="12" md="12" lg="12">
          <v-text-field
            label="Password"
            v-bind="fieldOptions"
            v-model="Client.password"
            :rules="[rules.required('Password')]"
            prepend-inner-icon="mdi-lock"
            :type="password.show ? 'text' : 'password'"
            :append-icon="password.show ? 'mdi-eye' : 'mdi-eye-off'"
            @click:append="password.show = !password.show"
          ></v-text-field>
        </v-col>
        <v-col v-if="!isClient && !reAssign" cols="12" md="12" lg="12">
          <v-text-field
            :type="password.show ? 'text' : 'password'"
            :append-icon="password.show ? 'mdi-eye' : 'mdi-eye-off'"
            prepend-inner-icon="mdi-lock"
            v-bind="fieldOptions"
            @click:append="password.show = !password.show"
            v-model="Client.password_confirmation"
            :rules="[
              (v) => password_confirmation(v, Client.password),
              rules.required('Confirm Password'),
            ]"
            label="Confirm Password"
          />
        </v-col> -->
        <!-- <v-col cols="12" md="12" lg="12">
          <v-select
            :items="companyList"
            item-text="name"
            item-value="id"
            v-model="Client.company_id"
            :rules="[rules.required('Company')]"
            v-bind="fieldOptions"
            label="Company"
          ></v-select>
        </v-col> -->
      </v-row>
    </v-form>
    <v-btn block @click="handleClient" class="my-3" color="primary">
      {{ isUpdate ? "Confirm" : "Add" }}
    </v-btn>
    <!-- v-if="!this.reAssign" -->
    <!-- <v-btn v-else block @click="handleReassignClient" class="my-3" color="primary"> Re-Assign Company </v-btn> -->
  </div>
</template>

<script>
import formFieldMixin from "@/mixins/formFieldMixin";
import { createFormMixin } from "@/mixins/form-mixin";
const initialClient = () => ({
    name: "",
    email: "",
    phone: "",
    role: "client",
});
export default {
    name: "ClientForm",
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
        reAssign:Boolean,
        errors: Object,
    },

    data() {
        return {
            isValid: false,
            isClient:false,
            password: {
                show: false,
                confirmed: false
            },
            Client: {}
        };
    },
    watch: {
        data: {
            deep: true,
            immediate: true,
            handler(v) {
                if (!this.isUpdate) this.Client = initialClient();
                this.Client = {
                    name: v.name,
                    client_id:v.id,
                    email: v.email,
                    role: 'client',
                    phone:v.phone,
                };
            }
        }
    },
    methods: {
        handleClient() {
            if (this.$refs.ClientForm.validate()) {
                this.isUpdate  ? this.$emit("editClient", this.Client) : this.$emit("addClient", this.Client)
            }
        },
        CheckIsClient(item){
          item==='client'? (this.isClient=true,this.user.password='',this.user.password_confirmation='') : this.isClient=false
        },
     //    handleReassignClient(){
     //      if (this.$refs.userForm.validate()) {
     //            this.$emit("reAssignUser", this.user) 
  
     //        }
     //    }
    }
};
</script>
