<template>
    <div class="form-group col-md-4">

        <label>Customers <span class="tx-danger">*</span></label>
        <v-select  @input="customerSelect($event)"  class="my-select form-control" v-bind:class="{true:errorclass}"
                  :options="customers.map((customer,Index)=>({label:customer.name,code:customer.id,Index,customer}))"></v-select>
        <div class="tx-danger" v-if="errormsg">{{ errormsg }}</div>

        <input type="hidden" v-if="customer" v-model="customer.id" name="customer_id" />
    </div>
</template>
<script>
import Bus from './../../../bus';

export default {
    name: 'CustomerDropDown',
    props: {
        customers:{
            required:true,
            default:[]
        },
        errorclass:String,
        errormsg:String,
    },
    data(){
        return {
            customer:null
        }
    },
    methods:{
        customerSelect(ev){
            if(ev){
                this.customer = ev.customer;
                Bus.$emit('onCustomerSelect',ev.customer);
            }else{
                Bus.$emit('onCustomerSelect',null);
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

