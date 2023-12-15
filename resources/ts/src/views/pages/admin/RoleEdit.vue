<template>
  <div class="card shadow-sm">
    <div class="card-header">
      <h3 class="card-title">Edit User Role and Permissions &nbsp;

      </h3>
      <div class="card-toolbar">
        <ol class="breadcrumb breadcrumb-line text-muted fs-6 fw-semibold">
          <li class="breadcrumb-item">
            <router-link to="/" class="text-muted text-hover-primary">Home</router-link>
          </li>
          <li class="breadcrumb-item">
            <router-link to="/role" class="text-muted text-hover-primary">User Role and Permission</router-link>
          </li>
          <li class="breadcrumb-item  text-primary">Edit User Role and Permission</li>
        </ol>
      </div>
    </div>
    <div class="card-body">
      <div class="row">
        <div class="col-md-12">
          <div class="row">
            <div class="col-md-10 offset-1">
              <!--begin::Form-->
              <el-form id="kt_account_profile_details_form" class="form" novalidate :model="targetData"
                @submit.prevent="submit()" :validation-schema="profileDetailsValidator">
                <!--begin::Card body-->
                <div class="card-body border-top p-9">
                  <!--begin::Input group-->
                  <div class="row mb-6">
                    <!--begin::Label-->
                    <label class="col-lg-4 col-form-label required fw-semobold fs-6">Role Title</label>
                    <!--end::Label-->

                    <!--begin::Col-->
                    <div class="col-lg-8">
                      <!--begin::Row-->
                      <div class="row">
                        <!--begin::Col-->
                        <div class="col-lg-12">

                          <el-form-item prop="name">
                            <el-input v-model="targetData.name" placeholder="Enter Role Title"></el-input>
                          </el-form-item>
                          <el-form-item prop="id">
                            <el-input type="hidden" v-model="targetData.id"></el-input>
                          </el-form-item>
                        </div>
                        <!--end::Col-->
                      </div>
                      <!--end::Row-->
                    </div>
                    <!--end::Col-->
                  </div>
                  <!--end::Input group-->
                  <!--begin::Input group-->
                  <div class="row mb-6">
                    <!--begin::Label-->
                    <label class="col-lg-4 col-form-label fw-semobold fs-6">Role's Permissions</label>
                    <!--end::Label-->
                    <!--begin::Col-->
                    <div class="col-lg-8 fv-row">
                      <!--begin::Options-->
                      <div class="d-flex align-items-center mt-3">
                        <!-- <div v-for="permission in get_permission" :key="permission.id">
                          <label class="form-check form-check-custom form-check-solid me-10">
                            <input class="form-check-input h-20px w-20px" type="checkbox"
                              v-model="targetData.permission" :value="permission.id"   />
                            <span class="form-check-label fw-semobold text-capitalize"> {{ permission.name }} </span>
                          </label>
                        </div> -->

                        <div v-for="permission in get_permission" :key="permission.id">
                          <label class="form-check form-check-custom form-check-solid me-10">
                            <input name="permission[]" class="form-check-input h-20px w-20px" type="checkbox"
                              v-model="targetData.permission" :value="permission.id" />
                            <span class="form-check-label fw-semobold text-capitalize"> {{ permission.name }} </span>
                          </label>
                        </div>
                      </div>
                      <!--end::Options-->
                    </div>
                    <!--end::Col-->
                  </div>
                  <!--end::Input group-->
                </div>
                <!--end::Card body-->
                <!--begin::Actions-->
                <div class="card-footer d-flex justify-content-end py-6 px-9">
                  <button type="submit" id="kt_account_profile_details_submit" ref="submitButton1"
                    class="btn btn-primary">
                    <span class="indicator-label"> Save Changes </span>
                    <span class="indicator-progress">
                      Please wait...
                      <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                    </span>
                  </button>
                </div>
                <!--end::Actions-->
              </el-form>
              <!--end::Form-->
            </div>
          </div>
        </div>
      </div>

    </div>

  </div>
</template>
  
<script lang="ts">
// import { getAssetPath } from "@/core/helpers/assets";
import { defineComponent, ref, onMounted } from "vue";
import { useRouter } from "vue-router";
import { useRoleStore, type Role } from "@/stores/role";
import Swal from "sweetalert2/dist/sweetalert2.js";

interface NewRoleData {
  id: string;
  name: string;
  permission: Array<string>;
}

export default defineComponent({
  name: "role",
  components: {
  },
  setup() {
    // Set Role Store for get reusable class
    const store = useRoleStore();
    const router = useRouter();

    // Set All Ref inside here
    const get_newpermission = ref([]);
    const get_permission = ref([]);
    const selRolePermission = ref([]);
    const formRef = ref<null | HTMLFormElement>(null);
    const loading = ref<boolean>(false);
    const targetData = ref<NewRoleData>({
      id: "",
      name: "",
      permission: [],
    });
    const rules = ref({
      name: [
        {
          required: true,
          message: "Please enter Role Title",
          trigger: "blur",
        },
      ],
      permission: [
        {
          required: true,
          message: "Please select Permissions",

        },
      ],
    });
    onMounted(async () => {
      await all_permission();
      await selectedRolePermission();

    });

    // Begin All Functions here..
    const all_permission = async () => {
      get_permission.value = await store.get_all_permission();
      //console.log(get_permission);
    }
    const selectedRolePermission = async () => {

      var data = await store.selectedRolePermission(router.currentRoute.value.params.id);
      //console.log(data);
      targetData.value.name = data.name;
      targetData.value.id = data.id;
      targetData.value.permission = data.permissions.map((permission) => (permission.id));

    }
    const submit = async () => {
      // Submit Form Loading start here 
      loading.value = true;
      var values = targetData.value;
      //console.log(values);
      const getRolePermission = ref([]);

      // Send Update Role Permission request
      await store.rolePermissionEdit(values);

      const error = Object.values(store.errors);
      if (error.length === 0) {
        Swal.fire({
          text: "Role and Permission successfully Updated!",
          icon: "success",
          buttonsStyling: false,
          confirmButtonText: "Ok, got it!",
          heightAuto: false,
          customClass: {
            confirmButton: "btn fw-semobold btn-light-primary",
          },
        }).then(() => {
          // Go to page after successfully login



          loading.value = false;
          //router.push({ name: "role" });

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
      // formRef.value!.disabled = false;
    };

    return {
      targetData,
      selectedRolePermission,
      selRolePermission,
      get_permission,
      get_newpermission,
      loading,
      formRef,
      rules,
      submit
    };
  },
});
</script>
  