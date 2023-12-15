import { ref } from "vue";
import { defineStore } from "pinia";
import ApiService from "@/core/services/ApiService";
import JwtService from "@/core/services/JwtService";
import { log } from "console";

export interface User {
    roleTitle: string;
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
    function rolePermissionAdd(roledata: Role){
       // console.log(roledata.value);
        return ApiService.post("api/rolePermissionInsert", roledata.value)
            .then(({ data }) => {
                setError({});
            })
            .catch(({ response }) => {
                setError(response.data.errors);
            });        
    }
    function get_all_permission() {
        return ApiService.get("api/getPermission")
            .then(({ data }) => {
               return data;
            })
            .catch(({ response }) => {
                setError(response.data.errors);
            });
    }
    function allRolePermission(){
        return ApiService.get("api/getRolePermission")
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
        rolePermissionAdd,
        allRolePermission,
    };
});
