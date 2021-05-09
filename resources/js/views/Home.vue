<template>
  <div>
    <v-skeleton-loader
      v-bind="attrs"
      v-if="skeletonLoader"
      type="card-avatar, article, actions"
    ></v-skeleton-loader>
    <div v-else class="pb-2">
      <v-card class="px-4 mt-2">
        <v-row>
          <v-col cols="12" sm="6" md="6" lg="3">
            <v-card dark color="teal" outlined>
              <v-card-title
                ><span class="mx-auto py-2 font-weight-bold">
                  Client : {{ $dashboard.totalClients }}
                </span>
              </v-card-title>
            </v-card>
          </v-col>
          <v-col cols="12" sm="6" md="6" lg="3">
            <v-card dark color="teal" outlined>
              <v-card-title
                ><span class="mx-auto py-2 font-weight-bold">
                  Paid Invoice : {{ $dashboard.paidInvoices }}
                </span>
              </v-card-title>
            </v-card>
          </v-col>
          <v-col cols="12" sm="6" md="6" lg="3">
            <v-card dark color="teal" outlined>
              <v-card-title
                ><span class="mx-auto py-2 font-weight-bold">
                  Unpaid Invoice :
                  {{ $dashboard.unpaidInvoices }}
                </span>
              </v-card-title>
            </v-card>
          </v-col>
          <v-col cols="12" sm="6" md="6" lg="3">
            <v-card dark color="teal" outlined>
              <v-card-title
                ><span class="mx-auto py-2 font-weight-bold">
                  Profit Ratio : {{ $dashboard.profitRatio }} %
                </span>
              </v-card-title>
            </v-card>
          </v-col>
        </v-row>
      </v-card>
      <v-card class="mt-5">
        <div id="chart">
          <apexchart
            type="bar"
            class="px-2"
            height="350"
            v-if="$lastMonthPaidInvoiceValue"
            :options="invoiceChartOptions"
            :series="paidSeries"
          ></apexchart>
        </div>
      </v-card>
      <v-card class="mt-3">
        <div id="chart">
          <apexchart
            type="bar"
            class="px-2"
            height="350"
            v-if="$lastMonthPaidInvoiceValue"
            :options="expenseChartOptions"
            :series="incomeExpenseSeries"
          ></apexchart>
        </div>
      </v-card>

      <v-card class="px-4 my-3 pb-4">
        <v-card-title>
          Unpaid Invoice
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
          :loading="loading"
          loading-text="Loading... Please wait"
          :headers="headers"
          :items="$dashboard.unpaidInvoicesAll"
          :search="search"
          hide-default-footer
        >
          <template v-slot:item.sendingType="{ item }">
            {{
              item.sendingType.charAt(0).toUpperCase() +
              item.sendingType.slice(1).replace("_", " ")
            }}
          </template>
          <template v-slot:item.sendingDate="{ item }">
            {{ $m(item.sendingDate).format("ll") }}
          </template>
          <template v-slot:item.actions="{ item }">
            <v-menu down left nudge-left="7rem">
              <template v-slot:activator="{ on, attrs }">
                <v-btn icon color="primary" dark v-bind="attrs" v-on="on">
                  <v-icon> mdi-dots-vertical </v-icon>
                </v-btn>
              </template>

              <v-list>
                <v-list-item @click="handleViewClient(item)" dense link>
                  <v-icon class="mr-2">mdi-eye-circle</v-icon>
                  view client
                </v-list-item>
                <v-list-item @click="handleInvoiceHistory(item)" dense link>
                  <v-icon size="20" color="error" class="mr-3"
                    >mdi-history</v-icon
                  >
                  check history
                </v-list-item>
              </v-list>
            </v-menu>
          </template>
        </v-data-table>
        <v-dialog
          :width="this.$vuetify.breakpoint.mdAndUp ? '30vw' : '80vw'"
          v-model="viewClient"
        >
          <v-card>
            <v-card-title
              ><span class="mx-auto">Client Information</span></v-card-title
            >
            <v-card-text>
              <v-row>
                <v-col cols="12">
                  <v-text-field
                    readonly
                    v-bind="fieldOptions"
                    label="Name"
                    v-model="clientInfo.name"
                  ></v-text-field>
                </v-col>
                <v-col cols="12">
                  <v-text-field
                    readonly
                    v-bind="fieldOptions"
                    label="Email"
                    v-model="clientInfo.email"
                  ></v-text-field>
                </v-col>
                <v-col cols="12">
                  <v-text-field
                    readonly
                    v-bind="fieldOptions"
                    v-model="clientInfo.contact"
                    label="Phone"
                  ></v-text-field>
                </v-col>
                <v-col cols="12">
                  <v-text-field
                    readonly
                    v-bind="fieldOptions"
                    v-model="clientInfo.compnay"
                    label="Company"
                  ></v-text-field>
                </v-col>
                <v-col cols="12">
                  <v-text-field
                    readonly
                    v-bind="fieldOptions"
                    v-model="clientInfo.role"
                    label="Role"
                  ></v-text-field>
                </v-col>
              </v-row>
            </v-card-text>
          </v-card>
        </v-dialog>
        <v-dialog
          :width="this.$vuetify.breakpoint.mdAndUp ? '30vw' : '80vw'"
          v-model="historyDialog"
        >
          <v-card>
            <v-card-title
              ><span class="mx-auto">Invoice history</span></v-card-title
            >
            <v-card-text>
              <v-row>
                <v-col cols="12">
                  <v-text-field
                    readonly
                    v-bind="fieldOptions"
                    label="Name"
                    v-model="invoiceHistory.amount"
                  ></v-text-field>
                </v-col>
                <v-col cols="12">
                  <v-text-field
                    readonly
                    v-bind="fieldOptions"
                    label="Created at"
                    v-model="invoiceHistory.createdAt"
                  ></v-text-field>
                </v-col>
                <v-col cols="12">
                  <v-text-field
                    readonly
                    v-bind="fieldOptions"
                    v-model="invoiceHistory.lastMailTime"
                    label="Last mail time"
                  ></v-text-field>
                </v-col>
                <v-col cols="12">
                  <v-text-field
                    readonly
                    v-bind="fieldOptions"
                    v-model="invoiceHistory.mailingCount"
                    label="Total Mail"
                  ></v-text-field>
                </v-col>
                <v-col cols="12">
                  <v-text-field
                    readonly
                    v-bind="fieldOptions"
                    v-model="invoiceHistory.type"
                    label="Type"
                  ></v-text-field>
                </v-col>
              </v-row>
            </v-card-text>
          </v-card>
        </v-dialog>
      </v-card>
    </div>

    <CircleLoader center v-if="loading" size="84" speed="1" border-width="3" />
  </div>
</template>

<script>
// @ is an alias to /src
import moment from "moment";
import formFieldMixin from "@/mixins/formFieldMixin";
import apexchart from "vue-apexcharts";
import { mapActions, mapGetters } from "vuex";
import CircleLoader from "@/components/customs/CircleLoader";

export default {
    name: "Home",
    mixins: [formFieldMixin],
    components: {
        CircleLoader,
        apexchart
    },
    data() {
        return {
            loading: false,
            search: "",
            attrs: {
                class: "mb-6",
                boilerplate: true,
                elevation: 2
            },
            skeletonLoader: true,
            viewClient: false,
            clientInfo: {
                name: "",
                email: "",
                contact: "",
                role: "",
                company: ""
            },
            invoiceHistory: {
                amount: "",
                createdAt: null,
                lastMailTime: null,
                mailingCount: null,
                type: ""
            },
            historyDialog: false,
            headers: [
                {
                    text: "Client",
                    align: "start",
                    sortable: false,
                    value: "client.name"
                },
                { text: "Compnay", value: "companies.name", sortable: false },
                { text: "Type", value: "sendingType", sortable: false },
                { text: "Date", value: "sendingDate", sortable: false },
                {
                    text: "Recurring period",
                    value: "recurringPeriod",
                    sortable: false
                },
                {
                    text: "Created by",
                    value: "createdBy.name",
                    sortable: false
                },
                { text: "Actions", value: "actions", sortable: false }
            ],
            invoiceSeries: [
                {
                    name: "Paid Invoice",
                    data: []
                },
                {
                    name: "Unpaid",
                    data: []
                }
            ],
            expenseSeries: [
                {
                    name: "Income",
                    data: []
                },
                {
                    name: "Expense",
                    data: []
                }
            ],
            // colors: ["#77B6EA", "#FF5733"],

            invoiceChartOptions: {
                chart: {
                    type: "bar",
                    height: 350,
                    toolbar: {
                        show: false
                    }
                },
                plotOptions: {
                    bar: {
                        horizontal: false,
                        columnWidth: "55%",
                        endingShape: "rounded"
                    }
                },
                colors: ["#77B6EA", "#FF5733"],
                dataLabels: {
                    enabled: false
                },
                stroke: {
                    show: true,
                    width: 2,
                    colors: ["transparent"]
                },
                xaxis: {
                    categories: []
                },
                yaxis: {
                    title: {
                        text: "Last Month Invoice "
                    }
                },
                fill: {
                    opacity: 1
                },
                tooltip: {
                    // y: {
                    //     formatter: function(val) {
                    //         return "$ " + val + " thousands";
                    //     }
                    // }
                }
            },
            expenseChartOptions: {
                chart: {
                    type: "bar",
                    height: 350,
                     toolbar: {
                        show: false
                    }
                },
                plotOptions: {
                    bar: {
                        horizontal: false,
                        columnWidth: "55%",
                        endingShape: "rounded"
                    }
                },
                colors: ["#77B6EA", "#FF5733"],
                dataLabels: {
                    enabled: false
                },
                stroke: {
                    show: true,
                    width: 2,
                    colors: ["transparent"]
                },
                xaxis: {
                    categories: []
                },
                yaxis: {
                    title: {
                        text: "Last Month Income | Expense "
                    }
                },
                fill: {
                    opacity: 1
                },
                tooltip: {
                    // y: {
                    //     formatter: function(val) {
                    //         return "$ " + val + " thousands";
                    //     }
                    // }
                }
            }
        };
    },
    computed: {
        ...mapGetters("DASHBOARD", [
            "$dashboard",
            "$lastMonthPaidInvoiceValue",
            "$lastMonthUnPaidInvoiceValue",
            "$lastMonthIncome",
            "$lastMonthExpense",
            "$lastMonthDate"
        ]),
        paidSeries() {
            return this.invoiceSeries;
        },
        incomeExpenseSeries() {
            return this.expenseSeries;
        }
    },
    methods: {
        ...mapActions("DASHBOARD", ["fetchDashboardData"]),
        async onFetchcDashboard() {
            this.loading = true;
            await this.fetchDashboardData();
            this.loading = false;
            this.skeletonLoader = false;
            (this.invoiceSeries[0].data = this.$lastMonthPaidInvoiceValue.reverse()),
                (this.invoiceSeries[1].data = this.$lastMonthUnPaidInvoiceValue.reverse()),
                (this.invoiceChartOptions.xaxis.categories = this.$lastMonthDate),
                (this.expenseChartOptions.xaxis.categories = this.$lastMonthDate),
                (this.expenseSeries[0].data = this.$lastMonthIncome.reverse()),
                (this.expenseSeries[1].data = this.$lastMonthExpense.reverse());
        },
        handleViewClient(item) {
            this.viewClient = true;
            this.clientInfo.name = item.client.name;
            this.clientInfo.email = item.client.email;
            this.clientInfo.contact = item.client.phone;
            this.clientInfo.role = item.client.role;
            this.clientInfo.compnay = item.companies.name;
        },
        handleInvoiceHistory(item) {
            this.historyDialog = true;
            this.invoiceHistory.amount = item.invoiceHistory.amount;
            this.invoiceHistory.createdAt = moment(
                item.invoiceHistory.createdAt
            ).format("ll");
            this.invoiceHistory.lastMailTime = moment(
                item.invoiceHistory.lastMailingTime
            ).format("ll");
            this.invoiceHistory.mailingCount = item.invoiceHistory.mailingCount;
            this.invoiceHistory.type = item.sendingType.charAt(0).toUpperCase() + item.sendingType.slice(1).replace("_", " ");
        }
    },
    created() {
        this.onFetchcDashboard();
    }
};
</script>
