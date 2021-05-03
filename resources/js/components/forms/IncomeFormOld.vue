<template>
    <div class="ml-4">
        <v-form
            lazy-validation
            v-model="isValid"
            ref="IncomeForm"
            @submit.prevent="onSubmit"
        >
            <v-row>
                <v-col cols="12" md="12" lg="12">
                    <v-select
                        v-model="expenses.category_id"
                        label="Category"
                        v-bind="fieldOptions"
                    />
                </v-col>
                <v-col cols="12" md="12" lg="12">
                    <v-text-field
                        v-model="expenses.client_id"
                        v-bind="fieldOptions"
                        label="Client"
                    ></v-text-field>
                </v-col>
                <v-col cols="12" md="12" lg="12">
                    <v-text-field
                        v-model="expenses.invoice_id"
                        v-bind="fieldOptions"
                        label="Invoice ID"
                    ></v-text-field>
                </v-col>
                <v-col cols="12" md="12" lg="12">
                    <v-text-field
                        v-model="expenses.company"
                        v-bind="fieldOptions"
                        label="Company"
                    ></v-text-field>
                </v-col>
                <v-col cols="12" md="12" lg="12">
                    <!-- <input @change="onFilePicked" ref="fileInput" type="file" style="display:none"> -->
                    <v-file-input
                        v-model="expenses.bills_file"
                        v-bind="fieldOptions"
                        label="Bills File"
                    ></v-file-input>
                    <!-- <v-btn @click="$refs.fileInput.click()">Click me</v-btn>
          <v-btn @click="$refs.fileInput[0].$el.click()">Click me</v-btn> -->
                </v-col>
                <v-col cols="12" md="12" lg="12">
                    <v-menu
                        ref="menu"
                        v-model="menu"
                        :close-on-content-click="false"
                        :return-value.sync="date"
                        transition="scale-transition"
                        offset-y
                        min-width="auto"
                    >
                        <template v-slot:activator="{ on, attrs }">
                            <v-text-field
                                v-model="date"
                                outlined 
                                label="Expense Date"
                                dense
                                prepend-innder-icon="mdi-calendar"
                                readonly
                                v-bind="attrs"
                                v-on="on"
                            ></v-text-field>
                        </template>
                        <v-date-picker v-model="date" no-title scrollable>
                            <v-spacer></v-spacer>
                            <v-btn text color="primary" @click="menu = false">
                                Cancel
                            </v-btn>
                            <v-btn
                                text
                                color="primary"
                                @click="$refs.menu.save(date)"
                            >
                                OK
                            </v-btn>
                        </v-date-picker>
                    </v-menu>
                </v-col>
            </v-row>
        </v-form>
    </div>
</template>

<script>
import formFieldMixin from "@/mixins/formFieldMixin";
export default {
    name: "expensesForm",
    mixins: [formFieldMixin],
    props: {
        isUpdate: Boolean,
        data: Object
    },

    data() {
        return {
            isValid: false,
            date: new Date().toISOString().substr(0, 10),
            menu: false,
            expenses: {
                category_id: "",
                client_id: "",
                invoice_id: "",
                company: "",
                expense_date: "",
                expense_type: "",
                expense_amount: ""
            }
        };
    },
    watch: {
        data: {
            deep: true,
            immediate: true,
            handler(v) {
                if (!this.isUpdate) return;
                this.expenses = {
                    company: v.company,
                    category_id: v.category_id,
                    client_id: v.client_id,
                    invoice_id: v.invoice_id,
                    client_id: v.client_id,
                    category_id: v.category_id,
                    client_id: v.client_id
                };
            }
        }
    },
    methods: {
        // onFilePicked(event) {
        //     // this.some
        // }
    }
};
</script>
