<template>
  <v-card class="mx-auto mb-12">
    <v-sheet color="info" class="white--text">
      <v-card-title class="text-center d-inline-block text-h3"
        >Parking Ticket</v-card-title
      >
    </v-sheet>

    <v-card-text>
      <v-simple-table>
        <template v-slot:default>
          <tbody class="text-left">
            <tr>
              <td>Ticket No:</td>
              <td>#{{ $store.state.ticket_details.ticket_number }}</td>
            </tr>
            <tr>
              <td>Issued at:</td>
              <td>{{ convertDate($store.state.ticket_details.created_at) }}</td>
            </tr>
            <tr>
              <td>Vehicle Plate:</td>
              <td>
                {{ $store.state.ticket_details.vehicle_plate }}
              </td>
            </tr>
            <tr>
              <td>Parking Lot:</td>
              <td>
                {{ $store.state.ticket_details.parking_lot_id }}
              </td>
            </tr>
            <tr>
              <td>Payment Status:</td>
              <td>
                <span
                  v-if="!$store.state.ticket_details.payment_status"
                  class="red--text font-weight-bold"
                >
                  Not paid</span
                >
                <span v-else class="green--text font-weight-bold"> Paid</span>
              </td>
            </tr>
            <tr
              class="red--text font-weight-bold"
              v-if="$store.state.ticket_details.extra_pay > 0"
            >
              <td>
                <span>Extra Fee:</span>
                <span>(for extra stay)</span>
              </td>
              <td>${{ $store.state.ticket_details.extra_pay }}</td>
            </tr>
          </tbody>
        </template>
      </v-simple-table>
    </v-card-text>

    <v-card-actions>
      <pay-ticket :id="$store.state.ticket_details.id" />
    </v-card-actions>
  </v-card>
</template>
<script>
import moment from "moment";
import PayTicket from "./PayTicket";
export default {
  components: {
    PayTicket,
  },
  methods: {
    convertDate(value) {
      return moment(value).format("YYYY-MM-DD (HH:mm)");
    },
  },
};
</script>
