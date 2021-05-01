<template>
  <div>
    <v-card-title>
      <span class="mx-auto"
        >{{ isUpdate ? "Edit Payment" : "Create Payment" }}
      </span>
    </v-card-title>
    <v-form
      lazy-validation
      v-model="isValid"
      ref="paymentForm"
      @submit.prevent="handlePayment"
    >
      <v-row>
        <v-col cols="12" md="12" lg="12">
          <v-text-field
            v-model="payment.name"
            :rules="[rules.required('Payemnt Name')]"
            label="Payemnt Name"
            v-bind="fieldOptions"
          />
        </v-col>
        <v-col cols="12" md="12" lg="12">
          <v-text-field
            v-model="payment.type"
            :rules="[rules.required('Payemnt Type')]"
            label="Payemnt Type"
            v-bind="fieldOptions"
          />
        </v-col>
        <v-col cols="12" md="12" lg="12">
          <v-textarea
            v-model="payment.details"
            :rules="[rules.required('Payemnt Details')]"
            v-bind="fieldOptions"
            label="details"
          ></v-textarea>
        </v-col>
      </v-row>
    </v-form>

    <v-btn block class="my-3" color="primary" @click="handlePayment">
      ADD
    </v-btn>
  </div>
</template>

<script>
import formFieldMixin from "@/mixins/formFieldMixin";
import { createFormMixin } from "@/mixins/form-mixin";
export default {
    name: "PaymentForm",
    mixins: [
        formFieldMixin,
        createFormMixin({
            rules: ["required"]
        })
    ],
    props: {
        isUpdate: Boolean,
        data: Object
    },

    data() {
        return {
            isValid: false,
            payment: {
                name: "",
                type: "",
                details: ""
            },
            paymentList: [
                {
                    name: "One time",
                    id: "one_time"
                },
                {
                    name: "Recurring",
                    id: "recurring"
                }
            ]
        };
    },
    watch: {
        data: {
            deep: true,
            immediate: true,
            handler(v) {
                if (!this.isUpdate) return;
                this.payment = {
                    name: v.name,
                    type: v.type,
                    details: v.details,
                    id:v.id
                };
            }
        }
    },
    methods: {
        handlePayment() {
          if (this.$refs.paymentForm.validate()) {
            this.isUpdate
                ? this.$emit("editPayment", this.payment)
                : this.$emit("addPayment", this.payment);
          }
        }
    }
};
</script>

<style></style>
