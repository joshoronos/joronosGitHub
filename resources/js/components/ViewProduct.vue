<template>
    <v-app>

      <div class="container">
        <div class="w3-bar w3-border w3-light-grey">
          <a href="#" v-if="userRole == 1" v-on:click="showCreate()" class="w3-bar-item w3-button w3-hover-green">Brand</a>
          <a href="#" v-on:click="showProduct(); showBrandNames()" class="w3-bar-item w3-button w3-hover-green">Product</a>
          <a href="#" v-if="userRole == 1" v-on:click="showPurchase()"  class="w3-bar-item w3-button w3-hover-green">Purchase Order</a>
          <a href="#" v-if="userRole == 1" v-on:click="showPurchaseApprove()"  class="w3-bar-item w3-button w3-hover-green">Purchase Approve</a>
          <a href="#" v-if="userRole == 4" v-on:click="nextPage()"  class="w3-bar-item w3-button w3-hover-green">Next Page</a>
        </div>
      </div>

      <v-container v-if="createDiv">
        <v-row v-if="userRole == 1">
          <brand-component :Vid="id" :VproductName="productname" :VbrandCB="brandCB" :Vquantity="quantity" :Vsbtn="saveBtn"></brand-component>
        </v-row>
      </v-container>

      <v-container v-if="purchaseApproveDiv">
        <v-row v-if="userRole == 1">
          <purchaseApprove-component></purchaseApprove-component>
        </v-row>
      </v-container>

    <v-container v-if="productDiv">
      <v-card>
        <v-card-title>
        <v-btn @click="modalCreate=true; brandCB=0;" v-if="userRole == 1"
        color="success"
        dark
        >
        <v-icon left>
          fa fa-plus-square
        </v-icon>
          ADD PRODUCT
        </v-btn>
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
              <v-item-group v-if="userRole == 2">
                <v-btn icon color="success">
                  <v-icon small @click="showDialog(true); getDetails(item)" color="success">fas fa-cart-plus</v-icon>
                </v-btn>
              </v-item-group>           
              <v-item-group v-if="userRole == 1">
                <v-btn icon>
                  <v-icon small  @click="viewDetails(item); viewDialog=true" class="btn btn-xsmall">fa-solid fa-eye</v-icon>
                </v-btn>
                <v-btn icon class="grey lighten-4 mx-0" color="primary">
                  <v-icon small @click="editDetails(item); modalEdit=true" class="btn btn-xsmall mx-1">fas fa-edit</v-icon>
                </v-btn>
                <v-btn icon color="error">
                  <v-icon small @click="getDataToDelete(item); modalDelete=true" class="btn btn-xsmall">fas fa-trash-can</v-icon>
                </v-btn>
              </v-item-group> 
          </template>
          </v-data-table>
      </v-card>
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
          <span class="text-h5"><v-icon color="success">fas fa-cart-plus</v-icon> Purchase Order</span>
        </v-card-title>
        <v-card-text>
          <v-container>
            <v-row>

              <v-col cols="12" sm="6" md="4">
                <v-text-field
                  label="Product Name"
                  v-model="modalProductName"
                  readonly
                  solo
                ></v-text-field>
              </v-col>

              <v-col cols="12" sm="6" md="4">
                <v-text-field
                  label="Brand Name"
                  v-model="modalBrandName"
                  readonly
                  solo
                ></v-text-field>
              </v-col>

              <v-col cols="12" sm="6" md="4">
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

    <v-row justify="center">
    <v-dialog
      v-model="modalCreate"
      persistent
      max-width="400px"
    >
      <v-card>
        <v-card-title>
          <span class="text-h5"><v-icon color="success">fas fa-plus-square</v-icon> Create Product</span>
        </v-card-title>
        <v-card-text>
          <v-container>

            <v-row>
              <v-col cols="12" sm="6" md="12">
                <v-text-field
                  v-model="productName"
                  label="Product Name"
                  required
                ></v-text-field>
              </v-col>
            </v-row>

            <v-row>
              <v-col cols="12" sm="6" md="12">
                <v-text-field
                  v-model="quantity"
                  label="Quantity"
                  require
                ></v-text-field>
              </v-col>
            </v-row>

            <v-row>
              <v-col cols="12" sm="6" md="12">
                  <v-select
                  v-model="brandCB"
                  :items="brandList"
                  item-text="brandName"
                  item-value="id"
                  ></v-select>
              </v-col>
            </v-row>

          </v-container>
        </v-card-text>

        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn
            color="blue darken-1"
            text
            @click="modalCreate=false"
          >
            Close
          </v-btn>
          <v-btn
            color="blue darken-1"
            text
            @click="saveProduct(); modalCreate=false"
          >
            Save
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </v-row>

  <v-row justify="center">
    <v-dialog
      v-model="modalEdit"
      persistent
      max-width="400px"
    >
      <v-card>
        <v-card-title>
          <span class="text-h5"><v-icon color="primary">fas fa-pencil</v-icon> Edit Product</span>
        </v-card-title>
        <v-card-text>
          <v-container>
            
            <v-row>
              <v-col cols="12" sm="6" md="12">
                <v-text-field
                  v-model="editProductName"
                  label="Product Name"
                  required
                ></v-text-field>
              </v-col>
            </v-row>

            <v-row>
              <v-col cols="12" sm="6" md="12">
                <v-text-field
                  v-model="editQuantity"
                  label="Quantity"
                  require
                ></v-text-field>
              </v-col>
            </v-row>

            <v-row>
              <v-col cols="12" sm="6" md="12">
                  <v-select
                  v-model="brandCB"
                  :items="brandList"
                  item-text="brandName"
                  item-value="id"
                  ></v-select>
              </v-col>
            </v-row>

          </v-container>
        </v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn
            color="blue darken-1"
            text
            @click="modalEdit=false"
          >
            Close
          </v-btn>
          <v-btn
            color="blue darken-1"
            text
            @click="editProduct(); modalEdit=false"
          >
            Save
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </v-row>

    <v-row justify="center">
      <v-dialog
        v-model="modalDelete"
        max-width="470"
      >
        <v-card>
          <v-card-title class="text-h5">
            Are you sure you want to delete this item?
          </v-card-title>

          <v-card-actions>
            <v-spacer></v-spacer>

            <v-btn
              color="error"
              text
              @click="modalDelete=false"
            >
              Disagree
            </v-btn>

            <v-btn
              color="success"
              text
              @click="deleteItem(); modalDelete=false"
            >
              Agree
            </v-btn>
          </v-card-actions>
        </v-card>
      </v-dialog>
    </v-row>


    <v-row justify="center">
      <v-dialog
          v-model="viewDialog"
         
        
      >
        <v-card>
          <v-card-title>
            <span class="text-h8"><v-icon>fas fa-info-circle</v-icon> Information
            </span>
          </v-card-title>
          <v-card-text>
            <v-container>
              <v-row>

                <v-col cols="12" sm="6" md="4">
                <v-text-field
                v-model="viewModelProduct"
                label="Product Name"
                readonly
                ></v-text-field>
                </v-col>

                <v-col cols="12" sm="6" md="4">
                  <v-text-field
                    label="Brand Name"
                    v-model="viewModelBrandName"
                    readonly
                  ></v-text-field>
                </v-col>

                <v-col cols="12" sm="6" md="4">
                  <v-text-field
                    label="Details"
                    v-model="viewModelDetails"
                    readonly
                  ></v-text-field>
                </v-col>

                <v-col cols="12" sm="6" md="4">
                  <v-text-field
                    label="Quantity"
                    v-model="viewModelQty"
                    readonly
                  ></v-text-field>
                </v-col>

                <v-col cols="12" sm="6" md="4">
                  <v-text-field
                    label="Price"
                    v-model="viewModelPrice"
                    readonly
                  ></v-text-field>
                </v-col>
              </v-row>
              <v-spacer></v-spacer>

            <v-card>
        <v-card-title>
          Purchase Order
          <v-spacer></v-spacer>
          <v-text-field
            v-model="purchaseSearch"
            append-icon="fa fa-search"
            label="Search"
            single-line
            hide-details
          ></v-text-field>
        </v-card-title>
          <v-data-table
              :headers="purchaseHeaders"
              :items="purchaseOrderHandler"
              :items-per-page="10"
              :search="purchaseSearch"
              class="elevation-1"
          >
          <template v-slot:[`item.status`]>
            <v-btn color="error" rounded>
                Pending
            </v-btn>
          </template>
          </v-data-table>
      </v-card>

            </v-container>
          </v-card-text>

          <v-card-actions>
            <v-spacer></v-spacer>

            <v-btn
              color="blue darken-1"
              text
              @click="viewDialog=false">
              Close
            </v-btn>

          </v-card-actions>
        </v-card>
      </v-dialog>
    </v-row>

    </v-app>
</template>

<script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/vue/2.5.17/vue.js"></script>
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
          { text: 'Brand Name', value: 'brandName', sortable: false },
          { text: 'Quantity', value: 'quantity', sortable: false},
          { text: 'Action', value: 'action', sortable: false},

        ],

        purchaseSearch:'',
        purchaseHeaders: [
          {
            text: 'PO Reference Number',
            align: 'start',
            sortable: false,
            value: 'po_reference_no',
          },
          { text: 'Quantity Ordered', value: 'quantity_order', sortable: false },
          { text: 'Date Purchase', value: 'date_purchase', sortable: false},
          { text: 'Person Created', value: 'username', sortable: false},
          { text: 'PO Status', value: 'status', sortable: false},

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
        itemHandler: [],
        productName:'',
        quantity:'',

        editProductName:'',
        editQuantity:'',
        modalProductName:'',
        modalProductQuantity:'',
        modalBrandName:'',
        modalCreate:'',
        modalEdit:'',
        modalDelete:'',

        saveBtn:true,
        editBtn:true,
        checking:false,
        showModal:false,
        createDiv:false,
        productDiv:false,
        purchaseDiv:false,
        purchaseApproveDiv:false,
        viewDialog:false,

        viewModelProduct:'',
        viewModelBrandName:'',
        viewModelDetails:'',
        viewModelQty:'',
        viewModelPrice:'',

        purchaseOrderHandler:[],

        brandCB: '',
        brandList: [],
        deleteId:''
      
      }

    },

    mounted () {
        this.viewAllProduct();
        this.showBrandNames();
    },

    methods: {
        viewAllProduct() {
            axios.get("/viewAllProduct").then((response) => {
                this.handler = response.data.product;
                this.userRole = response.data.userType;
                this.userName = response.data.username;
                // this.handler.forEach(e=>{
                //   e.input = 'kajsdoasidjo'
                // })
            });
        },

        editDetails(resp) {
          console.log(resp)
          this.itemHandler = resp
          this.id = resp.id
          this.editProductName = resp.name
          this.editQuantity = resp.quantity
          this.brandCB = resp.brand_id
        },

        viewDetails(resp) {
            axios.post("/viewProductInfo",{
                    id          : resp.id,
            }).then(response =>{
                this.purchaseOrderHandler = response.data.productPurchase
                this.viewModelProduct = response.data.product[0].name
                this.viewModelBrandName = response.data.product[0].brandName
                this.viewModelDetails = response.data.product[0].details
                this.viewModelQty = response.data.product[0].quantity
                this.viewModelPrice = response.data.product[0].price
            })  
        },

        getDataToDelete(item){
          
          this.deleteId = item.id
          
        },

        saveProduct(){
            axios.post("/saveProduct",{ 
                productName :this.productName,
                brandID     :this.brandCB,
                quantity    :this.quantity
            }).then(response => {
                alert(response.data);
                this.productName = ''
                this.brandCB = ''
                this.quantity = ''
                this.viewAllProduct();
            })
        },

        editProduct(){
            axios.post("/editProduct",{
                    id          : this.id,
                    prodName    : this.editProductName,
                    brandId     : this.brandCB,
                    quantity    : this.editQuantity
            }).then(response =>{
                alert(response.data);
                this.saveBtn = true;
                this.productName = '';
                this.quantity = '';
                this.brandCB = 0;
                this.viewAllProduct();
            })         
        },

        deleteItem() {
          axios.post("/deleteProduct",{
                  id : this.deleteId,
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

        showBrandNames(){
              axios.get("/getBrandDetails").then((response) => {
                this.brandList = response.data;
            });
        },

        showDialog(val){
             this.showModal = val;
        },

        showCreate() {
          this.editBtn = false;
          this.createDiv = true;
          this.productDiv = false;
          this.purchaseDiv = false;
          this.purchaseApproveDiv = false;
        },

        showProduct() {
          this.productDiv = true;
          this.createDiv = false;
          this.purchaseDiv = false;
          this.purchaseApproveDiv = false;
          this.viewAllProduct();
        },

        showPurchase() {
          this.purchaseDiv = true;
          this.createDiv = false;
          this.productDiv = false;
          this.purchaseApproveDiv = false;
        },

        showPurchaseApprove() {
          this.purchaseDiv = true;
          this.createDiv = false;
          this.productDiv = false;
          this.purchaseApproveDiv = true;
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

        nextPage(){
            location.assign('/page2');
        }
    }
  }
</script>
