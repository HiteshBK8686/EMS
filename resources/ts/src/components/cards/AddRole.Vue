<template>
        <!--begin::Action-->
        <button
            type="button"
            class="btn btn-primary er fs-6 px-8 py-4"
            data-bs-toggle="modal"
            :data-bs-target="`#${modalId}`"
          >
            {{ buttonText }}
          </button>
          <!--end::Action-->
  </template>
  
  <script lang="ts">
  import { defineComponent } from "vue";
  
  export default defineComponent({
    name: "modal-card",
    props: {
      buttonText: String,
      modalId: String,
    },
    components: {},
  });
  </script>
  