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
          
            v-bind="fieldOptions"
          />
            <!-- :rules="[rules.required('client')]" -->
        </v-col>
        <v-col cols="12" md="12" lg="12">
          <v-select
            :items="companyList"
            item-text="name"
            item-value="id"
            v-model="invoiceDetails.company_id"
            :rules="[rules.required('company')]"
            :error-messages="errors.company_id"
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
            :items="paymentList"
            item-value="id"
            item-text="name"
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
          <v-text-field
            type="number"
            v-model="invoiceDetails.recurring_period"
            
            v-bind="fieldOptions"
            label="Recurring Period"
            :error-messages="errors.recurringPeriod && errors.recurringPeriod[0]"
          ></v-text-field>
        </v-col>
        <v-col cols="12" md="12" lg="12">
          <v-text-field
            v-model="invoiceDetails.amount"
            :rules="[rules.required('Amount')]"
            v-bind="fieldOptions"
            label="Amount"
          ></v-text-field>
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
    name: "invoiceForm",
    mixins: [formFieldMixin,createFormMixin({
      rules: ["required"],
    })],
    props:{
        isUpdate:Boolean,
        data:Object,
        companyList: Array,
        errors: Object,
    },

    data() {
        return {
            isValid: false,
            menu:false,
              paymentList: [
                {
                    name: "One time",
                    id: "one_time"
                },
                {
                    name: "Recurring",
                    id: "recurring"
                }
            ],
            invoiceDetails:{
               client_id: 4,
               company_id:'',
               sending_type:'',
               sending_type:'',
               recurring_period:null,
               amount:'',
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
          client: v.clientID,
          company_id: v.companyId,
          sending_type:v.sending_type,
          sending_type:v.sendingType,
          recurring_period:v.recurringPeriod,
          invoice_id:v.id

   
        };
      },
    }
    },
    methods:{
      handleInvoice(){
        if (this.$refs.InvoiceForm.validate()){
          console.log('handleInvoice');
           this.isUpdate? this.$emit("editInvoice", this.invoiceDetails) : this.$emit("addInvoice", this.invoiceDetails)
        }
      }
    }
    
};
</script>

