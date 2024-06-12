<script setup>
import {onMounted, ref} from "vue";
import axios from "axios";
import router from "../../router";

let form = ref({
    date: '',
    due_date: '',
    number: '',
    reference: '',
    discount: 0,
    terms_and_conditions: ''
});
let allcustomers = ref([])
let customer_id = ref([])
let item = ref([])
let listCart = ref([])
const showModal = ref(false)
const hideModal = ref(true)
let listProduct = ref([])


onMounted(async () => {
    indexForm()
    getAllCustomers()
    getProducts()
})

const indexForm = async () => {
    let response = await axios.get('/api/create_invoice')
    console.log('form', response.data);
}

const getAllCustomers = async () => {
    let response = await axios.get('/api/customers')
    allcustomers.value = response.data.customers
}

const addCart = (item) => {
    const itemcart = {
        id: item.id,
        item_code: item.item_code,
        description: item.description,
        unit_price: item.unit_price,
        quantity: 1
    };
    listCart.value.push(itemcart);
    closeModal();
};

const removeItem = (i) => {
    listCart.value.splice(i, 1)
}

const openModal = () => {
    showModal.value = !showModal.value
}
const closeModal = () => {
    showModal.value = !hideModal.value
}

const getProducts = async () => {
    let response = await axios.get('/api/products')
    // console.log('products' , response);
    listProduct.value = response.data.products
}

const SubTotal = () => {
    let total = 0;
    listCart.value.forEach(data => {
        total += data.quantity * data.unit_price;
    });
    return total;
};

const Total = () => {
    const subtotal = SubTotal();
    const discount = form.value.discount ? form.value.discount : 0;
    return subtotal - discount;
};
const onSave = async () => {
    if (listCart.value.length >= 1) {
        const subtotal = SubTotal();
        const total = Total();

        const formData = new FormData();
        formData.append('invoice_item', JSON.stringify(listCart.value));
        formData.append('customer_id', customer_id.value);
        formData.append('date', form.value.date);
        formData.append('due_date', form.value.due_date);
        formData.append('number', form.value.number);
        formData.append('reference', form.value.reference);
        formData.append('discount', form.value.discount);
        formData.append('subtotal', subtotal);
        formData.append('total', total);
        formData.append('terms_and_conditions', form.value.terms_and_conditions);

        try {
            await axios.post('/api/add_invoice', formData);
            listCart.value = [];
            router.push('/');
        } catch (error) {
            console.error('Error saving invoice:', error);
        }
    }
};

</script>

<template>
    <div class="container">
        <div class="invoices">

            <div class="card__header">
                <div>
                    <h2 class="invoice__title">New Invoice</h2>
                </div>
                <div>

                </div>
            </div>

            <div class="card__content">
                <div class="card__content--header">
                    <div>
                        <p class="my-1">Customer</p>
                        <select name="" id="" class="input" v-model="customer_id">
                            <option disabled value="">Select a customer</option>
                            <option :value="customer.id" v-for="customer in allcustomers" :key="customer.id">
                                {{ customer.firstname }}
                            </option>
                        </select>
                    </div>
                    <div>
                        <p class="my-1">Date</p>
                        <input id="date" placeholder="dd-mm-yyyy" type="date" class="input" v-model="form.date">
                        <p class="my-1">Due Date</p>
                        <input id="due_date" type="date" class="input" v-model="form.due_date">
                    </div>
                    <div>
                        <p class="my-1">Numero</p>
                        <input type="text" class="input" v-model="form.number">
                        <p class="my-1">Reference(Optional)</p>
                        <input type="text" class="input" v-model="form.reference">
                    </div>
                </div>
                <br><br>
                <div class="table">

                    <div class="table--heading2">
                        <p>Item Description</p>
                        <p>Unit Price</p>
                        <p>Qty</p>
                        <p>Total</p>
                        <p></p>
                    </div>

                    <!-- item 1 -->
                    <div class="table--items2" v-for="(itemcart , i)  in listCart" :key="itemcart.id">
                        <p> # {{ itemcart.item_code }} {{ itemcart.description }}</p>
                        <p>
                            <input type="text" class="input" v-model.number="itemcart.unit_price">
                        </p>
                        <p>
                            <input type="text" class="input" v-model.number="itemcart.quantity">
                        </p>
                        <p v-if="itemcart.quantity">
                            $ {{ (itemcart.quantity) * (itemcart.unit_price) }}
                        </p>
                        <p v-else>

                        </p>
                        <p style="color: red; font-size: 24px;cursor: pointer;" @click="removeItem(i)">
                            &times;
                        </p>
                    </div>
                    <div style="padding: 10px 30px !important;">
                        <button class="btn btn-sm btn__open--modal" @click="openModal()">
                            Add New Line
                        </button>
                    </div>
                </div>

                <div class="table__footer">
                    <div class="document-footer">
                        <p>Terms and Conditions</p>
                        <textarea cols="50" rows="7" class="textarea" v-model="form.terms_and_conditions"></textarea>
                    </div>
                    <div>
                        <div class="table__footer--subtotal">
                            <p>Sub Total</p>
                            <span>$ {{ SubTotal() }}</span>
                        </div>
                        <div class="table__footer--discount">
                            <p>Discount</p>
                            <input type="text" class="input">
                        </div>
                        <div class="table__footer--total">
                            <p>Grand Total</p>
                            <span>$ {{ Total() }}</span>
                        </div>
                    </div>
                </div>


            </div>
            <div class="card__header" style="margin-top: 40px;">
                <div>

                </div>
                <div>
                    <a class="btn btn-secondary" @click="onSave()">
                        Save
                    </a>
                </div>
            </div>

        </div>
        <!--==================== add modal items ====================-->
        <div class="modal main__modal" :class="{show: showModal}">
            <div class="modal__content">
                <span class="modal__close btn__close--modal" @click="closeModal">×</span>
                <h3 class="modal__title">Add Item</h3>
                <hr>
                <br>
                <div class="modal__items">
                    <ul style="list-style: none">
                        <li v-for="(item,i) in listProduct" :key="item.id"
                            style="display: grid;grid-template-columns: 30px 350px 15px;align-items: center;padding-bottom: 5px;">
                            <p>
                                {{ i + 1 }}
                            </p>
                            <a href="">
                                {{ item.item_code }} {{ item.description }}
                            </a>
                            <button @click="addCart(item)"
                                    style="border: 1px solid #e0e0e0;width: 35px;height: 35px;cursor: pointer;">
                                +
                            </button>
                        </li>
                    </ul>
                </div>
                <br>
                <hr>
                <div class="model__footer">
                    <button class="btn btn-light mr-2 btn__close--modal" @click="closeModal">
                        Cancel
                    </button>
                    <button class="btn btn-light btn__close--modal ">Save</button>
                </div>
            </div>
        </div>

    </div>
</template>

<style scoped>

</style>
