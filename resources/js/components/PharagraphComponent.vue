<template>
  <v-app>
        <v-container>
            <v-card max-width="700px">
                <v-card-title>
                    <span class="text-h5">Create Brand</span>
                </v-card-title>
     
                <v-col cols="12" sm="6" md="6">
                <v-text-field
                v-model="brandName"
                label="Brand Name"
                required
                ></v-text-field>
                </v-col>

                <v-col cols="12" sm="6" md="6">
                <v-text-field
                v-model="brandDetails"
                label="Brand Details"
                required
                ></v-text-field>
                </v-col>

                <v-col cols="12" sm="6" md="6">
                <v-text-field
                v-model="brandPrice"
                label="Brand Price"
                required
                ></v-text-field>
                </v-col>

                <v-col cols="12" sm="4" md="6">
                    <v-btn depressed color="primary" @click="saveProductDetails()" class="btn btn-primary">Save</v-btn>
                </v-col>
                        
            </v-card>
        </v-container>
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
            id:'',
            productName:'',
            details:'',
            quantity:'',

            brandName:'',
            brandDetails:'',

            brandPrice:'',

            brandCB: '',
            brandList: [],

            saveBtn:true,

        }
    },
    mounted() {

        // this.id = this.Vid;
        // this.productName = this.VproductName;
        // this.brandCB = this.VbrandCB;
        // this.quantity = this.Vquantity;
        // this.saveBtn = this.Vsbtn;
    
    },

    methods: {

        saveProductDetails(){
            axios.post("/saveProductDetails",{
                brandName    :this.brandName,
                brandDetails :this.brandDetails,
                brandPrice   :this.brandPrice
            }).then(response => {
                alert(response.data);
                this.brandName = ''
                this.brandDetails = ''
                this.brandPrice = ''
            })

        }

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
