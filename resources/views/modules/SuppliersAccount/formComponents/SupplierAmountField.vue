<template>
    <div class="form-group col-md-4">
        <label for="inputEmail4" style="width: 100%">Amount <span v-if="balance != null" class="fa-pull-right">Payable Amount <strong>{{balance-amount}}</strong></span></label>
        <input  type="number" name="amount" v-model="amount"
               class="form-control" id="inputEmail4"
               placeholder="Please enter amount" v-bind:class="{true:errorclass}">
        <div class="tx-danger" v-if="errormsg">{{ errormsg }}</div>
        <input type="hidden" name="balance" :value="balance-amount">

    </div>
</template>
<script>
import Bus from './../../../bus';

export default {
    name: 'SupplierAmountField',
    props: {

        errorclass:String,
        errormsg:String,
    },
    data(){
      return {
          balance:null,
          amount:0
      }
    },
    mounted() {
        Bus.$on('onSupplierSelect',  (supplier)=>{
            if(supplier){
                this.balance = supplier.balance;
            }else{
                this.balance =null
            }
        });
    },
}
</script>
