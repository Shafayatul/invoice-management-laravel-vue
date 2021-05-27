<template>
  <div class="ml-3">
    <v-dialog
      @input="onInputInvoiceDialog"
      v-model="cmDialog"
      v-if="cmDialog"
      :width="this.$vuetify.breakpoint.mdAndUp ? '30vw' : '80vw'"
    >
      <v-card :loading="loading">
        <v-card-text>
          <InvoiceForm
            v-if="cmDialog"
            :errors="errors"
            @editInvoice="handleEditInvoice"
            :companyList="$companyList"
            @addInvoice="handleAddInvoice"
            @paidInvoice="handlePaidInvoice"
            :isUpdate="update.dialog"
            :data="update.data"
            :loading="loading"
            :clientList="$clientList"
          />
        </v-card-text>
      </v-card>
    </v-dialog>

    <v-card>
      <v-card-title>
        Invoice
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
        :items="$invoice"
        :search="search"
        hide-default-footer
        :items-per-page="+$pagination.perPage"
      >
        <template v-slot:item.invoiceHistory.isPaid="{ item }">
          <v-chip
            small
            :color="item.invoiceHistory.isPaid === 1 ? 'success' : 'error'"
          >
            {{ item.invoiceHistory.isPaid === 1 ? "Paid" : "Pending" }}</v-chip
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
              <v-list-item @click="handleView(item)" dense link>
                <v-icon size="20" color="success" class="mr-3">mdi-eye</v-icon>
                View
              </v-list-item>
              <v-divider></v-divider>
              <v-list-item v-if="item.invoiceHistory.isPaid == 0" @click="initUpdate(item)" dense link>
                <v-icon class="mr-2">mdi-pencil</v-icon>
                Edit
              </v-list-item>
              <v-divider></v-divider>
              <v-list-item @click="initDelete(item.id)" dense link>
                <v-icon class="mr-2">mdi-delete</v-icon>
                Delete
              </v-list-item>
              <v-divider v-if="item.invoiceHistory.isPaid == 0"></v-divider>
              <v-list-item
                v-if="item.invoiceHistory.isPaid == 0"
                @click="handlePaidDialog(item)"
                dense
                link
              >
                <v-icon class="mr-2">mdi-cash-check</v-icon>
                Pay invoice
              </v-list-item>
            </v-list>
          </v-menu>
        </template>
      </v-data-table>
      <div class="text-center pt-2">
        <v-pagination
          :value="$pagination.currentPage"
          @input="onChangePage"
          :length="Math.ceil($pagination.totalPage / $pagination.perPage)"
        ></v-pagination>
      </div>
    </v-card>
    <fabCreateButton v-if="$user.role === 'admin'" @click="initCreate()" />
    <v-snackbar
      v-model="snackbar.action"
      :timeout="snackbar.timeout"
      :color="snackbar.color"
      top
      right
      class="font-weight-bold"
    >
      {{ snackbar.text }}
    </v-snackbar>
    <CircleLoader center v-if="loading" size="84" speed="1" border-width="3" />
    <!-- Start Confirm delete action -->
    <confirm
      title="Are you sure to delete?"
      subtitle="Once you delete, this action can't be undone"
      v-model="deletee.dialog"
      :loading="deletee.loading"
      @yes="handleDeleteInvoice"
      @no="resetDelete"
    />
    <v-dialog
      :width="this.$vuetify.breakpoint.mdAndUp ? '30vw' : '80vw'"
      v-model="paidDialog"
      @click:outside='$refs.paidInvoiceForm.reset()'
    >
      <v-card>
        <v-card-title><span class="mx-auto">Pay invoice</span></v-card-title>
        <v-card-text>
          <v-form
            v-model="isValid"
            @submit.prevent="handlePaidInvoice"
            lazy-validation
            ref="paidInvoiceForm"
          >
            <v-row>
              <v-col cols="12">
                <v-select
                  :items="$paymentList"
                  v-bind="fieldOptions"
                  v-model="payInvoice.category_id"
                  :rules="[rules.required('Category')]"
                  label="Category"
                  item-value="id"
                  item-text="name"
                ></v-select>
              </v-col>
              <v-col>
                <v-file-input
                  @change="fileInput($event)"
                  v-bind="fieldOptions"
                  label="Bills files"
                ></v-file-input>
              </v-col>
            </v-row>
            <v-btn
              :loading="loading"
              block
              class="my-3"
              color="primary"
              @click="handlePaidInvoice"
            >
              confirm
            </v-btn>
          </v-form>
        </v-card-text>
      </v-card>
    </v-dialog>

    <!-- End Confirm delete action -->
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
                  v-model="viewClientInfo.invoiceHistory.itemName"
                  label="Item Name"
                ></v-text-field>
              </v-col>
              <v-col cols="12">
                <v-text-field
                  v-bind="fieldOptions"
                  v-model="viewClientInfo.invoiceHistory.quantity"
                  label="Quantity"
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
                  v-model="viewClientInfo.companies.name"
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
              <v-col cols="12">
                <v-text-field
                  v-bind="fieldOptions"
                  v-model="viewClientInfo.sendingType"
                  label="Sending type"
                ></v-text-field>
              </v-col>
              <v-col v-if="viewClientInfo.sendingType == 'recurring'" cols="12">
                <v-text-field
                  v-bind="fieldOptions"
                  v-model="viewClientInfo.recurringPeriod"
                  label="Recurring period"
                ></v-text-field>
              </v-col>
              <v-col cols="12">
                <v-text-field
                  v-bind="fieldOptions"
                  v-model="viewClientInfo.isPaid"
                  label="Status"
                ></v-text-field>
              </v-col>
              <v-col cols="12">
                <v-text-field
                  v-bind="fieldOptions"
                  v-model="viewClientInfo.invoiceHistory.amount"
                  label="Amount"
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
import { createFormMixin } from "@/mixins/form-mixin";
import { mapActions, mapGetters } from "vuex";
import confirm from "@/components/dialog/confirm.vue";
import CircleLoader from "@/components/customs/CircleLoader";
import InvoiceForm from "@/components/forms/InvoiceForm.vue";
import fabCreateButton from "@/components/button/fabCreateButton";
import crudMixin from "@/mixins/crud-mixin";

export default {
  name: "invoice",
  mixins: [
    crudMixin,
    formFieldMixin,
    createFormMixin({
      rules: ["min", "required"],
    }),
  ],
  components: {
    InvoiceForm,
    fabCreateButton,
    confirm,
    CircleLoader,
  },
  data() {
    return {
      cardloader: false,
      loading: false,
      tableLoader: false,
      search: "",
      paidDialog: false,
      isValid: false,
      errors: {},
      payInvoice: {
        invoice_id: null,
        category_id: null,
        receipt_file: null,
      },
      viewDetails: false,
      viewClientInfo: {
        client: {
          name: "",
          email: "",
          phone: "",
        },
        companies: {
          name: "",
        },
        invoiceHistory: {
          amount: "",
          isPaid: "",
        },
      },
      snackbar: {
        action: false,
        text: "",
        color: "success",
        timeout: "3000",
      },
      headers: [
        {
          text: "Client",
          align: "start",
          sortable: false,
          value: "client.name",
        },
        { text: "Compnay", value: "companies.name", sortable: false },
        { text: "Type", value: "sendingType", sortable: false },
        { text: "Sending Date", value: "sendingDate", sortable: false },
        {
          text: "Recurring period",
          value: "recurringPeriod",
          sortable: false,
        },
        {
          text: "Created by",
          value: "createdBy.name",
          sortable: false,
        },
        {
          text: "Item",
          value: "invoiceHistory.itemName",
          sortable: false,
        },
        {
          text: "Amount",
          value: "invoiceHistory.amount",
          sortable: false,
        },
        {
          text: "Quantity",
          value: "invoiceHistory.quantity",
          sortable: false,
        },
        {
          text: "Status",
          value: "invoiceHistory.isPaid",
          sortable: false,
        },
        { text: "Actions", value: "actions", sortable: false },
      ],
      desserts: [
        {
          name: "Lorem Ipsum",
          address: "lorem ipsum dolor sit",
        },
      ],
    };
  },
  computed: {
    ...mapGetters("INVOICE", ["$invoice", "$pagination"]),
    ...mapGetters("COMPANY", ["$companyList"]),
    ...mapGetters("CLIENT", ["$clientList"]),
    ...mapGetters("PAYMENT", ["$paymentList"]),
    ...mapGetters("AUTH", ["$user"]),
  },
  methods: {
    ...mapActions("INVOICE", [
      "fetchInvoice",
      "deleteInvoice",
      "addInvoice",
      "updateInvoice",
      "paidInvoice",
    ]),
    ...mapActions("COMPANY", ["fetchCompanyList"]),
    ...mapActions("CLIENT", ["fetchClientList"]),
    ...mapActions("PAYMENT", ["fetchPaymentList"]),
    click() {
      this.dialog = true;
    },
    async onChangePage(page) {
      this.tableLoader = true;
      await this.fetchInvoice({ page, per_page: 10 });
      this.tableLoader = false;
    },
    onInputInvoiceDialog(dialog) {
      if (!dialog) {
        this.resetUpdate();
        this.resetCreate();
      }
      this.errors = {};
    },
    async CompanyList() {
      this.loader = true;
      await this.fetchCompanyList();
      this.loading = false;
    },
    async ClientList() {
      this.loader = true;
      await this.fetchClientList();
      this.loading = false;
    },
    async onFetchInvoice() {
      this.tableLoader = true;
      await this.fetchInvoice({ per_page: 10, page: 1 });
      this.tableLoader = false;
    },
    async handleAddInvoice(invoiceDetails) {
      this.loading = true;

      // this.create.loading = true;
      let res = await this.addInvoice(invoiceDetails);
      if (res.error) {
        this.enableSnackbar("failed", "An error ocured when creating Invoice");

        this.errors = res.errors;
      } else {
        this.enableSnackbar("success", "Invoice created successfully");
        this.resetCreate();
      }
      this.loading = false;
    },
    handleView(item) {
      console.log(item);
      (this.viewDetails = true), (this.viewClientInfo = item);
      this.viewClientInfo.sendingType =
        this.viewClientInfo.sendingType.charAt(0).toUpperCase() +
        this.viewClientInfo.sendingType.slice(1).replace("_", " ");
      this.viewClientInfo.createdAt = moment(
        this.viewClientInfo.createdAt
      ).format("ll");
      this.viewClientInfo.updatedAt = moment(
        this.viewClientInfo.updatedAt
      ).format("ll");
      this.viewClientInfo.isPaid =
        this.viewClientInfo.invoiceHistory.isPaid === 1 ? "Paid" : "Pending";
      this.viewClientInfo.invoiceHistory = item.invoiceHistory;
    },
    async handleEditInvoice(invoiceDetails) {
      this.loading = true;

      // this.create.loading = true;
      let res = await this.updateInvoice(invoiceDetails);
      if (res.error) {
        this.enableSnackbar("failed", "An error ocured when editing Invoice");
        this.errors = res.errors;
      } else {
        this.enableSnackbar("success", "Invoice updated successfully");
        this.resetUpdate();
      }
      this.loading = false;
    },
    handlePaidDialog(item) {
      this.paidDialog = true;
      this.payInvoice.invoice_id = item.id;
    },
    async handlePaidInvoice() {
      if (this.$refs.paidInvoiceForm.validate()) {
        this.loading = true;
        // this.create.loading = true;
        if (!this.payInvoice.receipt_file) {
          delete this.payInvoice.receipt_file;
        }
        let res = await this.paidInvoice(this.payInvoice);
        if (res.error) {
          this.enableSnackbar(
            "failed",
            "An error ocured when paying a Invoice"
          );
          this.errors = res.errors;
        } else {
          this.enableSnackbar("success", "Invoice paid successfully");
          this.paidDialog = false;
          this.$refs.paidInvoiceForm.reset()
        }
        this.loading = false;
      }
    },
    async handleDeleteInvoice() {
      this.loading = true;
      let res = await this.deleteInvoice(this.deletee.id);
      if (res.error) {
        this.enableSnackbar("failed", "An error ocured when deleting Invoice");
      } else {
        this.enableSnackbar("success", "Invoice deleted successfully");
      }
      this.resetDelete();
      this.loading = false;
    },
    async onCreated() {
      this.loading = true;
      await this.onFetchInvoice();
      await this.CompanyList();
      await this.ClientList();
      await this.fetchPaymentList();
      this.loading = false;
    },
    fileInput(event) {
      this.payInvoice.receipt_file = event;
    },
  },
  created() {
    this.onCreated();
  },
};
</script>

<style></style>
