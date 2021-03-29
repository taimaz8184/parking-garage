<template>
  <v-container class="text-center pt-10">
    <!-- Registration -->
    <v-row v-if="!registered">
      <v-col cols="12" md="7" class="mx-auto my-7">
        <p>
          You can
          <nuxt-link to="/register"> register</nuxt-link>
          in the site and be notified if there is an available parking lot.
        </p>
      </v-col>
    </v-row>
    <!-- Get all parking places -->
    <v-btn
      v-if="places.length == 0 && !$store.state.ticket_issued"
      @click="getPlaces"
      color="primary"
      >To select a parking space, click</v-btn
    >
    <!-- show places -->
    <v-row
      v-if="!$store.state.ticket_issued > 0 && !$store.state.reserve_place"
    >
      <v-col cols="12">
        <v-tooltip bottom v-for="place in places" :key="'place' + place.id">
          <template v-slot:activator="{ on, attrs }">
            <v-btn
              width="200"
              class="ma-4 text-capitalize"
              v-bind="attrs"
              v-on="on"
              color="success"
              :disabled="place.reserved == 1"
              @click="
                ($store.state.reserve_place = true),
                  ($store.state.selected_place = place.id)
              "
            >
              <span class="mr-2">Parking Lot</span>
              <span>No. {{ place.id }}</span>
            </v-btn>
          </template>
          <span>Available</span>
        </v-tooltip>
      </v-col>
    </v-row>
    <reservation-details v-if="$store.state.reserve_place" />
    <!-- Ticket -->
    <v-row v-if="$store.state.ticket_issued">
      <v-col cols="12" md="5" class="mx-auto">
        <ticket />
      </v-col>
    </v-row>
  </v-container>
</template>
<script>
import axios from "axios";
import ReservationDetails from "./ReservationDetails.vue";
export default {
  components: { ReservationDetails },
  data() {
    return {
      places: [],
      registered: true,
    };
  },
  async mounted() {
    this.registered = localStorage.getItem("registered");
    // Get ticket details
    let ticket_number = localStorage.getItem("ticket_number");
    if (ticket_number) {
      this.$store.commit("getTicketDetails", ticket_number);
    }
  },
  methods: {
    // Get all parking places
    async getPlaces() {
      axios
        .get(process.env.api_url + "get-parking-places")
        .then((res) => {
          this.places = res.data;
        })
        .catch((err) => {
          console.log(err);
        });
    },
  },
};
</script>
