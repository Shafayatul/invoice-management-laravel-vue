<template>
  <div>
    <v-card>
      <v-card-text>
        <div>
          <v-card outlined class="pa-2 tw-text-center">
            <v-card-title
              ><span class="mx-auto">Filter Invoice</span></v-card-title
            >
            <v-card-text>
              <v-row>
                <v-col cols="12" md="4">
                  <v-menu
                    ref="menu"
                    v-model="menu"
                    :close-on-content-click="false"
                    :return-value.sync="filterSearch.start_date"
                    transition="scale-transition"
                    offset-y
                    min-width="auto"
                  >
                    <template v-slot:activator="{ on, attrs }">
                      <v-text-field
                        v-model="filterSearch.start_date"
                        dense
                        outlined
                        hide-details="auto"
                        label="From"
                        prepend-inner-icon="mdi-calendar"
                        readonly
                        v-bind="attrs"
                        v-on="on"
                      ></v-text-field>
                    </template>
                    <v-date-picker
                      v-model="filterSearch.start_date"
                      no-title
                      scrollable
                    >
                      <v-spacer></v-spacer>
                      <v-btn text color="primary" @click="menu = false">
                        Cancel
                      </v-btn>
                      <v-btn
                        text
                        color="primary"
                        @click="$refs.menu.save(filterSearch.start_date)"
                      >
                        OK
                      </v-btn>
                    </v-date-picker>
                  </v-menu>
                </v-col>
                <v-col cols="12" md="4">
                  <v-menu
                    ref="menu1"
                    v-model="meunu1"
                    :close-on-content-click="false"
                    :return-value.sync="filterSearch.end_date"
                    transition="scale-transition"
                    offset-y
                    min-width="auto"
                  >
                    <template v-slot:activator="{ on, attrs }">
                      <v-text-field
                        v-model="filterSearch.end_date"
                        dense
                        outlined
                        hide-details="auto"
                        label="to"
                        prepend-inner-icon="mdi-calendar"
                        readonly
                        v-bind="attrs"
                        v-on="on"
                      ></v-text-field>
                    </template>
                    <v-date-picker
                      v-model="filterSearch.end_date"
                      no-title
                      scrollable
                    >
                      <v-spacer></v-spacer>
                      <v-btn text color="primary" @click="menu1 = false">
                        Cancel
                      </v-btn>
                      <v-btn
                        text
                        color="primary"
                        @click="$refs.menu1.save(filterSearch.end_date)"
                      >
                        OK
                      </v-btn>
                    </v-date-picker>
                  </v-menu>
                </v-col>

                <v-col cols="12" md="1">
                  <v-btn
                    dark
                    color="primary"
                    @click="onFilterSearch"
                    :block="$vuetify.breakpoint.smAndDown"
                    ><v-icon>mdi-search</v-icon>Search</v-btn
                  >
                </v-col>
              </v-row>
            </v-card-text>
          </v-card>
        </div>
      </v-card-text>
    </v-card>
    <v-card class="mt-2">
      <v-card-title>
        Summarized invoice
        <v-spacer></v-spacer>
        <v-btn
          @click="handleBulkDownload"
          v-if="$summarizedInvocie.length"
          small
          class="mr-1"
          color="success"
          >Bulk <v-icon>mdi-download</v-icon></v-btn
        >
        <v-btn
          @click="handleSummarizedDownload"
          v-if="$summarizedInvocie.length"
          small
          class="mr-1"
          color="primary"
        >
          Summarized <v-icon>mdi-download</v-icon>
        </v-btn>
        <v-text-field
          v-model="search"
          append-inner-icon="mdi-magnify"
          dense
          outlined
          label="Search"
          single-line
          hide-details
        ></v-text-field>
      </v-card-title>
      <v-data-table
        :headers="headers"
        :items="$summarizedInvocie"
        :search="search"
        hide-default-footer
      >
        <template v-slot:item.invoiceHistory.isPaid="{ item }">
          <v-chip
            small
            :color="item.invoiceHistory.isPaid == '1' ? 'success' : 'error'"
          >
            {{ item.invoiceHistory.isPaid == "1" ? "Paid" : "Pending" }}</v-chip
          >
        </template>
        <template v-slot:item.sendingDate="{ item }">
          {{ $m(item.sendingDate).format("ll") }}
        </template>
        <template v-slot:item.sendingType="{ item }">
          {{
            item.sendingType.charAt(0).toUpperCase() +
            item.sendingType.slice(1).replace("_", " ")
          }}
        </template>
        <template v-slot:item.actions="{ item }">
          <v-menu down left nudge-left="7rem">
            <template v-slot:activator="{ on, attrs }">
              <v-btn icon color="primary" dark v-bind="attrs" v-on="on">
                <v-icon> mdi-dots-vertical </v-icon>
              </v-btn>
            </template>

            <v-list>
              <v-list-item @click="handleDownloadInvoice(item.id)" dense link>
                <v-icon size="20" color="success" class="mr-3"
                  >mdi-download</v-icon
                >
                Download
              </v-list-item>
            </v-list>
          </v-menu>
        </template>
      </v-data-table>
    </v-card>
    <CircleLoader center v-if="loading" size="84" speed="1" border-width="3" />
  </div>
</template>

<script>
import moment from "moment";
import formFieldMixin from "@/mixins/formFieldMixin";
import CircleLoader from "@/components/customs/CircleLoader";
import { mapActions, mapGetters } from "vuex";
import { sleep } from '@/helpers'
export default {
     name: "invoiceFile",
     mixins: [
        formFieldMixin,
    ],
        components: {

        CircleLoader
    },
    data(){
      return{
        date: new Date().toISOString().substr(0, 10),
        menu: false,
        meunu1:false,
        filterSearch:{
          start_date:new Date().toISOString().substr(0, 10),
          end_date:new Date().toISOString().substr(0, 10),
        },
        loading:false,
        search: '',
           headers: [
                {
                    text: "Client",
                    align: "start",
                    sortable: false,
                    value: "client.name"
                },
                { text: "Compnay", value: "companies.name", sortable: false },
                { text: "Type", value: "sendingType", sortable: false },
                { text: "Sending Date", value: "sendingDate", sortable: false },
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
                {
                    text: "amount",
                    value: "invoiceHistory.amount",
                    sortable: false
                },
                {
                    text: "Status",
                    value: "invoiceHistory.isPaid",
                    sortable: false
                },
                { text: "Actions", value: "actions", sortable: false }
            ],
      }
    },
    computed:{
      ...mapGetters("INVOICE", ["$summarizedInvocie"]),
      ...mapGetters("AUTH", ["$user"]),
    },
    methods:{
      ...mapActions("INVOICE", ["fetchSummarizedInoice","downloadInoice","downloadSummarizedInoice"]),
      async onCreated() {
            this.loading = true;
            await this.fetchSummarizedInoice(this.filterSearch);
            this.loading = false;
        },
       async onFilterSearch(){
            this.loading = true;
            console.log('dsdsdsdasdsdd');
            await this.fetchSummarizedInoice(this.filterSearch);
            this.loading = false;
        },
       async handleDownloadInvoice(id){
         await this.downloadInoice(id)
       },
       async handleBulkDownload(){
         for (let inv of this.$summarizedInvocie) {

           window.open("/api/v1/download/" + inv.id, "_blank");
         }
    
       },
       async handleSummarizedDownload(){  
         window.open(`api/v1/summarize-download?start_date=${this.filterSearch.start_date}&end_date=${this.filterSearch.end_date}&user_id=${this.$user.id}}`)

       }
    }
}
</script>