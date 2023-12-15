<template>
    <!--begin::Wrapper-->
    <div class="w-lg-500px p-10">
      <!--begin::Form-->
      <VForm
        class="form w-100 fv-plugins-bootstrap5 fv-plugins-framework"
        novalidate
        @submit="onSubmitReset"
        id="kt_login_signup_form"
        :validation-schema="registration"
      >
        <!--begin::Heading-->
        <div class="mb-10 text-center">
          <!--begin::Title-->
          <h1 class="text-dark mb-3">Password Reset</h1>
          <!--end::Title-->
  
          <!--begin::Link-->
          <div class="text-gray-400 fw-semobold fs-4">
           Your New Password Update ?
  
       
          </div>
          <!--end::Link-->
        </div>
        <!--end::Heading-->
  
        <!--begin::Input group-->
        <div class="row fv-row mb-7">
        
  
            
  
        <!--begin::Input group-->
        <div class="mb-10 fv-row" data-kt-password-meter="true">

            <Field
                class="form-control form-control-lg form-control-solid"
                type="hidden"
                v-model="this.$router.currentRoute.value.query.token"
                name="token"
              />
          <Field
            class="form-control form-control-lg form-control-solid mb-2"
            type="text"
            v-model="this.$router.currentRoute.value.query.email"
            name="email" readonly
          />
          <!--begin::Wrapper-->
          <div class="mb-1">
            <!--begin::Label-->
            <label class="form-label fw-bold text-dark fs-6"> Password </label>
            <!--end::Label-->
  
            <!--begin::Input wrapper-->
            <div class="position-relative mb-3">
              <Field
                class="form-control form-control-lg form-control-solid"
                type="password"
                placeholder=""
                name="password"
                autocomplete="off"
              />
              <div class="fv-plugins-message-container">
                <div class="fv-help-block">
                  <ErrorMessage name="password" />
                </div>
              </div>
            </div>
            <!--end::Input wrapper-->
            <!--begin::Meter-->
            <div
              class="d-flex align-items-center mb-3"
              data-kt-password-meter-control="highlight"
            >
              <div
                class="flex-grow-1 bg-secondary bg-active-success rounded h-5px me-2"
              ></div>
              <div
                class="flex-grow-1 bg-secondary bg-active-success rounded h-5px me-2"
              ></div>
              <div
                class="flex-grow-1 bg-secondary bg-active-success rounded h-5px me-2"
              ></div>
              <div
                class="flex-grow-1 bg-secondary bg-active-success rounded h-5px"
              ></div>
            </div>
            <!--end::Meter-->
          </div>
          <!--end::Wrapper-->
          <!--begin::Hint-->
          <div class="text-muted">
            Use 8 or more characters with a mix of letters, numbers & symbols.
          </div>
          <!--end::Hint-->
        </div>
        <!--end::Input group--->
  
        <!--begin::Input group-->
        <div class="fv-row mb-5">
          <label class="form-label fw-bold text-dark fs-6"
            >Confirm Password</label
          >
          <Field
            class="form-control form-control-lg form-control-solid"
            type="password"
            placeholder=""
            name="password_confirmation"
            autocomplete="off"
          />
          <div class="fv-plugins-message-container">
            <div class="fv-help-block">
              <ErrorMessage name="password_confirmation" />
            </div>
          </div>
        </div>
        <!--end::Input group-->
  
      
  
        <!--begin::Actions-->
        <div class="text-center">
          <button
            id="kt_sign_up_submit"
            ref="submitButton"
            type="submit"
            class="btn btn-lg btn-primary"
          >
            <span class="indicator-label"> Submit </span>
            <span class="indicator-progress">
              Please wait...
              <span
                class="spinner-border spinner-border-sm align-middle ms-2"
              ></span>
            </span>
          </button>
        </div>
        </div>
        <!--end::Actions-->
      </VForm>
      <!--end::Form-->
    </div>
    <!--end::Wrapper-->
  </template>
  
  <script lang="ts">
  import { getAssetPath } from "@/core/helpers/assets";
  import { defineComponent, nextTick, onMounted, ref } from "vue";
  import { ErrorMessage, Field, Form as VForm } from "vee-validate";
  import * as Yup from "yup";
  import { useAuthStore, type User } from "@/stores/auth";
  import { useRouter } from "vue-router";
  import { PasswordMeterComponent } from "@/assets/ts/components";
  import Swal from "sweetalert2/dist/sweetalert2.js";
import { link } from "fs";
  
  export default defineComponent({
    name: "sign-up",
    components: {
      Field,
      VForm,
      ErrorMessage,
    },
    setup() {
      const store = useAuthStore();
      const router = useRouter();
  
      const submitButton = ref<HTMLButtonElement | null>(null);
  
      const registration = Yup.object().shape({
        password: Yup.string().required().label("Password"),
        password_confirmation: Yup.string()
          .required()
          .oneOf([Yup.ref("password"), null], "Both Passwords must be Same.")
          .label("Password Confirmation"),
      });
      
      const password_reset_link = async () => {
        
        let token= router.currentRoute.value.query.token;
        let email = router.currentRoute.value.query.email;
        // const tokenValue ={
        //   'email':email
        // };
        
        //  // Send Password request
        // await store.checkResetToken(tokenValue);
        if(token == ' ' && email == ''){
            router.push({ name: "404" });
        }
      }

      onMounted(() => {
        nextTick(() => {
          PasswordMeterComponent.bootstrap();
          password_reset_link();
        });
      });
      


      const onSubmitReset = async (values: any) => {
       
        
       // console.log(values);
        values = values as User;
  
        // Clear existing errors
        store.logout();
  
        // eslint-disable-next-line
        submitButton.value!.disabled = true;
  
        // Activate indicator
        submitButton.value?.setAttribute("data-kt-indicator", "on");
  
        // Send Password request
        await store.passwordReset(values);
  
        const error = Object.values(store.errors);
  
        if (error.length === 0) {
          Swal.fire({
            text: "Password Reset Successfully, Kindly Login!",
            icon: "success",
            buttonsStyling: false,
            confirmButtonText: "Ok, got it!",
            heightAuto: false,
            customClass: {
              confirmButton: "btn fw-semobold btn-light-primary",
            },
          }).then(function () {
            // Go to page after successfully login
            router.push({ name: "sign-in" });
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
          });
        }
  
        submitButton.value?.removeAttribute("data-kt-indicator");
        // eslint-disable-next-line
        submitButton.value!.disabled = false;
      };
  
      return {
        registration,
        onSubmitReset,
        submitButton,
        getAssetPath,
      };
    },
  });
  </script>
  