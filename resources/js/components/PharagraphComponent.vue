<template>
  <v-app>
    <v-form>
      <v-container>
        <v-row>
            <h1>Product Name</h1>
            <v-col cols="12" sm="6" md="2">
                <v-text-field v-model="productName" label="Product Name" placeholder="Product Name" solo></v-text-field>
            </v-col>
            <v-col cols="12" sm="6" md="2">
                <v-text-field v-model="quantity" label="Quantity" placeholder="Quantity" solo></v-text-field>
            </v-col>
            <v-col cols="12" sm="6" md="2">
                 <v-select
                 v-model="brandCB"
                 :items="brandList"
                 item-text="brandName"
                 item-value="id"
                 ></v-select>
            </v-col>
            <v-col cols="12" sm="4" md="4">
                <button @click="saveProduct()" class="btn btn-primary">Save</button>
                <button @click="editProduct()" class="btn btn-success">Edit</button>
            </v-col>
            <v-row>       
            </v-row>
        </v-row>
        <v-row>
            <h1>Product Details</h1>
            <v-col cols="12" sm="6" md="3">
                <v-text-field v-model="brandName" label="Brand Name" placeholder="Brand Name" solo></v-text-field>
            </v-col>
            <v-col cols="12" sm="6" md="3">
                <v-text-field v-model="brandDetails" label="Brand Details" placeholder="Brand Details" solo></v-text-field>
            </v-col>   
            <v-col cols="12" sm="6" md="2">
                
            </v-col>
            <v-col cols="12" sm="4" md="3">
                <button @click="saveProductDetails()" class="btn btn-primary">Save</button>
                <button  class="btn btn-success">Edit</button>
            </v-col>
            <v-row>       
            </v-row>
        </v-row>     
      </v-container>
    </v-form>
  </v-app>
</template>

<script>
export default {
    props:[
        'Vid',
        'VproductName',
        'VbrandCB',
        'Vquantity'
    ],
    data() {
        
        return{
            id:'',
            productName:'',
            details:'',
            quantity:'',

            brandName:'',
            brandDetails:'',

            brandCB: '',
            brandList: []
        }
    },

    mounted() {
        console.log("pogi ako.");
        this.showBrandNames();
        
    },

    methods: {
        saveProduct(){
            axios.post("/saveProduct",{
                productName :this.productName,
                brandID     :this.brandCB,
                quantity    :this.quantity
            }).then(response => {
                alert(response.data);
            })
        },
        saveProductDetails(){
            axios.post("/saveProductDetails",{
                brandName :this.brandName,
                brandDetails :this.brandDetails
            }).then(response => {
                alert(response.data);
            })

        },      
        editProduct(){
            axios.post("/editProduct",{
                    id          : this.id,
                    prodName    : this.productName,
                    brandId     : this.brandCB,
                    quantity    : this.quantity
            }).then(response =>{
                alert(response.data);
            })
            
        },
        showBrandNames(){
              axios.get("/getBrandDetails").then((response) => {
                this.brandList = response.data;
            });
        }

    },
    watch:{
        Vid(val){
            this.id = val;
        },
        VproductName(val){
            this.productName = val;
        },
        VbrandCB(val){
            this.brandCB = val;
        },
        Vquantity(val){
            this.quantity = val;
        }
    }
};
</script>
