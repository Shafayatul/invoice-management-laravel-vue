<template>
  <div class="ml-4">
    <v-form
      lazy-validation
      v-model="isValid"
      ref="InvoiceForm"
      @submit.prevent="onSubmit"
    > 
      <v-row>
        <v-col cols="12" md="12" lg="12">
          <v-select
            v-model="invoiceDetails.client"
            label="Name"
            v-bind="fieldOptions"
          />
        </v-col>
        <v-col cols="12" md="12" lg="12">
          <v-select
            v-model="invoiceDetails.company_id"
            v-bind="fieldOptions"
            label="Company"
          ></v-select>
        </v-col>
        <v-col cols="12" md="12" lg="12">
          <v-select
            v-model="invoiceDetails.sending_type"
            v-bind="fieldOptions"
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
    
};
</script>

