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
          <CompanyForm
            @editCompany="handleEditCompany"
            @addCompany="handleAddCompany"
            :isUpdate="update.dialog"
            :data="update.data"
          />
        </v-card-text>
      </v-card>
    </v-dialog>
    <v-card :loading="loading">
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
        :loading="tableLoader"
        loading-text="Loading... Please wait"
        :headers="headers"
        :items="$company"
        :search="search"
        hide-default-footer
        :items-per-page="+$pagination.perPage"
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
      </v-data-table>
      <div class="text-center pt-2">
        <v-pagination
          :value="$pagination.currentPage"
          @input="onChangePage"
          :length="Math.ceil($pagination.totalPage / $pagination.perPage)"
        ></v-pagination>
      </div>
      <!-- Start Confirm delete action -->
      <confirm
        title="Are you sure to delete?"
        subtitle="Once you delete, this action can't be undone"
        v-model="deletee.dialog"
        :loading="deletee.loading"
        @yes="handleDeletecompany"
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
import CompanyForm from "@/components/forms/CompanyForm.vue";
import fabCreateButton from "@/components/button/fabCreateButton";
import confirm from "@/components/dialog/confirm.vue";
import crudMixin from "@/mixins/crud-mixin";
export default {
    name: "company",
    mixins: [crudMixin],
    components: {
        CompanyForm,
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
                { text: "Address", value: "address", sortable: false },
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
        ...mapGetters("COMPANY", ["$company","$pagination"])
    },
    methods: {
        ...mapActions("COMPANY", [
            "fetchCompany",
            "deleteCompany",
            "addCompany",
            "updateCompany"
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
        async onChangePage(page){
          this.tableLoader = true;
            await this.fetchCompany({page,per_page:10});
            this.tableLoader = false;
        },
        async onFetchCompany() {
            this.tableLoader = true;
            await this.fetchCompany({per_page:10,page:1});
            this.tableLoader = false;
        },
        async handleAddCompany(company) {
            this.loading = true;
            console.log(company);
            // this.create.loading = true;
            let res = await this.addCompany(company);
            if (res.error){
             console.log(res.error);
             this.enableSnackbar('failed','An error ocured when creating Company')
            } 
            else {
                this.enableSnackbar('success','Company created successfully')
            }
            this.resetCreate();
            this.loading = false;
        },
        async handleEditCompany(company) {
            this.loading = true;
            console.log(company);
            // this.create.loading = true;
            let res = await this.updateCompany(company);
            if (res.error){
             console.log(res.error);
             this.enableSnackbar('failed','An error ocured when editing Company')
            } 
            else {
                this.enableSnackbar('success','Company updated successfully')
            }
            this.resetUpdate();
            this.loading = false;
        },
        async handleDeletecompany() {
            this.loading = true;
            let res = await this.deleteCompany(this.deletee.id);
            if (res.error){
             console.log(res.error);
             this.enableSnackbar('failed','An error ocured when deleting Company')
            } 
            else {
                this.enableSnackbar('success','Company deleted successfully')
            }
            this.resetDelete();
            this.loading = false;
        }
    },
    created() {
        this.onFetchCompany();
    }
};
</script>

<style></style>
