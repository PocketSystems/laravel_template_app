<template>
    <div>
        <input type="text" name="po" :value="poOrders_data" hidden />
        <input type="text" name="grandTotal" :value="grandTotal" hidden />
        <input type="text" name="grandCostTotal" :value="grandCostTotal" hidden />
        <div class="panel" style="padding-bottom: 5px;border-bottom-left-radius: unset;border-bottom-right-radius:unset ">
            <div style="display: flex" class="mb-3">
                <div style="flex: 1">
                    <h4 id="section1" class="mg-b-10">Add Items</h4>
                </div>
                <div>
                    <a href="javascript:" v-on:click="onAddItem" class="btn btn-primary btn-icon">
                        <i data-feather="plus"></i>
                    </a>
                </div>
            </div>
            <div>

                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th width="50%">Item</th>
                        <th width="10%">Cost</th>
                        <th width="10%">Price</th>
                        <th width="5%">Stock</th>
                        <th width="10%">Qty</th>
                        <th width="10%">Total</th>
                        <th width="10%">Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <po-item @onDelete='onDeleteItem' :items="JSON.parse(items)"
                             v-for="(po,index) in poOrders" :key="index" :po="po" :index="index"
                             @change="poItemChange"/>
                    </tbody>

                </table>

                <!--            <h1>{{total}}</h1>-->
            </div>
        </div>
        <div class="bottom-panel-td">
            <div class="row">
                <div class="col-md-1 offset-md-10 "><span class="bottom-panel-text">Total</span></div>
                <div class="col-md-1 "><span class="bottom-panel-text"><strong>{{ grandTotal }}</strong></span></div>
            </div>
        </div>
    </div>
</template>

<script>
import PoItem from "./PoItem";

const poItems = () => ({
    item: 0,
    cost: 0,
    price: 0,
    stock: 0,
    qty: 0,
    total: 0,
    cost_total: 0
});

export default {
    name: "PoItems",
    components: {PoItem},
    props: ['items'],
    data() {
        return {
            grandTotal: 0,
            grandCostTotal: 0,
            poOrders: [
                poItems(),
                poItems()
            ],
            poOrders_data:[]

        }
    },
    methods: {
        onItemTotal(total, index) {
            this.total = total;
        },
        onAddItem() {
            this.poOrders.push(poItems());
        },
        onDeleteItem(index) {
            this.poOrders.splice(index, 1)
        },
        poItemChange(index, itemData) {
            this.poOrders[index] = itemData;
            console.log(this.poOrders.map((item)=>item.total))
            this.grandTotal = this.poOrders.map((item)=>item.total).reduce((a, b) => a + b, 0)
            this.grandCostTotal = this.poOrders.map((item)=>item.cost_total).reduce((a, b) => a + b, 0)
            this.poOrders_data = JSON.stringify(this.poOrders)
        }
    },
    watch: {
        poOrders:{
            handler(newVal,oldVal){
                this.$emit("change", newVal)
            },
            deep: true
        }
    }
}
</script>

<style>
.bottom-panel-td{
    background: #eaeaea;padding: 5px
}.bottom-panel-text{
     font-size: 20px;
 }
</style>
