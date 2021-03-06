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
          <UserForm
            v-if="cmDialog"
            :errors="errors"
            :isUpdate="update.dialog"
            :data="update.data"
            :companyList="$companyList"
            @editUser="handleEditUser"
            @addUser="handleAddUser"
            @reAssignUser="handleReassignUser"
            :reAssign="reAssign"
          />
        </v-card-text>
      </v-card>
    </v-dialog>
    <v-card>
      <v-card-title>
        Users
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
        :items="$users.filter((user) => user.role !== 'super admin')"
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
              <v-divider></v-divider>
              <v-list-item
                @click="handleBlockUser(item.id, item.isActive)"
                dense
                link
              >
                <v-icon size="20" color="error" class="mr-3"
                  >mdi-block-helper</v-icon
                >
                {{ item.isActive == "1" ? "Block" : "Unblock" }}
              </v-list-item>
              <v-divider></v-divider>
              <v-list-item
                @click="initUpdate(item), (reAssign = true)"
                dense
                link
              >
                <v-icon color="error" class="mr-3">mdi-update</v-icon>
                Change Company
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
      @yes="handleDeleteUser"
      @no="resetDelete"
    />
    <v-dialog
      :width="this.$vuetify.breakpoint.mdAndUp ? '30vw' : '80vw'"
      v-model="viewDetails"
    >
      <v-card v-if="viewUserInfo">
        <v-card-title><span class="mx-auto">View Details</span></v-card-title>
        <v-card-text>
          <v-form readonly>
            <v-row>
              <v-col cols="12">
                <v-text-field
                  v-bind="fieldOptions"
                  v-model="viewUserInfo.name"
                  label="Name"
                ></v-text-field>
              </v-col>
              <v-col cols="12">
                <v-text-field
                  v-bind="fieldOptions"
                  v-model="viewUserInfo.email"
                  label="Email"
                ></v-text-field>
              </v-col>
              <!-- <v-col cols="12">
                <v-text-field
                  v-bind="fieldOptions"
                  :v-model="viewUserInfo.isActive == '1'? true: false"
                  label="Activation"
                ></v-text-field>
              </v-col> -->
              <v-col cols="12">
                <v-text-field
                  v-bind="fieldOptions"
                  v-model="viewUserInfo.createdAt"
                  label="Created at"
                ></v-text-field>
              </v-col>
              <v-col cols="12">
                <v-text-field
                  v-bind="fieldOptions"
                  v-model="viewUserInfo.updatedAt"
                  label="Update at"
                ></v-text-field>
              </v-col>
              <v-col cols="12">
                <v-text-field
                  v-bind="fieldOptions"
                  v-model="viewUserInfo.Active"
                  label="Status"
                ></v-text-field>
              </v-col>
              <v-col cols="12">
                <v-text-field
                  v-bind="fieldOptions"
                  v-model="viewUserInfo.company.name"
                  label="Name"
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
import { mapActions, mapGetters } from "vuex";
import UserForm from "@/components/forms/UserForm.vue";
import fabCreateButton from "@/components/button/fabCreateButton";
import crudMixin from "@/mixins/crud-mixin";
import confirm from "@/components/dialog/confirm.vue";
import CircleLoader from "@/components/customs/CircleLoader";
export default {
    name: "Users",
    mixins: [crudMixin,formFieldMixin],
    components: {
        UserForm,
        fabCreateButton,
        confirm,
        CircleLoader
    },
    data() {
        return {
            cardloader: false,
            loading:false,
            viewDetails:false,
            viewUserInfo:{
              company: {
                name:''
              }
            },
            search: "",
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
                { text: "Role", value: "role", sortable: false },
                { text: "Company", value: "company", sortable: false },
                { text: "Status", value: "isActive", sortable: false },
                { text: "Actions", value: "actions", sortable: false },
            ],
            desserts: [
                {
                    name: "Lorem Ipsum",
                    email: "lorem@gmail.com",
                    role: "lorem",
                    company: "ABCD"
                }
            ]
        };
    },
    computed: {
        ...mapGetters("USERS", ["$users","$pagination"]),
        ...mapGetters("COMPANY", ["$companyList"])
    },
    methods: {
        ...mapActions("USERS", [
            "fetchUsers",
            "deleteUser",
            "addUser",
            "updateUsers",
            "blockUsers",
            "reAssignCompany"
        ]),
        ...mapActions("COMPANY",["fetchCompanyList"]),
        async onChangePage(page){
          this.tableLoader = true;
            await this.fetchUsers({page,per_page:10});
            this.tableLoader = false;
        },
        handleView(item){
         this.viewDetails=true,
         this.viewUserInfo=item
         this.viewUserInfo.Active=this.viewUserInfo.isActive == "1" ? "Active" : "Blocked" 
         this.viewUserInfo.createdAt= moment(this.viewUserInfo.createdAt).format('ll')
          this.viewUserInfo.updatedAt= moment(this.viewUserInfo.updatedAt).format('ll')
        },

        onInputUserDialog(dialog) {
            if (!dialog) {
                this.resetUpdate();
                this.resetCreate();
            }
            this.errors={}
        },
        async onFetchUsers() {
            this.tableLoader = true;
            await this.fetchUsers({per_page:10,page:1});
            this.tableLoader = false;
        },
        async CompanyList(){
            this.loader=true
            await this.fetchCompanyList()
            this.loading=false
        },
        async handleAddUser(user) {

            this.loading = true;
            // this.create.loading = true;
            let res = await this.addUser(user);
            if (res.error){
             this.enableSnackbar('failed','An error ocured when creating admin')
             this.errors = res.errors;
            } 
            else {
                this.enableSnackbar('success','Admin created successfully')
                this.resetCreate();
            }
            this.loading = false;
        },
        async handleEditUser(user) {

            this.loading = true;

            this.create.loading = true;
            let res = await this.updateUsers(user);
            if (res.error){

             this.enableSnackbar('failed','An error ocured when editing admin')
             this.errors = res.errors;
            } 
            else {
                this.enableSnackbar('success','Admin updated successfully')
                this.resetUpdate();
            }
            this.loading = false;
        },
        async handleDeleteUser() {
            
            this.loading = true;
            let res = await this.deleteUser(this.deletee.id);
            if (res.error){
             this.enableSnackbar('failed','An error ocured when deleting admin')
            } 
            else {
                this.enableSnackbar('success','Admin deleted successfully')
            }
            this.resetDelete();
            this.loading = false;
        },
        async handleBlockUser(id,type) {
            let action= (type == 1 ? 'Blocked' : 'Unblocked') 
            // {{ item.isActive =='1'? 'Block' : 'Unblock' }}
            this.loading = true;
            let res = await this.blockUsers(id)
            if (res.error){
             this.enableSnackbar('failed',`An error ocured when ${action} a admin`)
            } 
            else {
                this.enableSnackbar('success',`Admin ${action} successfully`)
            }
            this.resetDelete();
            this.loading = false;
        },
       async handleReassignUser(user){
            this.loading = true;
            this.create.loading = true;
            let res = await this.reAssignCompany({user_id:user.user_id,company_id:user.company_id});
            if (res.error){
             this.enableSnackbar('failed','An error ocured when Re-assigning a company')
             
            } 
            else {
                this.enableSnackbar('success','Company Re-assign successfully')
                this.reAssign=false
                this.resetUpdate();
            }
            this.loading = false;
       } 
    },
    created() {
        this.onFetchUsers();
        this.CompanyList()
   
    }
};
</script>

<style></style>
