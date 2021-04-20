<template>
  <div class="ml-3">
    <v-dialog
      v-model="cmDialog"
      @input="onInputInvoiceDialog"
      :width="this.$vuetify.breakpoint.mdAndUp ? '30vw' : '80vw'"
    >
      <v-card>
        <v-card-text>
          <!-- <v-card-title> Create Income </v-card-title> -->
          <IncomeForm :isUpdate="update.dialog" :data="update.data" />
        </v-card-text>
      </v-card>
    </v-dialog>
    <v-card>
      <v-card-title>
        Income
        <v-spacer></v-spacer>
        <v-text-field
          v-model="search"
          outlined
          dense
          prepend-inner-icon="mdi-magnify"
          label="Search"
          single-line
          hide-details
        ></v-text-field>
      </v-card-title>
      <v-data-table
        :loading="cardloader"
        loading-text="Loading... Please wait"
        :headers="headers"
        :items="desserts"
        :search="search"
      >
        <template v-slot:item.actions="{ item }">
          <v-menu down left nudge-left="7rem">
            <template v-slot:activator="{ on, attrs }">
              <v-btn icon color="primary" dark v-bind="attrs" v-on="on">
                <v-icon> mdi-dots-vertical </v-icon>
              </v-btn>
            </template>

            <v-list>
              <v-list-item @click="initUpdate(item)" dense link>
                <v-icon class="mr-2">mdi-pencil</v-icon>
                Edit
              </v-list-item>
              <v-divider></v-divider>
              <v-list-item dense link>
                <v-icon class="mr-2">mdi-delete</v-icon>
                Delete
              </v-list-item>
            </v-list>
          </v-menu>
        </template>
      </v-data-table>
    </v-card>
    <fabCreateButton @click="initCreate()" />
  </div>
</template>

<script>
import IncomeForm from "@/components/forms/IncomeForm.vue";
import fabCreateButton from "@/components/button/fabCreateButton";
import crudMixin from "@/mixins/crud-mixin";
export default {
    mixins: [crudMixin],
    components: {
        IncomeForm,
        fabCreateButton
    },
    data() {
        return {
            cardloader: false,
            search: "",
            headers: [
                {
                    text: "Category",
                    align: "start",
                    sortable: false,
                    value: "category_id"
                },
                {
                    text: "Client",
                    sortable: false,
                    value: "client_id"
                },
                { text: "Created By", value: "created_by", sortable: false },
                { text: "Bills File", value: "bills_file", sortable: false },
                {
                    text: "Expense Date",
                    value: "expense_date",
                    sortable: false
                },
                {
                    text: "Expense Type",
                    value: "expense_type",
                    sortable: false
                },
                {
                    text: "Expense Amount",
                    value: "expense_amount",
                    sortable: false
                },
                {
                    text: "Actions",
                    value: "actions",
                    sortable: false
                }
            ],
            desserts: [
                {
                    name: "Lorem Ipsum",
                    company: "lorem ipsum dolor sit",
                    expense_date: "12-12-12",
                    bills_file: "",
                    expense_type: "Cash",
                    expense_amount: "1232212"
                }
            ]
        };
    },
    methods: {
        click() {
            this.dialog = true;
        },
        onInputInvoiceDialog() {
            if (!dialog) {
                this.resetUpdate();
                this.resetCreate();
            }
        }
    }
};
</script>

<style></style>
