<template>
    <tr :data="itemData" >
        <td>
            <v-select v-model="itemData.item" @input="itemSelect($event)" name="items" class="my-select"
                      :options="items.map((item,itemIndex)=>({label:item.name,code:item.id,itemIndex}))"></v-select>
        </td>
        <td><input  v-model="itemData.cost" name="cost" type="number" class="form-control" :readonly="!itemData.item"></td>
        <td><input v-model="itemData.price" type="number" class="form-control" :readonly="!itemData.item"></td>
        <td class="stock-td">{{ itemData.stock }}</td>
        <td><input v-model="itemData.qty" name="qty" type="number" class="form-control" value="0" :readonly="!itemData.item"></td>
        <td>{{ itemData.cost_total }}</td>
        <td class="trash-td"><i v-on:click="$emit('onDelete',index)" style="color:red;cursor: pointer" class="fas fa-trash"></i></td>

    </tr>
</template>

<script>

export default {

    name: "PoItem",
    props: ['items', 'index', 'po'],
    data() {
        return {
            itemData:{...this.po}
        }
    },
    methods: {
        itemSelect(e) {
            if (e) {
                const {itemIndex} = e;
                this.itemData.cost = this.items[itemIndex].inventory_po.unit_cost
                this.itemData.price = this.items[itemIndex].inventory_po.unit_price
                this.itemData.stock = this.items[itemIndex].inventory_qty


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
                newVal.total = newVal.price*newVal.qty
                newVal.cost_total = newVal.cost*newVal.qty
/*                if(parseInt(newVal.stock) < parseInt(newVal.qty)){
                    newVal.qty = newVal.stock;
                }*/
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
