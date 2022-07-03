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
    // let checkboxes = document.getElementsByClassName('delete-checkbox');
    //         for (i=0; i<checkboxes.length;i++){
    //             checkboxes[i].checked=true;
    //         }

    //   $('#delete-product-btn').trigger('click')
   
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
    massDel(){
      setTimeout(() => {
        let checkboxes = document.getElementsByClassName('delete-checkbox');
        for (i=0; i<checkboxes.length;i++){
            checkboxes[i].checked=true;
        }
      this.massDelete()
      },2000)
    },
    massDelete(){
      // if(this.form.ids.length < 1){
      //   this.deleteErrorMessage = "You can only delete selected fields."
      //   this.massDeleteError = true;
      //   setTimeout(() => {
      //     this.massDeleteError = false;
      //   },3000)
      //   return;
      // }

      let ids = [];
      
      let boxes = document.querySelectorAll("input.delete-checkbox")
      for (let i = 0; i < boxes.length; i++) {
        if(boxes[i].checked === true){
          ids.push(Number(boxes[i].value))
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
          checkboxes[i].parentNode.removeChild(checkboxes[i])
        }
      }

      axios({
        method: "post",
        url: '/delete-products',
        headers: { 
          "Content-Type": "multipart/form-data" 
        },
        data: {ids: JSON.stringify(ids)}, 
      })
      .then(() => {
        //window.location.href = '/';
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
