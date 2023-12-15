import { ref } from "vue";
import { defineStore } from "pinia";
import ApiService from "@/core/services/ApiService";
import JwtService from "@/core/services/JwtService";
import { log } from "console";

export interface User {
    name: string;
    email: string;
    password: string;
    api_token: string;
    token: string;
    password_confirmation: string;
    userdata: object;
}

export const useAuthStore = defineStore("auth", () => {
    const errors = ref({});
    const message = ref({});
    const user = ref<User>({} as User);
    const isAuthenticated = ref(!!JwtService.getToken());
    //console.log(JwtService.getToken());

    function setAuth(authUser: User) {
        //console.log(authUser.api_token);
        isAuthenticated.value = true;
        user.value = authUser;
        errors.value = {};
        JwtService.saveToken(authUser.api_token);
        // console.log(JwtService.getToken());
    }

    function setError(error: any) {
        errors.value = { ...error };
    }
    // function setSuccess(data: any) {
    //   message.value = data.message;
    // }

    function purgeAuth() {
        isAuthenticated.value = false;
        user.value = {} as User;
        errors.value = [];
        JwtService.destroyToken();
    }

    function login(credentials: User) {
        //  console.log(credentials);
        return ApiService.post("/api/login", credentials)
            .then(({ data }) => {
                setAuth(data);
                //console.log(data);
            })
            .catch(({ response }) => {
                setError(response.data.errors);
            });
    }

    function logout() {
        purgeAuth();
    }

    function register(credentials: User) {
        return ApiService.post("register", credentials)
            .then(({ data }) => {
                setAuth(data);
            })
            .catch(({ response }) => {
                setError(response.data.errors);
            });
    }

    function forgotPassword(email: string) {
        //console.log(email);
        return ApiService.post("/api/forgot-password", email)
            .then(({ data }) => {
                //console.log(data);
                if (!data.errors) {
                    return setError({});
                }

                setError(data.errors);
            })
            .catch(({ response }) => {
                // console.log(response);
                setError(response.data.errors);
            });
    }
    function checkResetToken(tokenData: object) {
        return ApiService.post("/api/checkResetToken", tokenData)

            .then(({ data }) => {})
            .catch(({ response }) => {
                setError(response.data.errors);
            });
    }
    function passwordReset(passwordToken: User) {
        return ApiService.post("/api/resetPassword", passwordToken)
            .then(({ data }) => {
                if (!data.errors) {
                    //console.log(data.data);
                    
                    // setAuth(data);
                    return setError({});
                }
                 // console.log(data.errors);
                  
                // setAuth(data);
                return setError(data.errors);
            })
            .catch(({ response }) => {
                setError(response.data.errors);
            });
    }

    function verifyAuth() {
        //console.log(JwtService.getToken());

        if (JwtService.getToken()) {
            ApiService.setHeader();
            ApiService.post("/api/verify_token", {
                api_token: JwtService.getToken(),
            })
                .then(({ data }) => {
                    setAuth(data);
                })
                .catch(({ response }) => {
                    setError(response.data.errors);
                    purgeAuth();
                });
        } else {
            purgeAuth();
        }
    }

    return {
        errors,
        user,
        isAuthenticated,
        login,
        logout,
        register,
        forgotPassword,
        checkResetToken,
        passwordReset,
       verifyAuth,
    };
});
