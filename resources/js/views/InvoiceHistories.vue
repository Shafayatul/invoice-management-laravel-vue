<template>
  <div>
    <v-card>
      <v-card-title> Invoice Histories </v-card-title>
      <v-data-table
        :loading="cardloader"
        loading-text="Loading... Please wait"
        :headers="headers"
        :items="$invoiceHistories"
        :search="search"
        hide-default-footer
        :items-per-page="+$paginationHistories.perPage"
      >
        <template v-slot:item.actions="{ item }">
          <v-menu down left nudge-left="7rem">
            <template v-slot:activator="{ on, attrs }">
              <v-btn icon color="primary" dark v-bind="attrs" v-on="on">
                <v-icon> mdi-dots-vertical </v-icon>
              </v-btn>
            </template>

            <v-list>
              <v-list-item @click="handleView(item)" dense link>
                <v-icon size="20" color="success" class="mr-3">mdi-eye</v-icon>
                View
              </v-list-item>
            </v-list>
          </v-menu>
        </template>
        <template v-slot:item.createdAt="{ item }">
          {{ $m(item.createdAt).format("ll") }}
        </template>
        <template v-slot:item.isPaid="{ item }">
          <v-chip small :color="item.isPaid === 1 ? 'success' : 'error'">
            {{ item.isPaid === 1 ? "Paid" : "Pending" }}</v-chip
          >
          <!-- {{item.isPaid}} -->
        </template>
      </v-data-table>
      <div class="text-center pt-2">
        <v-pagination
          :value="$paginationHistories.currentPage"
          @input="onChangePage"
          :length="
            Math.ceil(
              $paginationHistories.totalPage / $paginationHistories.perPage
            )
          "
        ></v-pagination>
      </div>
    </v-card>
    <CircleLoader center v-if="loading" size="84" speed="1" border-width="3" />
    <v-dialog
      :width="this.$vuetify.breakpoint.mdAndUp ? '30vw' : '80vw'"
      v-model="viewDetails"
    >
      <v-card v-if="viewClientInfo">
        <v-card-title><span class="mx-auto">View Details</span></v-card-title>
        <v-card-text>
          <v-form readonly>
            <v-row>
              <v-col cols="12">
                <v-text-field
                  v-bind="fieldOptions"
                  v-model="viewClientInfo.client.name"
                  label="Name"
                ></v-text-field>
              </v-col>
              <v-col cols="12">
                <v-text-field
                  v-bind="fieldOptions"
                  v-model="viewClientInfo.client.email"
                  label="Email"
                ></v-text-field>
              </v-col>
              <v-col cols="12">
                <v-text-field
                  v-bind="fieldOptions"
                  v-model="viewClientInfo.client.phone"
                  label="Phone number"
                ></v-text-field>
              </v-col>
              <v-col cols="12">
                <v-text-field
                  v-bind="fieldOptions"
                  v-model="viewClientInfo.invoice.companies.name"
                  label="Company name"
                ></v-text-field>
              </v-col>
              <v-col cols="12">
                <v-text-field
                  v-bind="fieldOptions"
                  v-model="viewClientInfo.createdAt"
                  label="Created at"
                ></v-text-field>
              </v-col>
              <v-col v-if="viewClientInfo.sendingType == 'recurring'" cols="12">
                <v-text-field
                  v-bind="fieldOptions"
                  v-model="viewClientInfo.lastMailingTime"
                  label="Last mailed time"
                ></v-text-field>
              </v-col>
              <v-col cols="12">
                <v-text-field
                  v-bind="fieldOptions"
                  v-model="viewClientInfo.mailingCount"
                  label="Total mails"
                ></v-text-field>
              </v-col>
              <v-col cols="12">
                <v-text-field
                  v-bind="fieldOptions"
                  v-model="viewClientInfo.amount"
                  label="Amount"
                ></v-text-field>
              </v-col>
              <v-col cols="12">
                <v-text-field
                  v-bind="fieldOptions"
                  v-model="viewClientInfo.isPaid"
                  label="Status"
                ></v-text-field>
              </v-col>
            </v-row>
          </v-form>
        </v-card-text>
      </v-card>
    </v-dialog>
  </div>
</template>

<script>
import moment from "moment";
import formFieldMixin from "@/mixins/formFieldMixin";
import CircleLoader from "@/components/customs/CircleLoader";
import { mapActions, mapGetters } from "vuex";
export default {
    name: "InvoiceHistories",
    components: {
        CircleLoader
    },
     mixins: [formFieldMixin],
    data() {
        return {
            cardloader: false,
            search: "",
            loading: false,
            viewDetails: false,
            viewClientInfo: {
                client: {
                    name: "",
                    email: "",
                    phone: ""
                },
                invoice: {
                    companies:{
                      name:'',
                      
                    }
                },
                invoiceHistory: {
                    amount: "",
                    isPaid: ""
                }
            },
            headers: [
                {
                    text: "Client",
                    align: "start",
                    sortable: false,
                    value: "client.name"
                },
                { text: "Email", value: "client.email", sortable: false },
                { text: "Phone", value: "client.phone", sortable: false },
                { text: "Created at", value: "createdAt", sortable: false },
                {
                  text: "Item",
                  value: "itemName",
                  sortable: false,
                },
                {
                  text: "Quantity",
                  value: "quantity",
                  sortable: false,
                },
                { text: "Amount", value: "amount", sortable: false },

                {
                    text: "Status",
                    value: "isPaid",
                    sortable: false
                },
                { text: "Actions", value: "actions", sortable: false }
            ]
        };
    },
    computed: {
        ...mapGetters("INVOICE", ["$invoiceHistories", "$paginationHistories"])
    },
    methods: {
        ...mapActions("INVOICE", ["fetchInvoiceHistories"]),
        async onChangePage(page) {
            this.tableLoader = true;
            await this.fetchInvoiceHistories({ page, per_page: 10 });
            this.tableLoader = false;
        },
        async invoiceHistories() {
            this.cardloader = true;
            this.cardloader = true;
            await this.fetchInvoiceHistories({ per_page: 10, page: 1 });
            this.cardloader = false;
            this.cardloader = false;
        },
        handleView(item) {
            this.viewDetails = true,
            this.viewClientInfo = item

            this.viewClientInfo.createdAt = moment(
                this.viewClientInfo.createdAt
            ).format("ll");
            this.viewClientInfo.updatedAt = moment(
                this.viewClientInfo.updatedAt
            ).format("ll");
            this.viewClientInfo.isPaid =
                this.viewClientInfo.isPaid == "1"
                    ? "Paid"
                    : "Pending";
           
           this.viewClientInfo.lastMailingTime = moment(
                this.viewClientInfo.lastMailingTime
            ).format("ll");
        }
    },

    created() {
        this.invoiceHistories();
    }
};
</script>
