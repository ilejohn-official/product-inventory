const {createApp} = Vue

createApp({
  data() {
    return {
      loading: true,
      products: [],
      errorLoading: false,
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
        this.loading = false; 
      })
      .catch( (error) => {
        // handle error
        this.errorLoading = true
        console.log(error);
      })
    },
    massDelete(){
      if(this.form.ids.length < 1){
        this.deleteErrorMessage = "You can only delete selected fields."
        this.massDeleteError = true;
        return;
      }

      axios({
        method: "post",
        url: '/delete-products',
        headers: { 
          "Content-Type": "multipart/form-data" 
        },
        data: {ids: JSON.stringify(this.form.ids)}, 
      })
      .then((response) => {  
        console.log(response);
        this.loading = true;
        this.getAllProducts();
      })
      .catch( (error) => {
        console.log(error);
        this.deleteErrorMessage = "Something went wrong, try again later."
        this.massDeleteError = true;
      })
    },
    toFormData(obj){
      var form = new FormData();
      for (const i in obj){
        form.append(i, obj[i]);
      }
      return form
    }
  }
})
.mount('#app')
