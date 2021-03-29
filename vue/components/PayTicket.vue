<template>
  <section class="text-center mx-auto d-inline-block">
    <!-- Extra stayed notification -->
    <p class="red--text" v-if="$store.state.ticket_details.extra_pay > 0">
      {{ extra_hour }}
    </p>

    <v-btn
      @click="sheet = !sheet"
      class="mx-auto"
      color="deep-purple lighten-2"
      text
      :disabled="$store.state.ticket_details.payment_status == 1"
      v-if="!checking && !$store.state.ticket_details.extra_pay"
    >
      Pay
    </v-btn>

    <v-btn
      class="mx-auto"
      color="deep-purple lighten-2"
      :disabled="$store.state.ticket_details.payment_status == 0"
      text
      @click="exit"
      v-if="!checking && !$store.state.ticket_details.extra_pay"
    >
      Exit
    </v-btn>
    <!-- Pay the extra stay -->
    <v-btn
      class="mx-auto"
      color="deep-purple lighten-2"
      text
      v-if="$store.state.ticket_details.extra_pay > 0"
      @click="payExtra"
    >
      Pay extra stay
    </v-btn>
    <span v-if="checking">Cheking...</span>

    <v-bottom-sheet v-model="sheet">
      <v-sheet class="text-center" height="200px">
        <section class="py-3">You have to pay your ticket before exit.</section>
        <p>Due Payment: ${{ $store.state.ticket_details.price }}</p>
        <p>
          Duration:
          {{ $store.state.ticket_details.length }}
          {{ $store.state.ticket_details.length == 1 ? "hour" : "hours" }}
        </p>
        <p v-if="msg" class="red--text font-weight-bold">{{ msg }}</p>
        <v-btn
          v-if="!loading"
          class="mt-6 font-weight-bold"
          text
          color="green"
          @click="payTicket"
        >
          Pay
        </v-btn>
        <v-btn
          v-if="!loading"
          class="mt-6 font-weight-bold"
          text
          color="red"
          @click="sheet = !sheet"
        >
          close
        </v-btn>
        <p v-if="loading">Please wait ...</p>
      </v-sheet>
    </v-bottom-sheet>
  </section>
</template>
<script>
import moment from "moment";
import axios from "axios";
export default {
  props: ["id"],
  data: () => ({
    sheet: false,
    loading: false,
    checking: false,
    msg: "",
  }),
  methods: {
    // Pay the ticket
    async payTicket() {
      this.loading = true;
      let pay = {
        id: this.id,
      };
      axios
        .post(process.env.api_url + "pay-ticket", pay)
        .then((res) => {
          this.loading = false;
          this.sheet = false;
          let ticket_number = localStorage.getItem("ticket_number");
          if (ticket_number) {
            this.$store.commit("getTicketDetails", ticket_number);
          }
        })
        .catch((err) => {
          this.loading = false;
          this.msg = err;
        });
    },
    // Exit the parking lot
    async exit() {
      let saved_extra_pay = this.$store.state.ticket_details.extra_pay;

      let extra_pay = "";

      if (!saved_extra_pay) {
        extra_pay = this.checkMaxStay();
      } else {
        extra_pay = saved_extra_pay;
      }

      this.checking = true;

      if (extra_pay == 0 || saved_extra_pay == 0) {
        let place = {
          id: this.id,
        };

        axios
          .post(process.env.api_url + "exit-parking-lot", place)
          .then((res) => {
            localStorage.removeItem("ticket_number");
            this.$router.push("/exit");
          })
          .catch((err) => {
            this.loading = false;
            this.msg = err;
          });
      } else {
        this.checking = false;
        let pay = {
          id: this.id,
          pay: extra_pay,
        };
        axios
          .post(process.env.api_url + "save-extra-pay", pay)
          .then((res) => {
            let ticket_number = localStorage.getItem("ticket_number");
            if (ticket_number) {
              this.$store.commit("getTicketDetails", ticket_number);
            }
          })
          .catch((err) => {
            console.log(err);
          });
      }
    },
    // Check maximum stay of user
    checkMaxStay() {
      // convert to utc
      let today = moment().utc();
      let ticket_date = moment(
        this.$store.state.ticket_details.created_at
      ).utc();
      // change format
      let startTime = moment(ticket_date, "DD-MM-YYYY hh:mm:ss");
      let endTime = moment(today, "DD-MM-YYYY hh:mm:ss");
      // duration
      let diff = endTime.diff(startTime, "hours");
      localStorage.setItem("extra_hour", diff);

      let saved_price = this.$store.state.ticket_details.price;

      if (diff <= 1) {
        return 0;
      } else if (diff > 1 && diff <= 3) {
        return 4.5 - saved_price;
      } else if (diff > 3 && diff <= 6) {
        return 6.75 - saved_price;
      } else if (diff > 6 && diff <= 24) {
        return 10.12 - saved_price;
      }
    },
    // Pay the extra stay
    payExtra() {
      let pay = {
        id: this.id,
        pay: 0,
      };
      axios
        .post(process.env.api_url + "save-extra-pay", pay)
        .then((res) => {
          let ticket_number = localStorage.getItem("ticket_number");
          if (ticket_number) {
            this.$store.commit("getTicketDetails", ticket_number);
          }
        })
        .catch((err) => {
          console.log(err);
        });
    },
  },
  computed: {
    extra_hour() {
      let diff = localStorage.getItem("extra_hour");
      return `You have stayed for an extra ${diff} hours. Please pay the remaining
      balance before leaving the parking lot.`;
    },
  },
};
</script>
