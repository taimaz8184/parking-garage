<template>
  <v-container class="mt-10">
    <v-row class="d-flex flex-column">
      <v-col cols="12" md="4" class="mx-auto">
        <h1 class="text-center">Register</h1>
        <client-only>
          <validation-observer ref="observer" v-slot="{ invalid }">
            <v-form @submit.prevent="saveData" class="mt-5">
              <v-container>
                <v-row>
                  <!-- Email -->
                  <v-col cols="12" md="12">
                    <validation-provider
                      v-slot="{ errors }"
                      name="Email"
                      rules="required|email"
                    >
                      <v-text-field
                        v-model="email"
                        label="E-mail *"
                        :error-messages="errors"
                        type="email"
                        required
                      ></v-text-field>
                    </validation-provider>
                  </v-col>
                  <!-- Password -->
                  <v-col cols="12" md="12">
                    <validation-provider
                      v-slot="{ errors }"
                      name="Password"
                      rules="required|minPass"
                      vid="confirmation"
                    >
                      <v-text-field
                        v-model="password"
                        label="Password *"
                        :error-messages="errors"
                        required
                        type="password"
                      ></v-text-field>
                    </validation-provider>
                  </v-col>
                  <!-- Confirm Password -->
                  <v-col cols="12" md="12">
                    <validation-provider
                      v-slot="{ errors }"
                      name="Confirm password"
                      rules="confirmed:confirmation|minPass|required"
                    >
                      <v-text-field
                        v-model="confirmation"
                        label="Confirm password *"
                        :error-messages="errors"
                        required
                        type="password"
                      ></v-text-field>
                    </validation-provider>
                  </v-col>
                  <v-col cols="12" md="12" align="center">
                    <v-btn
                      :disabled="invalid || loading"
                      :loading="loading"
                      color="success"
                      type="submit"
                      >Register</v-btn
                    >
                  </v-col>
                </v-row>
              </v-container>
            </v-form>
          </validation-observer>
        </client-only>
        <p class="text-center mt-10">
          <nuxt-link to="/" class="text-decoration-none caption">
            Back to parking lot
          </nuxt-link>
        </p></v-col
      >
      <v-col
        v-if="msg"
        cols="12"
        md="6"
        class="mx-auto text-center d-inline-block"
      >
        <v-alert :type="msg_type" v-html="msg" class="auth-alert"> </v-alert>
      </v-col>
    </v-row>
  </v-container>
</template>
<script>
import axios from "axios";
export default {
  layout: "guest",
  data() {
    return {
      loading: false,
      msg: "",
      msg_type: "",
      email: "",
      password: "",
      confirmation: "",
    };
  },
  methods: {
    async saveData() {
      this.loading = true;
      this.msg = "";
      this.msg_type = "";

      let data = {
        email: this.email,
        password: this.password,
      };
      axios
        .post(process.env.api_url + "register", data)
        .then((res) => {
          // Get validation errors
          if (res.data.errors) {
            Object.keys(res.data.errors).forEach((key) => {
              res.data.errors[key].forEach((element) => {
                this.msg += `
                <p>${element}</p>
                `;
              });
            });

            this.msg_type = "error";
            this.loading = false;
          }
          // Registration completed
          else {
            this.msg = `Your registration has been successfully completed`;
            this.msg_type = "success";
            this.loading = false;
            localStorage.setItem("registered", true);
            setTimeout(() => {
              this.$router.push("/");
            }, 3000);
          }
        })
        .catch((err) => {
          this.msg = err;
          this.msg_type = "error";
        });
    },
  },
};
</script>
