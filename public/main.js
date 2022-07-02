const {createApp} = Vue

createApp({
  data() {
    return {
      products: [],
      massDeleteError: false,
      deleteErrorMessage: "",
      form : {
        ids : []
      }
    }
  },
  mounted() {
    this.getAllProducts();
  },
  methods: {
    getAllProducts(){
      axios.get('/products')
      .then((response) => {  
        this.products = response.data.data;
      })
      .catch( (error) => {
        // handle error
        console.log(error);
      })
    },
    massDelete(){
      if(this.form.ids.length < 1){
        this.deleteErrorMessage = "You can only delete selected fields."
        this.massDeleteError = true;
        setTimeout(() => {
          this.massDeleteError = false;
        },3000)
        return;
      }

      for (const product in this.products){
        if (Object.values(this.form.ids).includes(this.products[product].id)){
          delete this.products[product]
        }
      }

      axios({
        method: "post",
        url: '/delete-products',
        headers: { 
          "Content-Type": "multipart/form-data" 
        },
        data: {ids: JSON.stringify(this.form.ids)}, 
      })
      .then(() => {
      })
      .catch(() => {
        this.deleteErrorMessage = "Something went wrong, try again later."
        this.massDeleteError = true;
      })
      .finally(() => {
        this.getAllProducts();
      })
    }
  }
})
.mount('#app')
