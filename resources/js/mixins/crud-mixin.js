

const initialCreateUpdate = () => ({
    data: {},
    isValid: true,
    dialog: false,
    loading: false
});
const initialDelete = () => ({
    id: null,
    dialog: false,
    loading: false
});
const initialSearch = () => ({
    keyword: "",
    dialog: false,
    loading: false
});

export default {
    name: "CrudMixin",
    data: () => ({
        search: initialSearch(),
        deletee: initialDelete(),
        create: initialCreateUpdate(),
        update: initialCreateUpdate()
    }),
    computed: {
        cmDialog: {
            get() {
                return this.create.dialog || this.update.dialog;
            },
            set(v) {
                if (this.create.dialog) this.create.dialog = v;
                if (this.update.dialog) this.update.dialog = v;
                
            }
        }
    },
    methods: {
        initUpdate(data) {
            this.update.data = { ...data };
            this.update.dialog = true;
        },
        initDelete(id) {
            this.deletee.id = id;
            this.deletee.dialog = true;
        },
        initCreate() {
            this.create.dialog = true;
        },
        initReassign(data) {
            
        },
        initSearch() {
            this.search.dialog = true;
        },
        resetSearch() {
            this.search = initialSearch();
        },
        resetDelete() {
            this.deletee = initialDelete();
        },
        resetUpdate() {
            this.update = initialCreateUpdate();
        },
        resetCreate() {
            this.create = initialCreateUpdate();
        },
        enableSnackbar(type,msg) {
            if (type === 'success') {
                this.snackbar.text = msg;
                this.snackbar.color = "success";
                this.snackbar.action = true;
            }
            else {
                this.snackbar.text = msg;
                this.snackbar.color = "error";
                this.snackbar.action = true;
            }
        }
    }
};
