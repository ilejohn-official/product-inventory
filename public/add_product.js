const {shallowRef, createApp} = Vue
let form = {
   productType: {},
   attributeValue: {}
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
        <input type="number" class="form-control" :class="{'is-invalid' : errors.size || errors.attribute }" id="size" min="0.01" step="0.01" v-model="form.attributeValue.size">
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
        <input type="number" class="form-control" :class="{'is-invalid' : errors.weight || errors.attribute }" id="weight" min="0.01" step="0.01" v-model="form.attributeValue.weight">
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
        <input type="number" class="form-control" :class="{'is-invalid' : errors.height || (errors.attribute && !form.attributeValue.height) }" id="height" min="0.01" step="0.01" v-model="form.attributeValue.height">
        <div class="invalid-feedback">
            {{errors.height || errors.attribute}}
        </div>
        </div>
      </div>
      <div class="form-group row">
        <label for="width" class="col-sm-2 col-form-label">Width (CM)</label>
        <div class="col-sm-4">
        <input type="number" class="form-control" :class="{'is-invalid' : errors.width || (errors.attribute && !form.attributeValue.width) }" id="width" min="0.01" step="0.01" v-model="form.attributeValue.width">
        <div class="invalid-feedback">
            {{errors.width || errors.attribute}}
        </div>
        </div>
      </div>
      <div class="form-group row">
        <label for="length" class="col-sm-2 col-form-label">Length (CM)</label>
        <div class="col-sm-4">
        <input type="number" class="form-control" :class="{'is-invalid' : errors.lenght || (errors.attribute && !form.attributeValue.lenght) }" id="length" min="0.01" step="0.01" v-model="form.attributeValue.lenght">
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
      addProductError: false,
      addProductErrorMessage: "",
      productTypes: [
        {key: 'Size', unit: 'MB', id: 'DVD', measureCount: 1},
        {key: 'Weight', unit: 'KG', id: 'Book', measureCount: 1},
        {key: 'Dimension', unit: 'CM', id: 'Furniture', measureCount: 3}
      ],
      form : form,
      errors : errors
    }
  },
  methods: {
    clearAttribute(event){
      form.attributeValue = {};
    },
    isNumeric(n) {
      return !isNaN(parseFloat(n)) && isFinite(n);
    },
    validateForm(){
      if(!this.isNumeric(form.price) || parseFloat(form.price) < 0.01){
        this.errors.price = "Price must be a valid number greater than 0"
      }
      if(!form.name || form.name.length < 2){
        this.errors.name = "Please, submit required data: Name is required"
      }
      if(!form.sku || form.sku.length < 2){
        this.errors.sku = "Please, submit required data: SKU is required"
      }
      if(this.objLength(form.productType) < 1){
        this.errors.attributeValue = "Please, submit required data: Product type is required"
      }

      if (this.objLength(form.attributeValue) !== form.productType.measureCount) {
        this.errors.attribute = `Please, submit required data: ${this.objLength(form.productType) > 0 ? form.productType.id+' '+form.productType.key : 'Product measure'} is required`
      }
      if (this.objLength(form.attributeValue) === form.productType.measureCount) {
        let object = form.attributeValue;

        for (const key in object) {
          if (!this.isNumeric(object[key]) || parseFloat(object[key]) < 0.01) {
            this.errors[key] = `${key} must be valid number greater than 0`
          }
        }
      }
      
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
          this.errors = {}
         
        }, 5000);  
        return;
      }

      axios({
        method: "post",
        url: '/products',
        headers: { 
          "Content-Type": "multipart/form-data" 
        },
        data: {...form, attributeValue : JSON.stringify(form.attributeValue)},
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
            this.errors = {}
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
