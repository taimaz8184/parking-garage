<template>
  <v-row>
    <v-col cols="12" md="5" class="mx-auto">
      <v-card class="pa-9">
        <p class="info--text font-weight-bold mb-10">
          You have selected parking lot No.
          {{ $store.state.selected_place }}
        </p>
        <v-text-field
          label="Please type your vehicle plate"
          :rules="rules"
          hide-details="auto"
          v-model="$store.state.vehicle_plate"
        ></v-text-field>
        <p class="text-left mt-15">Please select the length of your stay:</p>
        <section>
          <v-btn
            v-for="item in length_options"
            :key="item.value"
            small
            @click="$store.state.length = item.value"
            :class="[
              $store.state.length == item.value ? 'info' : '',
              'mr-3 mb-3',
            ]"
            >{{ item.value }} {{ item.value == 1 ? "hour" : "hours" }}</v-btn
          >
        </section>
        <v-btn
          @click="reserve"
          color="success"
          class="reserve-btn"
          :disabled="$store.state.vehicle_plate.length < 3"
          >Reserve</v-btn
        >
      </v-card>
    </v-col>
  </v-row>
</template>

<script>
import axios from "axios";
export default {
  data() {
    return {
      rules: [
        (value) => !!value || "Required.",
        (value) => (value && value.length >= 3) || "Min 3 characters",
      ],
      length_options: [
        { value: "1" },
        { value: "3" },
        { value: "6" },
        { value: "24" },
      ],
    };
  },
  methods: {
    // Reserve parking place
    async reserve() {
      if (this.$store.state.vehicle_plate.length >= 3) {
        // Calculate price
        switch (this.$store.state.length) {
          case "1":
            this.$store.state.price = 3;
            break;
          case "3":
            this.$store.state.price = 4.5;
            break;
          case "6":
            this.$store.state.price = 6.75;
            break;
          case "24":
            this.$store.state.price = 10.12;
            break;
          default:
            this.$store.state.price = 3;
            break;
        }
        // Send data to api
        let details = {
          id: this.$store.state.selected_place,
          vehicle_plate: this.$store.state.vehicle_plate,
          length: this.$store.state.length,
          price: this.$store.state.price,
        };
        axios
          .post(process.env.api_url + "reserve-place", details)
          .then((res) => {
            if (res.data) {
              localStorage.setItem("ticket_number", res.data);
              this.$store.state.ticket_number = res.data;
              this.$store.state.ticket_issued = true;
              this.$store.state.reserve_place = false;
              this.$store.commit("getTicketDetails", res.data);
            }
          })
          .catch((err) => {
            console.log(err);
          });
      }
    },
  },
};
</script>

<style scoped>
.reserve-btn {
  margin-top: 8rem;
}
</style>
