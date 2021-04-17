<template>
  <transition name="route-transition" mode="out-in">
    <slot></slot>
  </transition>
</template>
<script>
export default {
  name: "RouterWrapper",
};
</script>

<style>
.route-transition-enter,
.route-transition-leave-to {
  opacity: 0;
  transform: translateY(-12px);
}
.route-transition-enter-active,
.route-transition-leave-active {
  transition: all 250ms ease;
}
.route-transition-enter-to,
.route-transition-leave {
  opacity: 1;
  transform: translateY(0);
}
</style>