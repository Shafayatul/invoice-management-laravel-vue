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
                :items="$users"
                :search="search"
            >
                <template v-slot:item.actions="{ item }">
                    <v-menu down left nudge-left="7rem">
                        <template v-slot:activator="{ on, attrs }">
                            <v-btn
                                icon
                                color="primary"
                                dark
                                v-bind="attrs"
                                v-on="on"
                            >
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
                <template v-slot:item.company="{ item }">
                    {{item.name}}
                </template>
            </v-data-table>
        </v-card>
        <fabCreateButton @click="initCreate()" />
        <CircleLoader
            center
            v-if="loading"
            size="84"
            speed="1"
            border-width="3"
        />
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
          
            @no="resetDelete"
        />
          <!-- @yes="handleDeletecompany" -->
    </div>
</template>

<script>
import { mapActions, mapGetters } from "vuex";
import UserForm from "@/components/forms/UserForm.vue";
import fabCreateButton from "@/components/button/fabCreateButton";
import crudMixin from "@/mixins/crud-mixin";
import confirm from "@/components/dialog/confirm.vue";
import CircleLoader from "@/components/customs/CircleLoader";
export default {
    name: "Users",
    mixins: [crudMixin],
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
            search: "",
            snackbar: {
                action: false,
                text: "",
                color: "success",
                timeout: "3000"
            },
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
                { text: "Actions", value: "actions", sortable: false }
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
        ...mapGetters("USERS", ["$users"])
    },
    methods: {
        ...mapActions("USERS", [
            "fetchUsers",
            "deleteUser",
            "addUser",
            "updateUsers"
        ]),
        click() {
            this.dialog = true;
        },
        onInputUserDialog(dialog) {
            if (!dialog) {
                this.resetUpdate();
                this.resetCreate();
            }
        },
        async onFetchUsers() {
            this.tableLoader = true;
            await this.fetchUsers();
            this.tableLoader = false;
        }
    },
    created() {
        this.onFetchUsers();
    }
};
</script>

<style></style>
