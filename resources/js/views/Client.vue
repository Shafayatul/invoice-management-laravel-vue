<template>
  <div class="ml-3">
    <v-dialog
      v-model="cmDialog"
      v-if="cmDialog"
      @input="onInputUserDialog"
      :width="this.$vuetify.breakpoint.mdAndUp ? '30vw' : '80vw'"
    >
      <v-card>
        <v-card-text>
          <ClientForm
            :errors="errors"
            v-if="cmDialog"
            :isUpdate="update.dialog"
            :data="update.data"
            :companyList="$companyList"
            @editClient="handleEditClient"
            @addClient="handleAddClient"
            :reAssign="reAssign"
            hide-default-footer
            :items-per-page="+$pagination.perPage"
          />
        </v-card-text>
      </v-card>
    </v-dialog>
    <v-card>
      <v-card-title>
        Clients
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
        :items="$client"
        :search="search"
        hide-default-footer
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
              <v-divider></v-divider>
              <v-list-item @click="initUpdate(item)" dense link>
                <v-icon class="mr-2">mdi-pencil</v-icon>
                Edit
              </v-list-item>
              <v-divider></v-divider>
              <v-list-item @click="initDelete(item.id)" dense link>
                <v-icon color="error" class="mr-2">mdi-delete</v-icon>
                Delete
              </v-list-item>
            </v-list>
          </v-menu>
        </template>
        <template v-slot:item.company="{ item }">
          {{ item.company.name }}
        </template>
        <template v-slot:item.isActive="{ item }">
          {{ item.isActive == "1" ? "Active" : "Blocked" }}
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
    <confirm
      title="Are you sure to delete?"
      subtitle="Once you delete, this action can't be undone"
      v-model="deletee.dialog"
      :loading="deletee.loading"
      @yes="handleDeleteClient"
      @no="resetDelete"
    />
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
                  v-model="viewClientInfo.name"
                  label="Name"
                ></v-text-field>
              </v-col>
              <v-col cols="12">
                <v-text-field
                  v-bind="fieldOptions"
                  v-model="viewClientInfo.email"
                  label="Email"
                ></v-text-field>
              </v-col>
              <!-- <v-col cols="12">
                <v-text-field
                  v-bind="fieldOptions"
                  :v-model="viewClientInfo.isActive == '1'? true: false"
                  label="Activation"
                ></v-text-field>
              </v-col> -->
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
                  v-model="viewClientInfo.updatedAt"
                  label="Update at"
                ></v-text-field>
              </v-col>
              <v-col cols="12">
                <v-text-field
                  v-bind="fieldOptions"
                  v-model="viewClientInfo.isActive"
                  label="Status"
                ></v-text-field>
              </v-col>
              <v-col cols="12">
                <v-text-field
                  v-bind="fieldOptions"
                  v-model="viewClientInfo.company.name"
                  label="Name"
                ></v-text-field>
              </v-col>
            </v-row>
          </v-form>
          <!-- {{viewClientInfo.company.name}} -->
        </v-card-text>
      </v-card>
    </v-dialog>
  </div>
</template>

<script>
import moment from 'moment';
import { mapActions, mapGetters } from "vuex";
import formFieldMixin from "@/mixins/formFieldMixin";
import ClientForm from "@/components/forms/ClientForm.vue";
import fabCreateButton from "@/components/button/fabCreateButton";
import crudMixin from "@/mixins/crud-mixin";
import confirm from "@/components/dialog/confirm.vue";
import CircleLoader from "@/components/customs/CircleLoader";
export default {
    name: "Client",
    mixins: [crudMixin,formFieldMixin,],
    components: {
        ClientForm,
        fabCreateButton,
        confirm,
        CircleLoader
    },
    data() {
        return {
            cardloader: false,
            loading:false,
            search: "",
            viewDetails:false,
            viewClientInfo:{
              company: {
                name:''
              }
            },
            reAssign:false,
            snackbar: {
                action: false,
                text: "",
                color: "success",
                timeout: "3000"
            },
            errors: {},
            headers: [
                {
                    text: "Name",
                    align: "start",
                    sortable: false,
                    value: "name"
                },
                { text: "Email", value: "email", sortable: false },
                { text: "Phone", value: "phone", sortable: false },
                { text: "Company", value: "company", sortable: false },
                { text: "Status", value: "isActive", sortable: false },
                { text: "Actions", value: "actions", sortable: false },
            ],
        };
    },
    computed: {
        ...mapGetters("CLIENT", ["$client","$pagination"]),
        ...mapGetters("COMPANY", ["$companyList"]),
        ...mapGetters("AUTH", [ "$user"]),
    },
    methods: {
        ...mapActions("CLIENT", [
            "fetchClient",
            "deleteClient",
            "addClient",
            "updateClient",
          //   "blockClient",
          //   "reAssignCompany"
        ]),
        ...mapActions("COMPANY",["fetchCompanyList"]),
        ...mapActions("AUTH", [ "fetchProfile"]),
        onInputUserDialog(dialog) {
            if (!dialog) {
                this.resetUpdate();
                this.resetCreate();
            }
            this.errors={}
        },
        async onChangePage(page){
          this.tableLoader = true;
            await this.fetchClient({page,per_page:10});
            this.tableLoader = false;
        },
        async onFetchClient() {
            this.tableLoader = true;
            await this.fetchClient({per_page:10,page:1});
            this.tableLoader = false;
        },
        async CompanyList(){
            this.loader=true
            await this.fetchCompanyList()
            this.loading=false
        },
        async fetchProfileData(){
            
            await this.fetchProfile()
          
        },
        handleView(item){
         this.viewDetails=true,
         this.viewClientInfo=item
         this.viewClientInfo.isActive=this.viewClientInfo.isActive == "1" ? "Active" : "Blocked" 
         this.viewClientInfo.createdAt= moment(this.viewClientInfo.createdAt).format('ll')
          this.viewClientInfo.updatedAt= moment(this.viewClientInfo.updatedAt).format('ll')
        },
        async oncreated(){
           this.loading=true
           await this.onFetchClient();
           await this.CompanyList();
           await this.fetchProfileData();
           this.loading=false
        },
        async handleAddClient(user) {
            this.loading = true;
            // this.create.loading = true;
            let res = await this.addClient(user);
            if (res.error){

             this.enableSnackbar('failed','An error ocured when creating client')
             this.errors = res.errors;
            } 
            else {
                this.enableSnackbar('success','client created successfully')
                this.resetCreate();
                this.errors={}
            }
            this.loading = false;
        },
        async handleEditClient(user) {
            this.loading = true;
            this.create.loading = true;
            let res = await this.updateClient(user);
            if (res.error){

             this.enableSnackbar('failed','An error ocured when editing client')
             this.errors = res.errors;
            } 
            else {
                this.enableSnackbar('success','Client updated successfully')
                this.resetUpdate();
            }
            this.loading = false;
        },
        async handleDeleteClient() {
            
            this.loading = true;
            let res = await this.deleteClient(this.deletee.id);
            if (res.error){
             this.enableSnackbar('failed','An error ocured when deleting client')
            } 
            else {
                this.enableSnackbar('success','Client deleted successfully')
            }
            this.resetDelete();
            this.loading = false;
        },
     //    async handleBlockUser(id,type) {
     //        let action= (type == 1 ? 'Blocked' : 'Unblocked') 
     //        // {{ item.isActive =='1'? 'Block' : 'Unblock' }}
     //        this.loading = true;
     //        let res = await this.blockClient(id)
     //        if (res.error){
     //         console.log(res.error);
     //         this.enableSnackbar('failed',`An error ocured when ${action} a user`)
     //        } 
     //        else {
     //            this.enableSnackbar('success',`User ${action} successfully`)
     //        }
     //        this.resetDelete();
     //        this.loading = false;
     //    },
     //   async handleReassignUser(user){
     //        console.log('reasasign');
     //        console.log(user);
     //        this.loading = true;
     //        this.create.loading = true;
     //        let res = await this.reAssignCompany({user_id:user.user_id,company_id:user.company_id});
     //        if (res.error){
     //         console.log(res.error);
     //         this.enableSnackbar('failed','An error ocured when Re-assigning a company')
             
     //        } 
     //        else {
     //            this.enableSnackbar('success','Company Re-assign successfully')
     //            this.reAssign=false
     //        }
     //        this.resetCreate();
     //        this.loading = false;
     //   } 
    },
    created() {
        this.oncreated()
    }
};
</script>

<style></style>
