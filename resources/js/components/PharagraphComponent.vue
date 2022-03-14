<template>
  <v-app>
    <v-form>
      <v-container>
        <v-row>
            <v-row>
                <v-col cols="12" sm="6" md="6"><h1>Create Product</h1></v-col>
                <v-col cols="12" sm="6" md="6"><h1>Create Brand</h1></v-col>
            </v-row>
            <v-col cols="12" sm="6" md="6">
                <v-text-field v-model="productName" label="Product Name" placeholder="Product Name" solo></v-text-field>
            </v-col>
            <v-col cols="12" sm="6" md="6">
                <v-text-field v-model="brandName" label="Brand Name" placeholder="Brand Name" solo></v-text-field>
            </v-col>
        </v-row>
        <v-row>
            <v-col cols="12" sm="6" md="6">
                <v-text-field v-model="quantity" label="Quantity" placeholder="Quantity" solo></v-text-field>
            </v-col>
            <v-col cols="12" sm="6" md="6">
                <v-text-field v-model="brandDetails" label="Brand Details" placeholder="Brand Details" solo></v-text-field>
            </v-col>
        </v-row>
        <v-row>
            <v-col cols="12" sm="6" md="6">
                 <v-select
                 v-model="brandCB"
                 :items="brandList"
                 item-text="brandName"
                 item-value="id"
                 ></v-select>
            </v-col>
        </v-row>
        <v-row>
            <v-col cols="12" sm="4" md="6">
                <button v-if="saveBtn" @click="saveProduct()" class="btn btn-primary">Save</button>
                <button @click="editProduct()" class="btn btn-success">Edit</button>
            </v-col>
            <v-col cols="12" sm="4" md="6">
                <button @click="saveProductDetails()" class="btn btn-primary">Save</button>
            </v-col>        
        </v-row>
        </v-container>
    </v-form>
  </v-app>
</template>

<script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>
<script>
export default {
    props:[
        'Vid',
        'VproductName',
        'VbrandCB',
        'Vquantity',
        'Vsbtn',
        'Vebtn'
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
            brandList: [],

            saveBtn:true,


        }
    },
    mounted() {
        
        this.showBrandNames();
        this.id = this.Vid;
        this.productName = this.VproductName;
        this.brandCB = this.VbrandCB;
        this.quantity = this.Vquantity;
        this.saveBtn = this.Vsbtn;
    
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
                this.saveBtn = true;
                this.productName = '';
                this.quantity = '';
                this.brandCB = 0;
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
        },
        Vsbtn(val){
            this.saveBtn = val;
        },
        Vebtn(val){
            this.editBtn = val;
        },
    }
};
</script>
