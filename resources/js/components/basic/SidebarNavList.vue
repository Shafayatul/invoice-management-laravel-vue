<template>
  <v-list :class="listClasses" nav dense :shaped="shaped">
    <v-list-item-group color="primary">
      <slot v-for="item in items" :route="item" name="route"></slot>
    </v-list-item-group>
  </v-list>
</template>

<script>
export default {
  name: "main-nav-list",
  props: {
    items: {
      type: Array,
      required: true,
      default: () => [],
    },
    collapsed: {
      type: Boolean,
      default: false,
    },
    br: {
      type: Boolean,
      default: false,
    },
    shaped: {
      type: Boolean,
      default: false,
    },
  },
  computed: {
    listClasses() {
      return {
        collapsed: this.collapsed,
      };
    },
  },
};
</script>

<style lang='scss' scoped>
.collapsed {
  width: 3.5rem;
  min-width: 3.5rem;
}
</style>