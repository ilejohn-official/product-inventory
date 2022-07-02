const {ref, createApp} = Vue

createApp({
  data() {
    let ids = ref([]);
    return {
      products: [],
      massDeleteError: false,
      deleteErrorMessage: "",
      form : {
        ids : ids
      }
    }
  },
  computed: {
    hasProducts(){
      return Object.keys(this.products).length > 0
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

      let ids = [];

      let boxes = document.querySelectorAll("input.delete-checkbox")
      for (let i = 0; i < boxes.length; i++) {
        if(boxes[i].checked === true){
          ids.push(Number(boxes[i].value))
          boxes[i].remove()
        }
      }

      for (const product in this.products){
        if (ids.includes(this.products[product].id)){
          delete this.products[product]
        }
      }

      let checkboxes = document.getElementsByClassName('delete-checkbox');
      for (var i = checkboxes.length; i--;) {
        if(checkboxes[i].checked === true){
          checkboxes[i].remove()
        }
      }

//window.location.href = '/';

      axios({
        method: "post",
        url: '/delete-products',
        headers: { 
          "Content-Type": "multipart/form-data" 
        },
        data: {ids: JSON.stringify(ids)}, 
      })
      .then(() => {
      })
      .catch(() => {
        this.deleteErrorMessage = "Something went wrong, try again later."
        this.massDeleteError = true;
      })
      .finally(() =>{
        this.getAllProducts();
      })
    }
  }
})
.mount('#app')
