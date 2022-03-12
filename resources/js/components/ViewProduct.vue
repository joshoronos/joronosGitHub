<template>
    <v-app>
      <div class="container">
        <div class="w3-bar w3-border w3-light-grey">
          <a href="#" v-if="userRole == 1" v-on:click="showCreate()" class="w3-bar-item w3-button w3-hover-green">Create Product/Brand</a>
          <a href="#" v-if="userRole == 1" v-on:click="showProduct()" class="w3-bar-item w3-button w3-hover-green">Product Table</a>
          <a href="#" v-on:click="showPurchase()"  class="w3-bar-item w3-button w3-hover-green">Purchase Order</a>
        </div>
      </div>
      <v-container v-if="createDiv">
        <v-row v-if="userRole == 1">
        <pharagraph-component :Vid="id" :VproductName="productname" :VbrandCB="brandCB" :Vquantity="quantity"></pharagraph-component>
        </v-row>
      </v-container>
     <v-container v-if="productDiv">
      <v-row>
        <v-data-table
            :headers="headers"
            :items="handler"
            :items-per-page="5"
            :hide-default-footer="true"
            class="elevation-1"
        >
        <template v-slot:[`item.action`]="{ item }">
            <v-item-group v-if="userRole == 2">
            <v-icon @click="showDialog(true); getDetails(item)" color="success" class="btn btn-xsmall">fa fa-cart-plus</v-icon>
            </v-item-group>           
            <v-item-group v-if="userRole == 1">
              <v-icon color="primary" @click="editDetails(item)" class="btn btn-xsmall">fas fa-edit</v-icon>
              <v-icon color="error" @click="deleteItem(item)" class="btn btn-xsmall">fas fa-trash-can</v-icon>
            </v-item-group> 
        </template>
        </v-data-table>
      </v-row>
      </v-container>

      <v-container v-if="purchaseDiv">
        <purchaseOrder-component></purchaseOrder-component>
      </v-container>

      <v-row justify="center">
        <v-dialog
          v-model="showModal"
          persistent
          max-width="1000px"
        >
      <v-card>
        <v-card-title>
          <span class="text-h5">Purchase Order</span>
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
                  label="Product Name"
                  v-model="modalProductName"
                  readonly
                  solo
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
                  solo
                ></v-text-field>
              </v-col>
              <v-col
                cols="12"
                sm="6"
                md="4"
              >
                <v-text-field
                  label="Input Quantity on Purchase"
                  v-model="modalProductQuantity"
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
            @click="showDialog(false)"
          >
            Close
          </v-btn>
          <v-btn
            color="blue darken-1"
            text
            @click="showDialog(false); savePurchaseOrder();"
          >
            Continue
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
    </v-row>    
    </v-app>
</template>


<script src="https://cdnjs.cloudflare.com/ajax/libs/vue/2.5.17/vue.js"></script>
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
          { text: 'Action', value: 'action'}
        ],
        handler: [],
        id:'',
        brandName:'',
        brandID:'',
        productname:'',
        quantity:'',
        brandCB:'',
        userRole:'',
        userName:'',

        modalProductName:'',
        modalProductQuantity:'',
        modalBrandName:'',

        showModal:false,
        createDiv:false,
        productDiv:false,
        purchaseDiv:false
    
        
      }

    },

    mounted () {
      
        this.viewAllProduct();
        console.log(this.createDiv);
        
    },

    methods: {
        viewAllProduct() {
            axios.get("/viewAllProduct").then((response) => {
                this.handler = response.data.product;
                this.userRole = response.data.userType;
                this.userName = response.data.username;
            });
        },

        editDetails(resp) {
          this.id = resp.id
          this.productname = resp.name
          this.quantity = resp.quantity
          this.brandCB = resp.brand_id
        },

        deleteItem(resp) {
          axios.post("/deleteProduct",{
                  id : resp.id,
            }).then(response =>{
                alert(response.data);
                this.viewAllProduct();
            })
          
        },

        getDetails(resp) {
          this.id = resp.id
          this.modalProductName = resp.name
          this.modalBrandName = resp.brandName
        },

        showDialog(val){
             this.showModal = val;
        
        },

        showCreate() {
          this.createDiv = true;
          this.productDiv = false;
          this.purchaseDiv = false;
        },

        showProduct() {
          this.productDiv = true;
          this.createDiv = false;
          this.purchaseDiv = false;
        },

        showPurchase() {
          this.purchaseDiv = true;
          this.createDiv = false;
          this.productDiv = false;
        },

        savePurchaseOrder(){
            axios.post("/purchaseOrder",{
                product_id         :this.id,
                quantity_order     :this.modalProductQuantity,
                username           :this.userName,
                orderStatus        : 0
            }).then(response => {
                alert(response.data);
            })
        },
    }
  }
</script>
