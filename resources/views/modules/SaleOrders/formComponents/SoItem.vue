<template>
    <tr :data="itemData" >
        <td>
            <v-select v-model="itemData.item" @input="itemSelect($event)" name="items" class="my-select"
                      :options="items.map((item,itemIndex)=>({label:item.name,code:item.id,itemIndex}))"></v-select>
        </td>
        <td class="stock-td">{{ itemData.cost}}</td>

        <td><input v-model="itemData.price" type="number" class="form-control" :readonly="!itemData.item"></td>
        <td class="stock-td">{{ itemData.stock }}</td>
        <td><input v-model="itemData.qty" name="qty" type="number" class="form-control" value="0" :readonly="!itemData.item"></td>
        <td><input v-model="itemData.discount" name="discount" type="number" class="form-control" value="0" :readonly="itemData.qty <= 0"></td>
        <td><input v-model="itemData.tax" name="tax" type="number" class="form-control" value="0" :readonly="itemData.qty <= 0"></td>
        <td>{{ Math.round(itemData.total) }}</td>
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
                this.itemData.cost = this.items[itemIndex].last_updated_cost
                this.itemData.price = this.items[itemIndex].last_updated_price
                this.itemData.stock = this.items[itemIndex].last_updated_stock


            } else {
                this.itemData.cost = 0
                this.itemData.price = 0
                this.itemData.stock = 0
            }
        },


    },
    watch: {
        itemData:{
            handler(newVal,oldVal){
                let total = newVal.total
                let price =newVal.price
                let qty= newVal.qty
                total = price*qty
                if(newVal.discount>0){
                    total = (total)-(total/100)*newVal.discount
                }
                if(newVal.tax>0){
                    total = (total)+total/100*newVal.tax
                }
                newVal.total = total
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
