<template>
  <div class="ml-3">
    <v-dialog
      @input="onInputCompanyDialog"
      v-model="cmDialog"
      v-if="cmDialog"
      :width="this.$vuetify.breakpoint.mdAndUp ? '30vw' : '80vw'"
    >
      <v-card>
        <v-card-text>
          <PaymentForm
            @editPayment="handleEditPayment"
            @addPayment="handleAddPayment"
            :isUpdate="update.dialog"
            :data="update.data"
          />
        </v-card-text>
      </v-card>
    </v-dialog>
    <v-card :loading="loading">
      <v-card-title>
        Payment
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
        :loading="tableLoader"
        loading-text="Loading... Please wait"
        :headers="headers"
        :items="$payment"
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
              <v-list-item @click="initDelete(item.id)" dense link>
                <v-icon class="mr-2">mdi-delete</v-icon>
                Delete
              </v-list-item>
            </v-list>
          </v-menu>
        </template>
        <template v-slot:item.type="{ item }">
           <v-chip small :color="item.type === 'one_time' ? 'primary' : 'success' "> {{ item.type === "one_time" ? "One Time" : "Recurring Period" }}</v-chip>
         
        </template>
      </v-data-table>
      <!-- Start Confirm delete action -->
      <confirm
        title="Are you sure to delete?"
        subtitle="Once you delete, this action can't be undone"
        v-model="deletee.dialog"
        :loading="deletee.loading"
        @yes="handleDeletecCmpany"
        @no="resetDelete"
      />
      <!-- End Confirm delete action -->
    </v-card>
    <fabCreateButton @click="initCreate()" />
    <CircleLoader center v-if="loading" size="84" speed="1" border-width="3" />
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
  </div>
</template>

<script>
import { mapActions, mapGetters } from "vuex";
import CircleLoader from "@/components/customs/CircleLoader";
import PaymentForm from "@/components/forms/PaymentForm.vue";
import fabCreateButton from "@/components/button/fabCreateButton";
import confirm from "@/components/dialog/confirm.vue";
import crudMixin from "@/mixins/crud-mixin";
export default {
    name: "Payment",
    mixins: [crudMixin],
    components: {
        PaymentForm,
        fabCreateButton,
        confirm,
        CircleLoader
    },
    data() {
        return {
            loading: false,
            tableLoader: false,
            search: "",
            snackbar: {
                action: false,
                text: "",
                color:"success",
                timeout:'3000'
            },
            headers: [
                {
                    text: "Name",
                    align: "start",
                    sortable: false,
                    value: "name"
                },
                { text: "Type", value: "type", sortable: false },
                { text: "Details", value: "details", sortable: false },
                { text: "Actions", value: "actions", sortable: false }
            ],
            desserts: [
                {
                    name: "Lorem Ipsum",
                    address: "lorem ipsum dolor sit"
                }
            ]
        };
    },
    computed: {
        ...mapGetters("PAYMENT", ["$payment"])
    },
    methods: {
        ...mapActions("PAYMENT", [
            "fetchPayment",
            "deletePayment",
            "addPayment",
            "updatePayment"
        ]),
        click() {
            this.dialog = true;
        },
        onInputCompanyDialog(dialog) {
          console.log('calling');
            if (!dialog) {
                this.resetUpdate();
                this.resetCreate();
            }
        },
        async onFetchPayment() {
            this.tableLoader = true;
            await this.fetchPayment();
            this.tableLoader = false;
        },
        async handleAddPayment(payment) {
            this.loading = true;
            console.log(payment);
            // this.create.loading = true;
            let res = await this.addPayment(payment);
            if (res.error){
             console.log(res.error);
             this.enableSnackbar('failed','An error ocured when creating Payment')
            } 
            else {
                this.enableSnackbar('success','Payment created successfully')
            }
            this.resetCreate();
            this.loading = false;
        },
        async handleEditPayment(payment) {
            this.loading = true;
            console.log(payment);
            // this.create.loading = true;
            let res = await this.updatePayment(payment);
            if (res.error){
             console.log(res.error);
             this.enableSnackbar('failed','An error ocured when editing Payment')
            } 
            else {
                this.enableSnackbar('success','Payment updated successfully')
            }
            this.resetUpdate();
            this.loading = false;
        },
        async handleDeletecCmpany() {
            this.loading = true;
            let res = await this.deletePayment(this.deletee.id);
            if (res.error){
             console.log(res.error);
             this.enableSnackbar('failed','An error ocured when deleting Payment')
            } 
            else {
                this.enableSnackbar('success','Payment deleted successfully')
            }
            this.resetDelete();
            this.loading = false;
        }
    },
    created() {
        this.onFetchPayment();
    }
};
</script>

<style></style>
