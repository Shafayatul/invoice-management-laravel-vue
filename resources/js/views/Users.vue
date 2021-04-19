<template>
  <div class="ml-3">
    <v-dialog
      v-model="cmDialog"
      @input="onInputUserDialog"
      :width="this.$vuetify.breakpoint.mdAndUp ? '30vw' : '80vw'"
    >
      <v-card>
        <v-card-text>
          <UserForm
            v-if="cmDialog"
            :isUpdate="update.dialog"
            :data="update.data"
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
        :items="desserts"
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
              <v-list-item dense link>
                <v-icon class="mr-2">mdi-delete</v-icon>
                Delete
              </v-list-item>
            </v-list>
          </v-menu>
        </template>
      </v-data-table>
    </v-card>
    <fabCreateButton @click="initCreate()" />
  </div>
</template>

<script>
import UserForm from "@/components/forms/UserForm.vue";
import fabCreateButton from "@/components/button/fabCreateButton";
import crudMixin from "@/mixins/crud-mixin"
export default {
  name:'Users',
  mixins:[crudMixin],
  components:{
    UserForm, fabCreateButton,
  },
  data(){
    return{
      cardloader:false,
      search:'',
       headers: [
          {
            text: 'Name',
            align: 'start',
            sortable: false,
            value: 'name',
          },
          { text: 'Email', value: 'email',sortable: false },
          { text: 'Role', value: 'role',sortable: false },
          { text: 'Company', value: 'company',sortable: false },
          { text: 'Actions', value: 'actions', sortable: false }
        
        ],
        desserts: [
        {
          name: 'Lorem Ipsum',
          email: 'lorem@gmail.com',
          role:'lorem',
          company:'ABCD',

        },
        ]
    }
  },
  methods:{
    click(){
      this.dialog=true;
    },
    onInputUserDialog(dialog){
      if(!dialog){
        this.resetUpdate()
        this.resetCreate()
      }
    }
  }
}
</script>

<style>

</style>