<template>
  <div class="ml-3">
    <v-dialog
      @input="onInputCompanyDialog"
      v-model="cmDialog"
      :width="this.$vuetify.breakpoint.mdAndUp ? '30vw' : '80vw'"
    >
      <v-card>
        <v-card-text>
          <!-- <v-card-title> Create company </v-card-title> -->
          <CompanyForm
            @editCompany="handleEditCompany"
            @addCompany="handleAddCompany"
            :isUpdate="update.dialog"
            :data="update.data"
          />
        </v-card-text>
        <!-- <v-card-actions>
          <v-btn class="ml-6 mb-2" color="primary" outlined> ADD </v-btn>
        </v-card-actions> -->
      </v-card>
    </v-dialog>
    <v-card :loading="loading">
      <v-card-title>
        Company
        <v-spacer></v-spacer>
        <v-text-field
          v-model="search"
          append-icon="mdi-magnify"
          label="Search"
          single-line
          hide-details
        ></v-text-field>
      </v-card-title>
      <v-data-table
        :loading="cardloader"
        loading-text="Loading... Please wait"
        :headers="headers"
        :items="$company"
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
              <v-list-item @click="handleDeletecompany(item.id)" dense link>
                <v-icon  class="mr-2">mdi-delete</v-icon>
                Delete
              </v-list-item>
            </v-list>
          </v-menu>
        </template>
      </v-data-table>
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
  </div>
</template>

<script>
import { mapActions,mapGetters } from "vuex";
import CompanyForm from "@/components/forms/CompanyForm.vue";
import fabCreateButton from "@/components/button/fabCreateButton";
import confirm from "@/components/dialog/confirm.vue"
import crudMixin from "@/mixins/crud-mixin"
export default {
  name:'company',
  mixins:[crudMixin],
  components:{
    CompanyForm, fabCreateButton,confirm
  },
  data(){
    return{
      loading:false,
      cardloader:false,
      search:'',
       headers: [
          {
            text: 'Name',
            align: 'start',
            sortable: false,
            value: 'name',
          },
          { text: 'Address', value: 'address',sortable: false },
          { text: 'Actions', value: 'actions', sortable: false }
        
        ],
        desserts: [
        {
          name: 'Lorem Ipsum',
          address: 'lorem ipsum dolor sit',
        },
        ]
    }
  },
    computed:{
      ...mapGetters("COMPANY", ["$company"]),
    },
  methods:{
    ...mapActions("COMPANY", ["fetchCompany","deleteCompany","addCompany","updateCompany"]),
    click(){
      this.dialog=true;
    },
    onInputCompanyDialog(dialog){
         if(!dialog){
        this.resetUpdate()
        this.resetCreate()
      }
    },
    async onFetchCompany() {
      this.loading = true;
      await this.fetchCompany();
      this.loading = false;
    },
    async handleAddCompany(company) {
      this.create.loading = true;
      console.log(company);
      // this.create.loading = true;
      let res = await this.addCompany(company);
      if (res.error) console.log(res.error);
      else {
        console.log(res.data);
      }
      this.resetCreate()
    },
   async  handleEditCompany(company){
      console.log(company);
      // this.create.loading = true;
      let res = await this.updateCompany(company);
      if (res.error) console.log(res.error);
      else {
        console.log(res.data);
      }
        this.resetUpdate()
    },
    async handleDeletecompany (id){
       this.deletee.loading = true;
      let res = await this.deleteCompany(id);
      if (res.error) 
      // await this.fetchEmployees();
      this.resetDelete();
    }
  },
  created(){
    this.onFetchCompany();
  }
}
</script>

<style>

</style>