const {createApp} = Vue

createApp({
  data() {
    return {
      loading: true,
      products: [],
      errorLoading: false
    }
  },
  mounted() {

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
    
  }
})
.mount('#app')
