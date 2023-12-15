<template>
  <KTUpgradePlanModal />
  <KTCreateAppModal />
  <KTNewTargetModal />
  <KTViewUsersModal />
  <KTInviteFriendsModal />
  <RoleAdd />
  <RoleEdit />
</template>

<script lang="ts">
import { defineComponent } from "vue";
import KTUpgradePlanModal from "@/components/modals/general/UpgradePlanModal.vue";
import KTCreateAppModal from "@/components/modals/wizards/create-app-modal/CreateAppModal.vue";
import KTNewTargetModal from "@/components/modals/forms/NewTargetModal.vue";
import KTViewUsersModal from "@/components/modals/general/ViewUsersModal.vue";
import RoleAdd from "@/components/modals/forms/RoleAdd.vue";
import RoleEdit from "@/components/modals/forms/RoleEdit.vue";
import KTInviteFriendsModal from "@/components/modals/general/InviteFriendsModal.vue";

export default defineComponent({
  name: "global-modals",
  components: {
    KTUpgradePlanModal,
    KTCreateAppModal,
    RoleAdd,
    RoleEdit,
    KTNewTargetModal,
    KTViewUsersModal,
    KTInviteFriendsModal,
  },
});
</script>
