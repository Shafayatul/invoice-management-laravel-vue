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
        <v-col v-if="!isUpdate" cols="12" md="12" lg="12">
          <v-select
            v-model="invoiceDetails.client_id"
            :rules="[rules.required('Client')]"
            label="Client"
            :items="clientList"
            item-value="id"
            :error-messages="errors.clientId && errors.clientId[0]"
            item-text="name"
            v-bind="fieldOptions"
          />
          <!-- :rules="[rules.required('client')]" -->
        </v-col>
        <v-col cols="12" md="12" lg="12">
          <v-text-field
            v-model="invoiceDetails.item_name"
            :rules="[rules.required('Item Name')]"
            :error-messages="errors.itemName && errors.itemName[0]"
            v-bind="fieldOptions"
            label="Item Name"
          ></v-text-field>
        </v-col>
        <v-col cols="12" md="12" lg="12">
          <v-text-field
            type="number"
            v-model="invoiceDetails.quantity"
            :rules="[rules.required('Amount'), rules.min(1)]"
            :error-messages="errors.amount && errors.amount[0]"
            v-bind="fieldOptions"
            label="Quantity"
          ></v-text-field>
        </v-col>

        <v-col cols="12" md="12" lg="12">
          <v-text-field
            type="number"
            :min="0"
            v-model="invoiceDetails.amount"
            :rules="[rules.required('Amount'), rules.min(0)]"
            :error-messages="errors.expenseAmount && errors.expenseAmount[0]"
            v-bind="fieldOptions"
            label="Amount"
          ></v-text-field>
        </v-col>

        <v-col v-if="!isUpdate" cols="12" md="12" lg="12">
          <v-select
            v-model="invoiceDetails.sending_type"
            v-bind="fieldOptions"
            :rules="[rules.required('Sending Type')]"
            :error-messages="errors.sendingType && errors.sendingType[0]"
            label="Sending type"
            :items="paymentList"
            item-value="id"
            item-text="name"
          ></v-select>
        </v-col>
        <v-col v-if="!isUpdate" cols="12" md="12" lg="12">
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
                :error-messages="errors.sendingDate && errors.sendingDate[0]"
                dense
                label="Sending Date"
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
        <v-col
          v-if="invoiceDetails.sending_type === 'recurring'"
          cols="12"
          md="12"
          lg="12"
        >
          <v-text-field
            type="number"
            v-model="invoiceDetails.recurring_period"
            v-bind="fieldOptions"
            label="Recurring Period"
            :min="0"
            :rules="[rules.required('Recurring Period'), rules.min(0)]"
            :error-messages="
              errors.recurringPeriod && errors.recurringPeriod[0]
            "
          ></v-text-field>
        </v-col>
      </v-row>
    </v-form>

    <v-btn
      :loading="loading"
      block
      class="my-3"
      color="primary"
      @click="handleInvoice"
    >
      {{ isUpdate ? "Confirm" : "Add" }}
    </v-btn>
  </div>
</template>

<script>
import formFieldMixin from "@/mixins/formFieldMixin";
import { createFormMixin } from "@/mixins/form-mixin";
import moment from "moment";
export default {
  name: "invoiceForm",
  mixins: [
    formFieldMixin,
    createFormMixin({
      rules: ["min", "required"],
    }),
  ],
  props: {
    isUpdate: Boolean,
    data: Object,
    companyList: Array,
    clientList: Array,
    errors: Object,
    loading: Boolean,
  },

  data() {
    return {
      isValid: false,
      menu: false,
      paymentList: [
        {
          name: "One time",
          id: "one_time",
        },
        {
          name: "Recurring",
          id: "recurring",
        },
      ],
      invoiceDetails: {
        client_id: null,
        item_name: "",
        quantity: null,
        sending_date: new Date().toISOString().substr(0, 10),
        sending_type: "",
        recurring_period: 0,
        amount: "",
      },
    };
  },
  watch: {
    data: {
      deep: true,
      immediate: true,
      handler(v) {
        if (!this.isUpdate) return;
        this.invoiceDetails = {
          client_id: v.clientId,
          company_id: v.companyId,
          sending_type: v.sendingType,
          sending_date: new Date(v.sendingDate).toISOString().substr(0, 10),
          recurring_period: v.recurringPeriod,
          amount: v.invoiceHistory.amount,
          item_name: v.invoiceHistory.itemName,
          quantity: v.invoiceHistory.quantity,
          invoice_id: v.id,
        };
      },
    },
  },
  methods: {
    handleInvoice() {
      if (this.$refs.InvoiceForm.validate()) {
        this.isUpdate
          ? this.$emit("editInvoice", this.invoiceDetails)
          : this.$emit("addInvoice", this.invoiceDetails);
      }
    },
  },
};
</script>

