<template>
    <v-app>
      <v-card>
        <v-card-title>
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
          <template v-slot:[`item.action`]="{ item }">
            <v-btn icon color="success">
                <v-icon small @click="modalApprove(true); getData(item)" class="btn btn-xsmall">fa fa-check-square</v-icon>
            </v-btn>
            <v-btn icon class="grey lighten-4 mx-0" color="primary">
                  <v-icon small @click="getID(item.id); modalPicker=true" class="btn btn-xsmall mx-1">fas fa-edit</v-icon>
            </v-btn>       
          </template>
        </v-data-table>
      </v-card>

    <v-row justify="center">
      <v-dialog
          content-class="my-custom-dialog"
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

                <v-col cols="12" sm="6" md="4">
                <v-text-field
                v-model="modalProductName"
                label="Product Name"
                readonly
                ></v-text-field>
                </v-col>

                <v-col cols="12" sm="6" md="4">
                  <v-text-field
                    label="Brand Name"
                    v-model="modalBrandName"
                    readonly
                  ></v-text-field>
                </v-col>

                <v-col cols="12" sm="6" md="4">
                  <v-text-field
                    label="Product Price"
                    v-model="modalPrice"
                    readonly
                  ></v-text-field>
                </v-col>

                <v-col cols="12" sm="6" md="8">
                </v-col>

                <v-col cols="12" sm="6" md="4">
                  <v-text-field
                    label="Quantity Ordered"
                    v-model="modalQuantityOrder"
                    readonly
                  ></v-text-field>
                </v-col>

                <v-col cols="12" sm="6" md="8">
                </v-col>

                <v-col cols="12" sm="6" md="4">
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
              @click="approveDialog=false">
              Close
            </v-btn>

            <v-btn
              color="blue darken-1"
              text
              @click="approvePurchaseOrder(); approveDialog = false">
              Confirm
            </v-btn>

          </v-card-actions>
        </v-card>
      </v-dialog>
    </v-row>

  <v-row justify="center">
      <v-dialog
          content-class="my-custom-dialog"
          v-model="modalPicker"
          persistent
          max-width="600px"
      >
        <v-card>
          <v-card-title>
            <span class="text-h8"><v-icon>fas fa-calendar</v-icon> Select Date
            </span>
          </v-card-title>
          <v-card-text>
            <v-container>
              <v-row
              justify="space-around"
              align="center"
              >
              <v-date-picker
                v-model="picker"
                :min="getCurrentDate()"
                format="'YYYYMMDD'"
              ></v-date-picker>

              </v-row>
            </v-container>
          </v-card-text>

          <v-card-actions>
            <v-spacer></v-spacer>

            <v-btn
              color="blue darken-1"
              text
              @click="modalPicker=false">
              Close
            </v-btn>

            <v-btn
              color="blue darken-1"
              text
              @click="updatePurchaseDate(); approveDialog = false">
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
        search: '',
        picker:'',
        selectedDate: '',
        headers: [
          {
            text: 'PO Reference Number',
            align: 'start',
            sortable: false,
            value: 'po_reference_no',
          },
          { text: 'Product Name', value: 'name', sortable: false,},
          { text: 'Brand Name', value: 'brandName', sortable: false },
          { text: 'Quantity', value: 'quantity', sortable: false},
          { text: 'Quantity Ordered', value: 'quantity_order', sortable: false},
          { text: 'Price per Item', value: 'price', sortable: false},
          { text: 'Purchase Date', value: 'date_purchase', sortable: false},
          { text: 'Action', value: 'action', sortable: false},
        ],
        handler: [],

        approveDialog:false,
        modalPicker:false,

        idToUpdate:'',

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
            });
        },

        getID(val){
          this.idToUpdate = val
        },

        updatePurchaseDate()
        {
            axios.post("/updatePurchaseDate",{
                  id            : this.idToUpdate,
                  purchase_date : this.selectedDate
            }).then(response =>{
                alert(response.data);
                this.showPurchaseOrder();
            })
        },

        getCurrentDate(){
          let todayDate = new Date().toISOString().slice(0, 10);
          return todayDate;
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
    watch:{
      picker(val){
          this.selectedDate = val;
      }
    }
  }

</script>