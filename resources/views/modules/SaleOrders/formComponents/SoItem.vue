<template>
    <tr :data="itemData" >
        <td data-label="Item">
            <v-select v-model="itemData.item" @input="itemSelect($event)" name="items" class="my-select"
                      :options="items.map((item,itemIndex)=>({label:item.name,code:item.id,itemIndex}))"></v-select>
            <span v-if="itemData.item && !itemData.stock" style="margin-top:5px;color: red">Out of Stock! can't add this item</span>
        </td>

        <td data-label="price"><input v-model="itemData.price" type="number" class="form-control" :readonly="!itemData.item"></td>
        <td data-label="Stock" class="stock-td">{{ itemData.stock }}</td>
        <td data-label="Qty"><input v-model="itemData.qty" name="qty" type="number" class="form-control" value="0" :readonly="!itemData.item"></td>
        <td data-label="Discount"><input v-model="itemData.discount" name="discount" type="number" class="form-control" value="0" :readonly="itemData.qty <= 0"></td>
        <td data-label="Tax"><input v-model="itemData.tax" name="tax" type="number"  class="form-control" value="0" :readonly="itemData.qty <= 0"></td>
        <td data-label="Total">{{ $root.price(Math.round(itemData.total)) }}</td>
        <td class="trash-td"><i v-on:click="$emit('onDelete',index)" style="color:red;cursor: pointer" class="fas fa-trash"></i></td>

    </tr>
</template>

<script>

export default {

    name: "SoItem",
    props: ['items', 'index', 'so'],
    data() {
        return {
            itemData:{...this.so}
        }
    },
    methods: {
        itemSelect(e) {
            if (e) {
                const {itemIndex} = e;
                this.itemData.price = 0
                this.itemData.stock = this.items[itemIndex].inventory_qty


            } else {
                this.itemData.price = 0
                this.itemData.stock = 0
            }
        },


    },
    watch: {
        itemData:{
            handler(newVal,oldVal){
                let total = newVal.total
                let discount_amount = newVal.discount_amount
                let price =newVal.price
                let qty= newVal.qty
                total = price*qty
                if(newVal.discount>0){
                    total = (total)-(total/100)*newVal.discount
                    discount_amount = (total/100)*newVal.discount

                }
                if(newVal.tax>0){
                    total = (total)+total/100*newVal.tax
                }
                if(parseInt(newVal.stock) < parseInt(newVal.qty)){
                    newVal.qty = newVal.stock;
                }
                newVal.total = total
                newVal.discount_amount = discount_amount
                this.$emit("change",this.index, newVal)
            },
            deep: true
        }
    }, mounted: function () {
        console.log(this.itemData)
        console.log(this.items)
    }
}
</script>
<style>
.my-select {
    background: white;
}

td {
    vertical-align: inherit !important;
}

.stock-td,.trash-td {
    text-align: center !important;
}


</style>
