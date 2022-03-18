<template>
  <v-app>
        <v-container> 
      <v-card>
        <v-card-title>
        <v-btn @click="modalCreate=true"
        color="success"
        dark
        >
        <v-icon left>
        fa fa-plus-square
        </v-icon>
        ADD BRAND
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
              <v-item-group>
                <v-btn icon>
                  <v-icon small @click="viewBrandInfo(item); viewDialog=true" class="btn btn-xsmall">fa-solid fa-eye</v-icon>
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


    <v-row justify="center">
        <v-dialog
          v-model="modalCreate"
          persistent
          max-width="400px"
        >
      <v-card>
        <v-card-title>
          <span class="text-h5"><v-icon color="success">fas fa-cart-plus</v-icon> Purchase Order</span>
        </v-card-title>
        <v-card-text>
          <v-container>
            <v-row>

            <v-row>
              <v-col cols="12" sm="6" md="12">
                <v-text-field
                  v-model="modalBrandName"
                  label="Brand Name"
                  required
                ></v-text-field>
              </v-col>
            </v-row>

            <v-row>
              <v-col cols="12" sm="6" md="12">
                <v-text-field
                  v-model="modalDetails"
                  label="Details"
                  require
                ></v-text-field>
              </v-col>
            </v-row>

            <v-row>
              <v-col cols="12" sm="6" md="12">
                <v-text-field
                  v-model="modalPrice"
                  label="Price"
                  require
                ></v-text-field>
              </v-col>
            </v-row>

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
            @click="modalCreate=false; saveProductDetails();"
          >
            Continue
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
                  v-model="editBrandName"
                  label="Brand Name"
                  required
                ></v-text-field>
              </v-col>
            </v-row>

            <v-row>
              <v-col cols="12" sm="6" md="12">
                <v-text-field
                  v-model="editBrandDetails"
                  label="Details"
                  require
                ></v-text-field>
              </v-col>
            </v-row>

            <v-row>
              <v-col cols="12" sm="6" md="12">
                <v-text-field
                  v-model="editPrice"
                  label="Price"
                  require
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
            @click="modalEdit=false"
          >
            Close
          </v-btn>
          <v-btn
            color="blue darken-1"
            text
            @click="editProductDetails(); modalEdit=false"
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
                v-model="viewModelBrandName"
                label="Brand Name"
                readonly
                ></v-text-field>
                </v-col>

                <v-col cols="12" sm="6" md="4">
                  <v-text-field
                    label="Brand Details"
                    v-model="viewModelDetails"
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
          Product
          <v-spacer></v-spacer>
          <v-text-field
            v-model="productSearch"
            append-icon="fa fa-search"
            label="Search"
            single-line
            hide-details
          ></v-text-field>
        </v-card-title>
          <v-data-table
              :headers="productHeaders"
              :items="productHandler"
              :items-per-page="5"
              :search="productSearch"
              class="elevation-1"
          >
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
<script>
export default {
    // props:[
    //     'Vid',
    //     'VproductName',
    //     'VbrandCB',
    //     'Vquantity',
    //     'Vsbtn',
    //     'Vebtn'
    // ],
    data() {
        
        return{
            search:'',
            headers: [
          {
            text: 'Brand Name',
            align: 'start',
            sortable: false,
            value: 'brandName',
          },
          { text: 'Product Details', value: 'details', sortable: false },
          { text: 'Price', value: 'price', sortable: false},
          { text: 'Action', value: 'action', sortable: false},

        ],
        productSearch:'',

        productHeaders: [
          {
            text: 'Product Name',
            align: 'start',
            sortable: false,
            value: 'name',
          },
          { text: 'Quantity', value: 'quantity', sortable: false },
        ],

        viewModelBrandName:'',
        viewModelDetails:'',
        viewModelPrice:'',
        viewDialog:'',
        productHandler:[],

        handler:[],
        itemHandler:[],
        modalCreate:'',
        modalDelete:'',
        modalEdit:'',
        editBrandName:'',
        editBrandDetails:'',
        editPrice:'',
        id:'',
        deleteId:'',
        modalBrandName:'',
        modalDetails:'',
        modalPrice:''

        }
    },
    mounted() {

        this.viewAllBrand();
        // this.id = this.Vid;
        // this.productName = this.VproductName;
        // this.brandCB = this.VbrandCB;
        // this.quantity = this.Vquantity;
        // this.saveBtn = this.Vsbtn;
    
    },

    methods: {

        viewAllBrand() {
            axios.get("/viewAllBrand").then((response) => {
                this.handler = response.data;
                // this.handler.forEach(e=>{
                //   e.input = 'kajsdoasidjo'
                // })
            });
        },

        editDetails(resp) {
          console.log(resp)
          //this.itemHandler = resp
          this.id = resp.id
          this.editBrandName = resp.brandName
          this.editBrandDetails = resp.details
          this.editPrice = resp.price

        },

        getDataToDelete(item){
          
          this.deleteId = item.id
          
        },

        deleteItem() {
          axios.post("/deleteBrand",{
                  id : this.deleteId,
            }).then(response =>{
                alert(response.data);
                this.viewAllBrand();
            })
          
        },

        viewBrandInfo(resp){
            axios.post("/viewBrandInfo",{
                  id : resp.id,
            }).then(response =>{
                console.log(response.data)
                this.productHandler = response.data
                this.viewModelBrandName = response.data[0].brandName
                this.viewModelDetails = response.data[0].details
                this.viewModelPrice = response.data[0].price
            })
        },

        saveProductDetails(){
            axios.post("/saveProductDetails",{
                brandName    :this.modalBrandName,
                brandDetails :this.modalDetails,
                brandPrice   :this.modalPrice
            }).then(response => {
                alert(response.data);
                this.viewAllBrand();
                this.modalBrandName = ''
                this.modalDetails = ''
                this.modalPrice = ''
            })

        },

        editProductDetails(){
            axios.post("/editProductDetails",{
                id           :this.id,
                brandName    :this.editBrandName,
                brandDetails :this.editBrandDetails,
                brandPrice   :this.editPrice
            }).then(response => {
                alert(response.data);
                this.viewAllBrand();
                this.editBrandName = ''
                this.editPrice = ''
                this.editBrandDetails = ''
            })

        },

        

        // saveProduct(){
        //     axios.post("/saveProduct",{ 
        //         productName :this.productName,
        //         brandID     :this.brandCB,
        //         quantity    :this.quantity
        //     }).then(response => {
        //         alert(response.data);
        //         this.productName = ''
        //         this.brandCB = ''
        //         this.quantity = ''
        //     })
        // },

        // editProduct(){
        //     axios.post("/editProduct",{
        //             id          : this.id,
        //             prodName    : this.productName,
        //             brandId     : this.brandCB,
        //             quantity    : this.quantity
        //     }).then(response =>{
        //         alert(response.data);
        //         this.saveBtn = true;
        //         this.productName = '';
        //         this.quantity = '';
        //         this.brandCB = 0;
        //     })         
        // },
    },
    // watch:{
    //     Vid(val){
    //         this.id = val;
    //     },
    //     VproductName(val){
    //         this.productName = val;
    //     },
    //     VbrandCB(val){
    //         this.brandCB = val;
    //     },
    //     Vquantity(val){
    //         this.quantity = val;
    //     },
    //     Vsbtn(val){
    //         this.saveBtn = val;
    //     },
    //     Vebtn(val){
    //         this.editBtn = val;
    //     },
    // }
};
</script>
