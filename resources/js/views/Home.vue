<template>
    <div>
        <v-skeleton-loader
            v-bind="attrs"
            v-if="skeletonLoader"
            type="card-avatar, article, actions"
        ></v-skeleton-loader>
        <div v-else>
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
                                    Profit Ratio : {{ $dashboard.profitRatio }}
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

            <v-card class="px-4 mt-3">
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
                >
                    <!-- <template v-slot:item.actions="{ item }">
         
        </template> -->
                </v-data-table>
            </v-card>
        </div>

        <CircleLoader
            center
            v-if="loading"
            size="84"
            speed="1"
            border-width="3"
        />
    </div>
</template>

<script>
// @ is an alias to /src
import apexchart from "vue-apexcharts";
import { mapActions, mapGetters } from "vuex";
import CircleLoader from "@/components/customs/CircleLoader";

export default {
    name: "Home",
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
                { text: "Created by", value: "createdBy.name", sortable: false }
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

            invoiceChartOptions: {
                chart: {
                    type: "bar",
                    height: 350
                },
                plotOptions: {
                    bar: {
                        horizontal: false,
                        columnWidth: "55%",
                        endingShape: "rounded"
                    }
                },
                dataLabels: {
                    enabled: false
                },
                colors: ["#77B6EA", "#FF5733"],
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
                        text: "Last Month Invoice"
                    }
                },
                fill: {
                    opacity: 1
                },
                tooltip: {
                    y: {
                        formatter: function(val) {
                            return "$ " + val + " thousands";
                        }
                    }
                }
            },
             expenseChartOptions: {
                chart: {
                    type: "bar",
                    height: 350
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
                        text: "Last Month Invoice | Income "
                    }
                },
                fill: {
                    opacity: 1
                },
                tooltip: {
                    y: {
                        formatter: function(val) {
                            return "$ " + val + " thousands";
                        }
                    }
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
            return this.invoiceSeries
        },
        incomeExpenseSeries(){
            return this.expenseSeries
        }
    },
    methods: {
        ...mapActions("DASHBOARD", ["fetchDashboardData"]),
        async onFetchcDashboard() {
            this.loading = true;
            await this.fetchDashboardData();
            this.loading = false;
            this.skeletonLoader = false;
            this.invoiceSeries[0].data = this.$lastMonthPaidInvoiceValue,
            this.invoiceSeries[1].data = this.$lastMonthUnPaidInvoiceValue,
            this.invoiceChartOptions.xaxis.categories = this.$lastMonthDate;
            this.expenseChartOptions.xaxis.categories = this.$lastMonthDate;
            this.expenseSeries[0].data = this.$lastMonthIncome,
            this.expenseSeries[1].data = this.$lastMonthExpense
        }
    },
    created() {
        this.onFetchcDashboard();
    }
};
</script>
