import { ref } from "vue";
import { defineStore } from "pinia";
import ApiService from "@/core/services/ApiService";
import JwtService from "@/core/services/JwtService";
import { log } from "console";

export interface Role {
    id:string;
    name: string;
    permission: object;
}

export const useRoleStore = defineStore("role", () => {
    const errors = ref({});
    const role = ref<Role>({} as Role);

    function setError(error: any) {
        errors.value = { ...error };
    }
    // function setSuccess(data: any) {
    //   message.value = data.message;
    // }

    // Add Role and Permission in the DB
    function rolePermissionAdd(roledata: Role){
        // console.log(roledata.value);
        return ApiService.post("/api/rolePermissionInsert", roledata.value)
            .then(({ data }) => {
                setError({});
            })
            .catch(({ response }) => {
                setError(response.data.errors);
            });        
    }
    // Update Role and Permission Module
    function rolePermissionEdit(roledata: Role){
        // console.log(roledata);
         return ApiService.post("/api/rolePermissionUpdate", roledata)
             .then(({ data }) => {
                 setError({});
             })
             .catch(({ response }) => {
                 setError(response.data.errors);
             });        
    }
    
    // Delete Role and Permission Module
    function rolePermissionDelete(roledata: Role){
        const roleId= { id : roledata };
       
        return ApiService.post("/api/rolePermissionDelete", roleId)
             .then(({ data }) => {
                 setError({});
             })
             .catch(({ response }) => {
                 setError(response.data.errors);
             });        
    }

    // Select data on Edit mode in role and Permission
    function selectedRolePermission(roledata: Role){
        //console.log(roledata);
        
        return ApiService.get("/api/selectedRolePermission/" + roledata)
            .then(({ data }) => {
                return data;
            })
            .catch(({ response }) => {
                setError(response.data.errors);
            });
    }
    // Get All the Permission
    function get_all_permission() {
        return ApiService.get("/api/getPermission")
            .then(({ data }) => {
               return data;
            })
            .catch(({ response }) => {
                setError(response.data.errors);
            });
    }
     // Get Role and Permission on Role Listing Page
    function allRolePermission(){
        return ApiService.get("/api/getRolePermission")
        .then(({ data }) => {
            return data;
         })
         .catch(({ response }) => {
             setError(response.data.errors);
         });
    }
    return {
        errors,
        role,
        get_all_permission,
        selectedRolePermission,
        rolePermissionAdd,
        rolePermissionEdit,
        rolePermissionDelete,
        allRolePermission,
    };
});
