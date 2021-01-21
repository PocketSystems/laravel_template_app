<template>
    <div class="form-group col-md-4">

        <label>Suppliers <span class="tx-danger">*</span></label>
        <v-select  @input="supplierSelect($event)"  class="my-select form-control" v-bind:class="{true:errorclass}"
                  :options="suppliers.map((supplier,Index)=>({label:supplier.name,code:supplier.id,Index,supplier}))"></v-select>
        <div class="tx-danger" v-if="errormsg">{{ errormsg }}</div>
        <input type="hidden" v-if="supplier" v-model="supplier.id" name="supplier_id" />
    </div>
</template>
<script>
import Bus from './../../../bus';

export default {
    name: 'SupplierDropDown',
    props: {
        suppliers:{
            required:true,
            default:[]
        },
        errorclass:String,
        errormsg:String,
    },
    data(){
        return {
            supplier:null
        }
    },
    methods:{
        supplierSelect(ev){
            if(ev){
                this.supplier = ev.supplier;
                Bus.$emit('onSupplierSelect',ev.supplier);
            }else{
                Bus.$emit('onSupplierSelect',null);

            }

        }
    }
}
</script>
<style lang="scss">
    .form-group .vs__dropdown-toggle{
        border: unset;
        margin-top: -4px;

    }
</style>

