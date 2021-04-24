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
            >
                <!-- <template v-slot:item.Status="{ item }">
                    {{ "sdadsadas" }}
                </template> -->
                <template v-slot:item.isPaid="{ item }">
                    <v-chip
                        small
                        :color="
                            item.isPaid === '1' ? 'success' : 'error'
                        "
                    >
                        {{
                            item.isPaid === '1'
                                ? "Paid"
                                : "Pending"
                        }}</v-chip
                    >
                    <!-- {{item.isPaid}} -->
                </template>
            </v-data-table>
        </v-card>
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
import CircleLoader from "@/components/customs/CircleLoader";
import { mapActions, mapGetters } from "vuex";
export default {
    name: "InvoiceHistories",
    components: {
        CircleLoader
    },
    data() {
        return {
            cardloader: false,
            search: "",
            loading: false,
            headers: [
                {
                    text: "Client",
                    align: "start",
                    sortable: false,
                    value: "client.name"
                },
                { text: "Email", value: "client.email", sortable: false },
                { text: "Phone", value: "client.phone", sortable: false },
                { text: "Date", value: "createdAt", sortable: false },
                { text: "Amount", value: "amount", sortable: false },

                {
                    text: "Status",
                    value: "isPaid",
                    sortable: false
                }
            ]
        };
    },
    computed: {
        ...mapGetters("INVOICE", ["$invoiceHistories"])
    },
    methods: {
        ...mapActions("INVOICE", ["fetchInvoiceHistories"]),
        async invoiceHistories() {
            this.cardloader = true;
            this.cardloader = true;
            await this.fetchInvoiceHistories();
            this.cardloader = false;
            this.cardloader = false;
        }
    },

    created() {
        this.invoiceHistories();
    }
};
</script>
