export const strict = false;
import axios from 'axios'
export const state = () => ({
  ticket_details: [],
  ticket_issued: false,
  reserve_place: false,
  vehicle_plate: '',
  selected_place: '',
  length: '1',
  price: '',
  ticket_number: ''
});

export const mutations = {
  // Get ticket details
  async getTicketDetails(state, ticket) {
    await axios
      .post(process.env.api_url + "ticket-details", {
        number: ticket,
      })
      .then((res) => {
        state.ticket_details = res.data;
        state.ticket_issued = true;
      })
      .catch((err) => {
        console.log(err);
      });
  },

};
