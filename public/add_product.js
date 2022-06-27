const {createApp} = Vue

createApp({
  data() {
    return {
      loading: true,
      errorLoading: false,
      addProductError: false,
      deleteErrorMessage: "",
      productTypes: [
        {value: 'dvd', text: 'DVD'},
        {value: 'book', text: 'Book'},
        {value: 'furniture', text: 'Furniture'},
      ],
      productType: "",
      form : {
        name: "",
        sku: "",
        price: 0.00,
        attribute_key: "",
        attribute_value: "",
        attribute_unit: ""
      }
    }
  },
  mounted() {
    this.loading = false
  },
  methods: {
    addProduct(){

      axios({
        method: "post",
        url: '/products',
        headers: { 
          "Content-Type": "multipart/form-data" 
        },
        data: {ids: JSON.stringify(this.form.ids)}, 
      })
      .then((response) => {  
        console.log(response);
        this.loading = true;
      })
      .catch( (error) => {
        console.log(error);
        this.deleteErrorMessage = "Something went wrong, try again later."
        this.massDeleteError = true;
      })
    }
  }
})
.mount('#app')
