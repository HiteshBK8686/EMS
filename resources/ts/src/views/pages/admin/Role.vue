<template>
    <div class="card shadow-sm">
        <div class="card-header">
            <h3 class="card-title">User Role and Permissions &nbsp;
                <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal"
                    :data-bs-target="`#kt_modal_new_role`">
                    Add New
                </button>

            </h3>
            <div class="card-toolbar">
                <ol class="breadcrumb breadcrumb-line text-muted fs-6 fw-semibold">
                    <li class="breadcrumb-item">
                        <router-link to="/" class="text-muted text-hover-primary">Home</router-link>
                    </li>
                    <li class="breadcrumb-item  text-primary">User Role</li>
                </ol>
            </div>
        </div>
        <div class="card-body">

            <div class="row">
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-12">

                            <RoleAdd @save="onSave"></RoleAdd>
                        </div>

                    </div>

                    <div class="row">
                        <div class="col-md-12">

                            <Datatable :header="tableHeader" :data="tableData.data">
                                <template v-slot:id="{ row: data }">
                                    <span class=" text-capitalize">{{ data.id }}</span>
                                </template>
                                <template v-slot:name="{ row: data }">
                                    <span class=" text-capitalize">{{ data.name }}</span>
                                </template>
                                <!-- <template v-slot:permission="{ row: data }">
                                <li v-for="permissionName in data.permission" :key="permissionName.id" class="d-flex align-items-center text-capitalize py-2">
                                    <span class="bullet me-5"></span> {{ permissionName.name }}
                                </li> 
                                role-edit
                                <span style="margin-right:5px;" v-for="permissionName in data.permission" :key="permissionName.id" class="badge badge-info text-capitalize text-lg   ml-4">{{ permissionName.name }}</span>  
                            </template> -->
                                <template v-slot:action="{ row: data }">
                                    <!-- <button class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1">
                                        <KTIcon icon-name="switch" icon-class="fs-3" />
                                    </button> -->
                                    <button @click.prevent="$router.push({ name: 'role-edit', params: { id: data.id } })"
                                        class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1">
                                        <KTIcon icon-name="pencil" icon-class="fs-3" />
                                    </button>
                                    <button @click.prevent="deleteData(data.id)"
                                        class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1">
                                        <KTIcon icon-name="trash" icon-class="fs-3" />
                                    </button>
                                </template>

                            </Datatable>
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
import RoleAdd from "@/components/modals/forms/RoleAdd.vue";
import RoleEdit from "@/components/modals/forms/RoleEdit.vue";
import { useRoleStore, type Role } from "@/stores/role";
import Datatable from "@/components/kt-datatable/KTDataTable.vue";
import Swal from "sweetalert2/dist/sweetalert2.js";

interface RoleData {
    id: string;
}

export default defineComponent({
    name: "role",
    components: {
        RoleAdd,
        RoleEdit,
        Datatable
    },
    setup() {


        // Set Role Store for get reusable class
        const store = useRoleStore();
        const router = useRouter();

        // Set All Ref here
        const getRolePermission = ref([]);
        const loading = ref<boolean>(false);
        const targetData = ref<RoleData>({
            id: "",
        });



        // put that function which call on Mount Page inside OnMounted.
        onMounted(async () => {
            await allRolePermission();
        });
        // End Mounted


        // emit event
        const onSave = async () => {
            await allRolePermission();
        }
        // end Emit Event

        // Setup Data Table for fetch data on Listing.
        const tableHeader = ref([
            {
                columnName: "#",
                columnLabel: "id",
            },
            {
                columnName: "Role Title",
                columnLabel: "name",
            },
            // {
            // columnName: "Role Permission",
            // columnLabel: "permission",
            // },
            {
                columnName: "Action",
                columnLabel: "action",
            },
        ]);
        const tableData = getRolePermission;
        // End Data Table

        // Get All Role and their related Permission
        const allRolePermission = async () => {
            getRolePermission.value = await store.allRolePermission();
        }

        // Delete Data Function
        const deleteData = async (id) => {

            Swal.fire({
                title: "Delete Role and Permission ?",
                text: "Are you sure you want Delete Role and Permission ?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, Delete it!',
            }).then  ((result) => {
                if (result.isConfirmed) {

                    // Send Update Role Permission request
                    store.rolePermissionDelete(id);

                    const error = Object.values(store.errors);
                    if (error.length === 0) {
                        Swal.fire({
                            text: "Role and Permission successfully Deleted!",
                            icon: "success",
                            buttonsStyling: false,
                            confirmButtonText: "Ok, got it!",
                            heightAuto: false,
                            customClass: {
                                confirmButton: "btn fw-semobold btn-light-primary",
                            },
                        }).then(() => {
                            // Go to page after successfully login
                            
                            allRolePermission();
                            router.push({ name: "role" });

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

                }
            })
        };

        return {
            getRolePermission,
            tableHeader,
            tableData,
            deleteData,
            onSave
        };
    },
});
</script>
  