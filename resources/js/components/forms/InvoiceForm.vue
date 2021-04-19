<template>
  <div>
    <v-card-title>
      <span class="mx-auto"
        >{{ isUpdate ? "Edit Invoice" : "Create Invoice" }}
      </span>
    </v-card-title>
    <v-form
      lazy-validation
      v-model="isValid"
      ref="InvoiceForm"
      @submit.prevent="handleInvoice"
    >
      <v-row>
        <v-col cols="12" md="12" lg="12">
          <v-select
            v-model="invoiceDetails.client"
            label="Client"
            :rules="[rules.required('client')]"
            v-bind="fieldOptions"
          />
        </v-col>
        <v-col cols="12" md="12" lg="12">
          <v-select
            v-model="invoiceDetails.company_id"
            :rules="[rules.required('company')]"
            v-bind="fieldOptions"
            label="Company"
          ></v-select>
        </v-col>
        <v-col cols="12" md="12" lg="12">
          <v-select
            v-model="invoiceDetails.sending_type"
            v-bind="fieldOptions"
            :rules="[rules.required('Sending Type')]"
            label="Sending type"
          ></v-select>
        </v-col>
        <v-col cols="12" md="12" lg="12">
          <v-menu
            ref="menu"
            v-model="menu"
            :close-on-content-click="false"
            :return-value.sync="invoiceDetails.sending_date"
            transition="scale-transition"
            offset-y
            min-width="auto"
          >
            <template v-slot:activator="{ on, attrs }">
              <v-text-field
                v-model="invoiceDetails.sending_date"
                outlined
                hide-details="auto"
                :rules="[rules.required('Sending Date')]"
                dense
                label="Picker in menu"
                prepend-inner-icon="mdi-calendar"
                readonly
                v-bind="attrs"
                v-on="on"
              ></v-text-field>
            </template>
            <v-date-picker
              v-model="invoiceDetails.sending_date"
              no-title
              scrollable
            >
              <v-spacer></v-spacer>
              <v-btn text color="primary" @click="menu = false"> Cancel </v-btn>
              <v-btn
                text
                color="primary"
                @click="$refs.menu.save(invoiceDetails.sending_date)"
              >
                OK
              </v-btn>
            </v-date-picker>
          </v-menu>
        </v-col>
        <v-col cols="12" md="12" lg="12">
          <v-select
            v-model="invoiceDetails.recurring_period"
            :rules="[rules.required('Recurring Period')]"
            v-bind="fieldOptions"
            label="Recurring Period"
          ></v-select>
        </v-col>
      </v-row>
    </v-form>

      <v-btn block class="my-3" color="primary" @click="handleInvoice"  >
        ADD
      </v-btn>

  </div>
</template>

<script>

import formFieldMixin from "@/mixins/formFieldMixin";
import { createFormMixin } from "@/mixins/form-mixin";
export default {
    name: "userForm",
    mixins: [formFieldMixin,createFormMixin({
      rules: ["required"],
    })],
    props:{
        isUpdate:Boolean,
        data:Object
    },

    data() {
        return {
            isValid: false,
            menu:false,
            invoiceDetails:{
               client: '',
               company_id:'',
               sending_type:'',
               sending_type:'',
               recurring_period:'',
               created_by:''
            }
        };
    },
    watch: {
        data:{
      deep: true,
      immediate: true,
      handler(v) {
        if (!this.isUpdate) return;
        this.invoiceDetails = {
          client: v.client,
          company_id: v.company_id,
          sending_type:v.sending_type,
          sending_type:v.sending_type,
          recurring_period:v.recurring_period,
          created_by:v.created_by
        };
      },
    }
    },
    methods:{
      handleInvoice(){
        this.$refs.InvoiceForm.validate();
        if (!this.isValid) return;
      }
    }
    
};
</script>

