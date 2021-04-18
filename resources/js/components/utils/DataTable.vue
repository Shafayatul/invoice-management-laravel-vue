<template>
  <v-card flat class="transparent">
    <v-card-title class="mb-md-5">
      {{ title }}
      <v-spacer></v-spacer>
      <v-select
        dense
        outlined
        hide-details
        class="mr-md-3"
        v-model="itemsPP"
        label="Items Per Page"
        style="max-width: 150px"
        :items="[5, 10, 15, 20, 50, 'ALL']"
      />
      <v-text-field
        dense
        outlined
        hide-details
        label="Filter"
        v-model="searchKeyModel"
        class="filter mt-md-0 mt-3"
        append-icon="mdi-search"
      ></v-text-field>
    </v-card-title>
    <v-card-text>
      <v-data-table
        single-expand
        hide-default-footer
        :items="items"
        :headers="headers"
        :loading="loading"
        :expanded.sync="mExpanded"
        :show-expand="expanded"
        :items-per-page="options.itemsPerPage"
        item-key="id"
        class="pt-3 pb-5 transparent"
      >
        <template v-slot:[`item.name`]="{ item }">
          <div class="d-flex align-center">
            <v-avatar color="grey" class="white--text" size="24">{{
              item.name[0]
            }}</v-avatar>
            <span class="ml-3" style="white-space: nowrap">{{
              item.name
            }}</span>
          </div>
        </template>
        <template v-slot:[`item.role`]="{ item }">
          <v-chip
            small
            class="white--text elevation-1 capitalize"
            :color="roleColor || 'primary'"
            >{{ item.role }}</v-chip
          >
        </template>
        <template v-slot:[`item.actions`]="{ item }">
          <v-menu bottom left transition="slide-x-transition">
            <template v-slot:activator="{ on, attrs }">
              <v-btn dark icon v-bind="attrs" v-on="on" color="primary">
                <v-icon>mdi-dots-vertical</v-icon>
              </v-btn>
            </template>

            <v-list class="bg-sec">
              <v-list-item
                link
                class="pl-5 pr-8"
                @click="$emit('update', item)"
              >
                <v-list-item-title>
                  <v-icon color="primary" size="18" class="mr-2"
                    >mdi-pencil</v-icon
                  >
                  <span class="body-2">Update</span>
                </v-list-item-title>
              </v-list-item>
              <v-list-item
                link
                class="pl-5 pr-8"
                @click="$emit('delete', item.id)"
              >
                <v-list-item-title>
                  <v-icon color="primary" size="18" class="mr-2"
                    >mdi-trash-can</v-icon
                  >
                  <span class="body-2">Delete</span>
                </v-list-item-title>
              </v-list-item>
            </v-list>
          </v-menu>
        </template>
        <template v-slot:footer>
          <v-pagination v-model="pageNo" class="mt-5" :length="pageCount" />
        </template>
        <template v-for="slot in slots" v-slot:[slot]="{ item }">
          <slot :name="slot.split('.').pop()" :item="item" />
        </template>
        <template v-if="expanded" v-slot:expanded-item="{ headers, item }">
          <td :colspan="headers.length" class="px-md-3">
            <slot name="expanded" :item="item" />
          </td>
        </template>
      </v-data-table>
    </v-card-text>
    
  </v-card>
</template>

<script>
export default {
  name: "DataTable",
  props: {
    title: {
      type: String,
      default: "Table Title",
    },
    items: {
      type: Array,
      required: true,
    },
    headers: {
      type: Array,
      required: true,
    },
    roleColor: String,
    options: Object,
    loading: {
      type: Boolean,
      default: false,
    },
    slots: {
      type: Array,
      default: () => [],
    },
    expanded: {
      type: Boolean,
      default: false,
    },
  },
  data: () => ({
    st: "status",
    mExpanded: [],
    searchTimeout: null,
    searchKey: "",
  }),
  computed: {
    searchKeyModel: {
      get() {
        return this.searchKey;
      },
      set(key) {
        this.searchKey = key;
        if (key.length % 3 === 0) this.$emit("keyword", key);
      },
    },
    itemsPP: {
      get() {
        let { totalPage, perPage } = this.options;
        return totalPage !== perPage ? this.options.perPage : "ALL";
      },
      set(v) {
        return this.$emit("options", {
          ...this.options,
          perPage: v,
        });
      },
    },
    pageNo: {
      get() {
        return this.options.currentPage;
      },
      set(v) {
        return this.$emit("options", {
          ...this.options,
          currentPage: v,
        });
      },
    },
    pageCount() {
      let { totalPage, perPage } = this.options;
      return Math.ceil(totalPage / perPage);
    },
  },
};
</script>
<style lang="scss" scoped>
.filter {
  max-width: 350px;
//   @include on(mobile) {
//     max-width: 100%;
//   }
}
</style>
