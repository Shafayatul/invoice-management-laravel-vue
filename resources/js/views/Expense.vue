<template>
  <div class="ml-3">
    <v-dialog
      fullscreen
      hide-overlay
      transition="dialog-bottom-transition"
      v-model="cmDialog"
    >
      <v-card>
        <v-toolbar dark color="primary">
          <v-btn icon dark @click="cmDialog = false">
            <v-icon>mdi-close</v-icon>
          </v-btn>
          <v-toolbar-title>Expense</v-toolbar-title>
          <v-spacer></v-spacer>
          <v-toolbar-items>
            <v-btn dark text @click="dialog = false"> Save </v-btn>
          </v-toolbar-items>
        </v-toolbar>
        <v-card-text>
          <!-- <v-card-title> Create Expense </v-card-title> -->
          <ExpenseForm
            v-if="cmDialog"
            :isUpdate="update.dialog"
            :data="update.data"
          />
        </v-card-text>
        <!-- <v-card-actions>
          <v-btn class="ml-6 mb-2" color="primary" outlined> ADD </v-btn>
        </v-card-actions> -->
      </v-card>
    </v-dialog>
    <v-card>
      <v-card-title>
        Company
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
import ExpenseForm from "@/components/forms/ExpenseForm.vue";
import fabCreateButton from "@/components/button/fabCreateButton";
import crudMixin from "@/mixins/crud-mixin";
export default {
    mixins: [crudMixin],
    name:'Expenses',
    components: {
        ExpenseForm,
        fabCreateButton
    },
    data() {
        return {
            cardloader: false,
            search: "",
            headers: [
                {
                    text: "User",
                    align: "start",
                    sortable: false,
                    value: "user"
                },
                { text: "Category", value: "category", sortable: false },
                { text: "Company", value: "company", sortable: false },
                { text: "Bills File", value: "bills_file", sortable: false },
                { text: "Date", value: "date", sortable: false },
                {
                    text: "Expense Type",
                    value: "expense_type",
                    sortable: false
                },
                { text: "Amount", value: "amount", sortable: false }
            ],
            desserts: [
                {
                    user: "Lorem Ipsum",
                    category: "lorem ipsum dolor sit",
                    company: "",
                    bills_file: "",
                    date: "",
                    expense_type: ""
                }
            ]
        };
    },
    methods: {
        click() {
            this.dialog = true;
        }
    }
};
</script>

<style></style>
