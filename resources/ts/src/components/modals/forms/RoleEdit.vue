<template>
  <!--begin::Modal - New Target-->
  <div class="modal fade" id="kt_modal_edit_role" ref="editTargetModalRef" tabindex="-1" aria-hidden="true">
    <!--begin::Modal dialog-->
    <div class="modal-dialog modal-dialog-centered mw-650px">
      <!--begin::Modal content-->
      <div class="modal-content rounded">
        <!--begin::Modal header-->
        <div class="modal-header pb-0 border-0 justify-content-end">
          <!--begin::Close-->
          <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
            <KTIcon icon-name="cross" icon-class="fs-1" />
          </div>
          <!--end::Close-->
        </div>
        <!--begin::Modal header-->

        <!--begin::Modal body-->
        <div class="modal-body scroll-y px-10 px-lg-15 pt-0 pb-15">
          <!--begin:Form-->
          <el-form id="kt_modal_new_target_form" @submit.prevent="submit()" :model="targetData" :rules="rules"
            ref="formRef" class="form">
            <!--begin::Heading-->
            <div class="mb-13 text-center">
              <!--begin::Title-->
              <h1 class="mb-3">Edit Role and Permissions  {{ roleId }}</h1>
              <!--end::Title-->
            </div>
            <!--end::Heading-->

            <!--begin::Input group-->
            <div class="d-flex flex-column mb-4 fv-row">
              <!--begin::Label-->
              <label class="d-flex align-items-center fs-6 fw-semobold mb-2">
                <span class="required">Role Title</span>
              </label>
              <!--end::Label-->

              <el-form-item prop="roleTitle">
                <el-input v-model="targetData.roleTitle" placeholder="Enter Role Title" name="roleTitle"></el-input>
              </el-form-item>
            </div>
            <!--end::Input group-->
            <!--begin::Checkboxes-->
            <!-- <div class="mb-4">
                  <label class="form-check form-check-custom form-check-solid me-10">
                    <input
                      class="form-check-input h-20px w-20px"
                      type="checkbox"
                      @click='checkAll()'
                      v-model='isCheckAll'                   
                    />
                      <span class="form-check-label fw-semobold text-capitalize"> Select All </span>
                  </label>
                </div> -->
            <div class="mb-4">
                <!--begin::Label-->
                <label class="d-flex align-items-center fs-6 fw-semobold mb-2">
                  <span class="required">Role Permissions</span>
                </label>
                <!--end::Label-->
            </div>
            <div class="d-flex align-items-center mb-8">
              <!--begin::Checkbox-->
             
              <div v-for="permission in get_permission" :key="permission.id">
                <label class="form-check form-check-custom form-check-solid me-10">
                  <input class="form-check-input h-20px w-20px" type="checkbox" v-model="targetData.rolePermission"
                    :value="permission.id" />
                    <span class="form-check-label fw-semobold text-capitalize"> {{ permission.name }} </span>
                </label>
              </div>

              <!--end::Checkbox-->
            </div>
            <!--end::Checkboxes-->
            <!--begin::Actions-->
            <div class="text-center pt-4">
              <button type="reset" id="kt_modal_new_target_cancel" class="btn btn-light me-3">
                Cancel
              </button>

              <!--begin::Button-->
              <button :data-kt-indicator="loading ? 'on' : null" class="btn btn-lg btn-primary" type="submit">
                <span v-if="!loading" class="indicator-label">
                  Submit
                  <KTIcon icon-name="arrow-right" icon-class="fs-3 ms-2 me-0" />
                </span>
                <span v-if="loading" class="indicator-progress">
                  Please wait...
                  <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                </span>
              </button>
              <!--end::Button-->
            </div>
            <!--end::Actions-->
          </el-form>
          <!--end:Form-->
        </div>
        <!--end::Modal body-->
      </div>
      <!--end::Modal content-->
    </div>
    <!--end::Modal dialog-->
  </div>
  <!--end::Modal - New Target-->
</template>
  
<style lang="scss">
.el-select {
  width: 100%;
}

.el-date-editor.el-input,
.el-date-editor.el-input__inner {
  width: 100%;
}
</style>
  
<script lang="ts">
import { getAssetPath } from "@/core/helpers/assets";
import { defineComponent, nextTick, onMounted, ref,defineEmits } from "vue";
import { hideModal } from "@/core/helpers/dom";
import { useRoleStore, type Role } from "@/stores/role";
import { useRouter } from "vue-router";
import Swal from "sweetalert2/dist/sweetalert2.js";
import { string } from "yup/lib/locale";

interface NewRoleData { 
  roleTitle: string;
  rolePermission: Array<string>;
}

export default defineComponent({
  name: "new-target-modal",
  components: {},
  props: {
    modelValue: {
      type: String,
    },
    roleId: {
      type:String,
    },
  },
  // props:{
  //  modelValue: String
  // },
  
  setup(props,ctx) {

    // Set Role Store for get reusable class
    const store = useRoleStore();
    const router = useRouter();

    const formRef = ref<null | HTMLFormElement>(null);
    const editTargetModalRef = ref<null | HTMLElement>(null);
    const loading = ref<boolean>(false);
    const targetData = ref<NewRoleData>({
      roleTitle: "",
      rolePermission: [],
    });
   
    const emit = defineEmits(['save']);
    const get_permission = ref([]);
    const rules = ref({
      roleTitle: [
        {
          required: true,
          message: "Please enter Role Title",
          trigger: "blur",
        },
      ],
      rolePermission: [
        {
          required: true,
          message: "Please select Permissions",
          trigger: "checked",
        },
      ],
    });
    
    const resetData = () => {
      targetData.value={
        roleTitle: "",
        rolePermission: [],
      }
    }
    const submit = async (values: any) => {
      // Submit Form Loading start here 
      loading.value = true;

      values = targetData;
      const getRolePermission = ref([]);
      // Send login request
      await store.rolePermissionEdit(values);

      const error = Object.values(store.errors);
      if (error.length === 0) {
        Swal.fire({
          text: "You have successfully logged in!",
          icon: "success",
          buttonsStyling: false,
          confirmButtonText: "Ok, got it!",
          heightAuto: false,
          customClass: {
            confirmButton: "btn fw-semobold btn-light-primary",
          },
        }).then(() => {
          // Go to page after successfully login
          
          hideModal(editTargetModalRef.value);   
          ctx.emit('save');      
          loading.value = false;
          router.push({ name: "role" });
          return resetData();;
        });
      } else {
        Swal.fire({
          text: error,
          icon: "error",
          buttonsStyling: false,
          confirmButtonText: "Try again!",
          heightAuto: false,
          customClass: {
            confirmButton: "btn fw-semobold btn-light-danger",
          },
        }).then(() => {
          store.errors = {};
        });
      }
      // eslint-disable-next-line
      formRef.value!.disabled = false;
    };
    const all_permission = async () => {
      get_permission.value = await store.get_all_permission();
    }
    onMounted(async () => {
      await all_permission();
      
      
    });
    return {
      targetData,
      get_permission,
      submit,
      loading,
      formRef,
      rules,
      editTargetModalRef,
      getAssetPath,
    };
  },
});
</script> 
<style lang="scss">
.override-styles {
  z-index: 99999 !important;
  pointer-events: initial;
}
</style>
  