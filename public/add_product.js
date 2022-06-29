const {shallowRef, createApp} = Vue
const form = {
  // name: "",
  // sku: "",
  // price: 0.00,
  // attribute_key: "",
   attribute_value: {},
  // attribute_unit: ""
}
let errors = {}

const DVD = shallowRef({
  data(){
    return {
      form: form,
      errors : errors
    }
  },
  template: `
    <div class="form-group row" id="DVD">
      <label for="size" class="col-sm-2 col-form-label">Size (MB)</label>
      <div class="col-sm-4">
        <input type="number" class="form-control" :class="{'is-invalid' : errors.size || errors.attribute }" id="size" min="0.01" step="0.01" v-model="form.attribute_value.size">
        <small><i>Please provide size in MB </i></small>
        <div class="invalid-feedback">
            {{errors.size || errors.attribute}}
        </div>
      </div>
    </div>`
})

const Book = shallowRef({
  data(){
    return {
      form: form,
      errors : errors
    }
  },
  template: `
  <div class="form-group row" id="Book">
      <label for="weight" class="col-sm-2 col-form-label">Weight (KG)</label>
      <div class="col-sm-4">
        <input type="number" class="form-control" :class="{'is-invalid' : errors.weight || errors.attribute }" id="weight" min="0.01" step="0.01" v-model="form.attribute_value.weight">
        <small><i>Please provide weight in KG </i></small>
        <div class="invalid-feedback">
            {{errors.weight || errors.attribute}}
        </div>
      </div>
  </div>`})

const Furniture = shallowRef({
  data(){
    return {
      form: form,
      errors : errors
    }
  },
  template: `<div id="Furniture">
      <div class="form-group row" >
        <label for="height" class="col-sm-2 col-form-label">Height (CM)</label>
        <div class="col-sm-4">
        <input type="number" class="form-control" :class="{'is-invalid' : errors.height || (errors.attribute && !form.attribute_value.height) }" id="height" min="0.01" step="0.01" v-model="form.attribute_value.height">
        <div class="invalid-feedback">
            {{errors.height || errors.attribute}}
        </div>
        </div>
      </div>
      <div class="form-group row">
        <label for="width" class="col-sm-2 col-form-label">Width (CM)</label>
        <div class="col-sm-4">
        <input type="number" class="form-control" :class="{'is-invalid' : errors.width || (errors.attribute && !form.attribute_value.width) }" id="width" min="0.01" step="0.01" v-model="form.attribute_value.width">
        <div class="invalid-feedback">
            {{errors.width || errors.attribute}}
        </div>
        </div>
      </div>
      <div class="form-group row">
        <label for="length" class="col-sm-2 col-form-label">Length (CM)</label>
        <div class="col-sm-4">
        <input type="number" class="form-control" :class="{'is-invalid' : errors.lenght || (errors.attribute && !form.attribute_value.lenght) }" id="length" min="0.01" step="0.01" v-model="form.attribute_value.lenght">
        <div class="invalid-feedback">
            {{errors.lenght || errors.attribute}}
        </div>
        <small><i>Please provide dimensions in HxWxL </i></small>
        </div>
      </div>
      </div>`
})

createApp({
  data() {
    return {
      component: {
        DVD,Book,Furniture
      },
      loading: true,
      errorLoading: false,
      addProductError: false,
      addProductErrorMessage: "",
      productTypes: [
        {key: 'Size', unit: 'MB', id: 'DVD', measureCount: 1},
        {key: 'Weight', unit: 'KG', id: 'Book', measureCount: 1},
        {key: 'Dimension', unit: 'CM', id: 'Furniture', measureCount: 3}
      ],
      productType: {},
      form : form,
      errors : errors
    }
  },
  mounted() {
    this.loading = false
  },
  methods: {
    clearAttribute(event){
      form.attribute_value = [];
    },
    isNumeric(n) {
      return !isNaN(parseFloat(n)) && isFinite(n);
    },
    validateForm(){
      if(!this.isNumeric(form.amount) || parseFloat(form.amount) < 0.01){
        this.errors.amount = "Price must be a valid number greater than 0"
      }
      if(!form.name || form.name.length < 2){
        this.errors.name = "Name is required"
      }
      if(!form.sku || form.sku.length < 2){
        this.errors.sku = "SKU is required"
      }
      if(this.objLength(this.productType) < 1){
        this.errors.attribute_value = "Product type is required"
      }
      if (this.objLength(form.attribute_value) !== this.productType.measureCount) {
        this.errors.attribute = `${this.objLength(this.productType) > 0 ? this.productType.id+' '+this.productType.key : 'Product measure'} is required`
      }
      if (this.objLength(form.attribute_value) === this.productType.measureCount) {
        let object = form.attribute_value;

        for (const key in object) {
          if (!this.isNumeric(object[key]) || parseFloat(object[key]) < 0.01) {
            this.errors[key] = `${key} must be valid number greater than 0`
          }
        }
      }
      
    },
    parseAttribute(object){
      return Object.values(object).join('x')
    },
    hasErrors(){
      return this.objLength(this.errors) > 0
    },
    objLength(object) {
      return Object.keys(object).length
    },
    addProduct(){

      this.validateForm()

      if (this.hasErrors()) {
        setTimeout(() => {
          for (const key in this.errors){
            this.errors[key] = '';
          }
        }, 5000);
        return;
      }

      form.attribute_key = this.productType.key
      form.attribute_unit = this.productType.unit
      form.price = form.amount
      delete form.amount
      form.attribute_value = this.parseAttribute(form.attribute_value)

      axios({
        method: "post",
        url: '/products',
        headers: { 
          "Content-Type": "multipart/form-data" 
        },
        data: form,
      })
      .then(() => {
        window.location.href = '/';
      })
      .catch( (error) => {
        const err = error.response.data.data;
        
        if (this.objLength(err) > 0){
          for (const key in err){
            this.errors[key] = err[key]
          }

          setTimeout(() => {
            for (const key in this.errors){
              this.errors[key] = '';
            }
          }, 5000);
        } else {
          this.addProductErrorMessage = "Something went wrong, try again later."
          this.addProductError = true;
        }
      })
    }
  }
})
.mount('#app')
