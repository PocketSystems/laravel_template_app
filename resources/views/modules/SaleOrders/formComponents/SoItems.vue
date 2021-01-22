<template>
    <div>
        <input type="text" name="so" :value="soOrders_data" hidden />
        <input type="text" name="count" :value="count" hidden />
        <input type="text" name="grandTotal" :value="grandTotal" hidden />
        <input type="text" name="discount_total" :value="discount_total" hidden />
        <div class="card card-body" style="padding-bottom: 5px;border-bottom-left-radius: unset;border-bottom-right-radius:unset ">
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
                        <th width="10%">Price</th>
                        <th width="5%">Stock</th>
                        <th width="10%">Qty</th>
                        <th width="10%">Discount %</th>
                        <th width="7%">Tax %</th>
                        <th width="10%">Total</th>
                        <th width="10%">Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <so-item @onDelete='onDeleteItem' :items="JSON.parse(items)"
                             v-for="(so,index) in soOrders" :key="index" :so="so" :index="index"
                             @change="soItemChange"/>
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
import SoItem from "./SoItem";

const soItems = () => ({
    item: 0,
    cost: 0,
    price: 0,
    stock: 0,
    qty: 0,
    discount: 0,
    discount_amount: 0,
    tax: 0,
    total: 0
});

export default {
    name: "SoItems",
    components: {SoItem},
    props: ['items'],
    data() {
        return {
            grandTotal: 0,
            discount_total: 0,
            count: 0,
            soOrders: [
                soItems(),
                soItems()
            ],
            soOrders_data:[]

        }
    },
    methods: {
        onItemTotal(total, index) {
            this.total = total;
        },
        onAddItem() {
            this.soOrders.push(soItems());
        },
        onDeleteItem(index) {
            this.soOrders.splice(index, 1)
        },
        soItemChange(index, itemData) {
            this.soOrders[index] = itemData;
            this.count = this.soOrders.filter((item)=>item.item).length
            this.discount_total = this.soOrders.map((item)=>item.discount_amount).reduce((a, b) => a + b, 0)
            this.grandTotal = this.soOrders.map((item)=>item.total).reduce((a, b) => a + b, 0)
            this.soOrders_data = JSON.stringify(this.soOrders)
        }
    },
    watch: {
        soOrders:{
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
