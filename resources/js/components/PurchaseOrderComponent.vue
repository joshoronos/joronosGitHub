<template>
    <v-app>
        <v-row>
        <v-data-table
            :headers="headers"
            :items="handler"
            :items-per-page="5"
            :hide-default-footer="true"
            class="elevation-1"
        >
        <template v-slot:[`item.action`]="{ item }">
        <v-btn>
            <v-icon @click="modalApprove(true); getData(item)" color="success" class="btn btn-xsmall">fa fa-check-square</v-icon>
        </v-btn>
          
        </template>
        </v-data-table>
      </v-row>

<v-row justify="center">
    <v-dialog
      v-model="approveDialog"
      persistent
      max-width="290"
    >
      <v-card>
        <v-card-title class="text-h5">
          Do you want to approve this transaction?
        </v-card-title>
        <v-card-text></v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn
            color="blue darken-1"
            text
            @click="approveDialog=false"
          >
            No
          </v-btn>
          <v-btn
            color="blue darken-1"
            text
            @click="approvePurchaseOrder(); approveDialog = false"
          >
            Yes
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </v-row>
    </v-app>
</template>


<script>
  export default {
    data () {
      return {
        headers: [
          {
            text: 'Product Name',
            align: 'start',
            sortable: false,
            value: 'name',
          },
          { text: 'Brand Name', value: 'brandName' },
          { text: 'Quantity', value: 'quantity'},
          { text: 'Quantity Ordered', value: 'quantity_order'},
          { text: 'Action', value: 'action'},
        ],
        handler: [],

        approveDialog:false,

        itemHandler:[]

      }
    },

    mounted () {
        this.showPurchaseOrder();
    },

    methods: {
        showPurchaseOrder() {
            axios.get("/showPurchaseOrder").then((response) => {
                this.handler = response.data;
            });
        },

        modalApprove(val) {
          this.approveDialog = val;
        },

        getData(val) {
          this.itemHandler = val;
        },

        approvePurchaseOrder() {
          axios.post("/approvePurchaseOrder",{
                purchase_id        :this.itemHandler.id,
                product_id         :this.itemHandler.product_id,
                quantity_order     :this.itemHandler.quantity_order,
                orderStatus        :this.itemHandler.status
            }).then(response => {
                alert(response.data);
                this.showPurchaseOrder();
            })
        }
    },
  }

</script>