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
            <v-btn icon color="success">
                <v-icon small @click="modalApprove(true); getData(item)" class="btn btn-xsmall">fa fa-check-square</v-icon>
            </v-btn>       
          </template>
        </v-data-table>
      </v-row>

    <v-row justify="center">
      <v-dialog
        v-model="approveDialog"
        persistent
        max-width="600px"
      >
      <v-card>
        <v-card-title>
          <span class="text-h8"><v-icon>fas fa-info-circle</v-icon>Information
          </span>
        </v-card-title>
        <v-card-text>
          <v-container>
            <v-row>
              <v-col
                cols="12"
                sm="6"
                md="4"
              >
              <v-text-field
              v-model="modalProductName"
              label="Product Name"
              readonly
              ></v-text-field>
              </v-col>
              <v-col
                cols="12"
                sm="6"
                md="4"
              >
                <v-text-field
                  label="Brand Name"
                  v-model="modalBrandName"
                  readonly
                ></v-text-field>
              </v-col>
              <v-col
                cols="12"
                sm="6"
                md="4"
              >
                <v-text-field
                  label="Product Price"
                  v-model="modalPrice"
                  readonly
                ></v-text-field>
              </v-col>
              <v-col
                cols="12"
                sm="6"
                md="8"
              >
              </v-col>
              <v-col
                cols="12"
                sm="6"
                md="4"
              >
                <v-text-field
                  label="Quantity Ordered"
                  v-model="modalQuantityOrder"
                  readonly
                ></v-text-field>
              </v-col>
              <v-col
                cols="12"
                sm="6"
                md="8"
              >
              </v-col>
              <v-col
                cols="12"
                sm="6"
                md="4"
              >
                <v-text-field
                  class="red-total"
                  label="Total Price"
                  color="error"
                  v-model="modalTotalPrice"
                  readonly
                ></v-text-field>
              </v-col>
            </v-row>
          </v-container>
        </v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn
            color="blue darken-1"
            text
            @click="approveDialog = false"
          >
            Close
          </v-btn>
          <v-btn
            color="blue darken-1"
            text
            @click="approvePurchaseOrder(); approveDialog = false"
          >
            Confirm
          </v-btn>
        </v-card-actions>
      </v-card>
      </v-dialog>
    </v-row>
  </v-app>
</template>

<style>
.red-total input{
  color: red !important;
}
</style>
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
          { text: 'Price per Item', value: 'price'},
          { text: 'Action', value: 'action'},
        ],
        handler: [],

        approveDialog:false,

        priceFormat:'',
        itemHandler:[],

        modalProductName:'',
        modalBrandName:'',
        modalPrice:'',
        modalQuantityOrder:'',
        modalTotalPrice:''

      }
    },

    mounted () {
        this.showPurchaseOrder();    
    },

    methods: {
        showPurchaseOrder() {
            axios.get("/showPurchaseOrder").then((response) => {
                this.handler = response.data.product;
                console.log(this.handler);
            });
        },

        modalApprove(val) {
          this.approveDialog = val;
        },

        getData(val) {
          this.itemHandler = val;
          this.modalProductName = val.name
          this.modalBrandName = val.brandName
          this.modalPrice = val.price.toLocaleString();
          this.modalQuantityOrder = val.quantity_order
          this.modalTotalPrice = (val.quantity_order * val.price).toLocaleString();
        },

        approvePurchaseOrder() {
          axios.post("/approvePurchaseOrder",{
                purchase_id        :this.itemHandler.id,
                product_id         :this.itemHandler.product_id,
                quantity_order     :this.itemHandler.quantity_order,
                brandPrice         :this.itemHandler.price,
                orderStatus        :this.itemHandler.status,
                brand_id           :this.itemHandler.brand_id,
                totalPrice         :this.itemHandler.quantity_order * this.itemHandler.price
            }).then(response => {
                alert(response.data);
                this.showPurchaseOrder();
            })
        }
    },
  }

</script>