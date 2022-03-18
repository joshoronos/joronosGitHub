<template>
    <v-app>
      <v-card>
        <v-card-title>
          Purchase Approve
          <v-spacer></v-spacer>
          <v-text-field
            v-model="search"
            append-icon="fa fa-search"
            label="Search"
            single-line
            hide-details
          ></v-text-field>
        </v-card-title>

        <v-data-table
            :headers="headers"
            :items="handler"
            :items-per-page="10"
            :search="search"
            class="elevation-1"
        >
          <template v-slot:[`item.status`]>
            <v-btn color="success" rounded>
                Approved
            </v-btn>       
          </template>
        </v-data-table>
      </v-card>
  </v-app>
</template>

<script>

   export default {
    data () {
      return {
        search:'',
        headers: [
        {
            text: 'Product Name',
            align: 'start',
            sortable: false,
            value: 'name',
        },
            { text: 'Brand Name', value: 'brandName', sortable: false},
            { text: 'Quantity Ordered', value: 'quantity_order', sortable: false},
            { text: 'Total Price', value: 'total_price', sortable: false},
            { text: 'Status', value: 'status', sortable: false},
        ],

          handler:[],
          
      }
    },
    mounted(){
        this.showPurchaseApprove();
    },


        methods:{
        showPurchaseApprove(){
        axios.get("/getPurchaseApprove").then((response) => {
                this.handler = response.data;
            });
        },
    }
}


</script>