<template>
    <div>
        <div class="section">
            <div class="columns is-centered">
                <div class="column is-6-desktop is-8-tablet">
                    <div class="box">

                        <div class="is-flex is-justify-content-center mb-2" style="font-size: 20px; font-weight: bold;">INVENTORY ADJUSTMENT</div>

                        <div class="columns">
                            <div class="column">
                                <b-field label="Page" label-position="on-border">
                                    <b-select v-model="perPage" @input="setPerPage">
                                        <option value="5">5 per page</option>
                                        <option value="10">10 per page</option>
                                        <option value="15">15 per page</option>
                                        <option value="20">20 per page</option>
                                    </b-select>
                                    <b-select v-model="sortOrder" @input="loadAsyncData">
                                        <option value="asc">ASC</option>
                                        <option value="desc">DESC</option>

                                    </b-select>
                                </b-field>
                            </div>

                            <div class="column">
                                <b-field label="Item Name" expanded label-position="on-border">
                                    <b-input type="text" expanded
                                             v-model="search.item_name" placeholder="Search Item Name"
                                             @keyup.native.enter="loadAsyncData"/>
                                    <p class="control">
                                        <b-tooltip label="Search" type="is-success">
                                            <b-button type="is-primary"  icon-right="account-filter" @click="loadAsyncData"/>
                                        </b-tooltip>
                                    </p>
                                </b-field>
                            </div>
                        </div>


                        <b-table
                            :data="data"
                            :loading="loading"
                            paginated
                            backend-pagination
                            :total="total"
                            :per-page="perPage"
                            @page-change="onPageChange"
                            aria-next-label="Next page"
                            aria-previous-label="Previous page"
                            aria-page-label="Page"
                            aria-current-label="Current page"
                            backend-sorting
                            :default-sort-direction="defaultSortDirection"
                            @sort="onSort">

                            <b-table-column field="inventory_adjustment_id" label="ID" v-slot="props">
                                {{ props.row.inventory_adjustment_id }}
                            </b-table-column>

                            <b-table-column field="item" label="Item Name" v-slot="props">
                                {{ props.row.item.item_name }}
                            </b-table-column>

                            <b-table-column field="adjusted_qty" label="Adjusted Qty" v-slot="props">
                                {{ props.row.adjusted_qty }}
                            </b-table-column>

                            <b-table-column field="current_qty" label="Current Qty" v-slot="props">
                                {{ props.row.current_qty }}
                            </b-table-column>

                            <b-table-column field="adjustment" label="Adjustment" v-slot="props">
                                {{ props.row.adjustment }}
                            </b-table-column>

                            <b-table-column field="remarks" label="Remarks" v-slot="props">
                                {{ props.row.remarks }}
                            </b-table-column>

                            <!-- <b-table-column label="Action" v-slot="props">
                                <div class="is-flex">
                                    <b-tooltip label="Edit" type="is-warning">
                                        <b-button class="button is-small is-warning mr-1" tag="a" icon-right="pencil" @click="getData(props.row.inventory_id)"></b-button>
                                    </b-tooltip>
                                    <b-tooltip label="Delete" type="is-danger">
                                        <b-button class="button is-small is-danger mr-1" icon-right="delete" @click="confirmDelete(props.row.inventory_id)"></b-button>
                                    </b-tooltip>
                                </div>
                            </b-table-column> -->
                        </b-table>

                        <div class="buttons mt-3">
                            <b-button tag="a"
                                href="/inventory-adjustment/create"
                                icon-right="account-arrow-up-outline" 
                                class="is-success">CREATE ADJUSTMENT</b-button>
                        </div>

                    </div>
                </div><!--col -->
            </div><!-- cols -->
        </div><!--section div-->


        <!--modal create-->
        <b-modal v-model="isModalCreate" has-modal-card
            trap-focus
            :width="640"
            aria-role="dialog"
            aria-label="Modal"
            aria-modal>

            <form @submit.prevent="submit">
                <div class="modal-card">
                    <header class="modal-card-head">
                        <p class="modal-card-title">Adjustment Information</p>
                        <button
                            type="button"
                            class="delete"
                            @click="isModalCreate = false"/>
                    </header>

                    <section class="modal-card-body">
                        <div class="">
                            <div class="columns">
                                <div class="column">
                                    <b-field label="Barcode" label-position="on-border"
                                             :type="this.errors.barcode ? 'is-danger':''"
                                             :message="this.errors.barcode ? this.errors.barcode[0] : ''">
                                        <b-input v-model="fields.barcode"
                                                 placeholder="Barcode">
                                        </b-input>
                                    </b-field>
                                </div>
                                <div class="column">
                                    <b-field label="Serial" label-position="on-border"
                                             :type="this.errors.serial ? 'is-danger':''"
                                             :message="this.errors.serial ? this.errors.serial[0] : ''">
                                        <b-input v-model="fields.serial"
                                                 placeholder="Serial">
                                        </b-input>
                                    </b-field>
                                </div>
                            </div>

                            <div class="columns">
                                <div class="column">
                                    <b-field label="Brand" label-position="on-border"
                                             :type="this.errors.brand ? 'is-danger':''"
                                             :message="this.errors.brand ? this.errors.brand[0] : ''">
                                        <b-input v-model="fields.brand"
                                                 placeholder="Brand">
                                        </b-input>
                                    </b-field>
                                </div>
                                <div class="column">
                                    <b-field label="Model" label-position="on-border"
                                             :type="this.errors.model ? 'is-danger':''"
                                             :message="this.errors.model ? this.errors.model[0] : ''">
                                        <b-input v-model="fields.model"
                                                 placeholder="Model">
                                        </b-input>
                                    </b-field>
                                </div>
                            </div>

                            <div class="columns">
                                <div class="column">
                                    <b-field label="Item Name" label-position="on-border"
                                             :type="this.errors.item_name ? 'is-danger':''"
                                             :message="this.errors.item_name ? this.errors.item_name[0] : ''">
                                        <b-input v-model="fields.item_name"
                                                 placeholder="Item Name" required>
                                        </b-input>
                                    </b-field>
                                </div>
                            </div>

                            <div class="columns">
                                <div class="column">
                                    <b-field label="Item Description" label-position="on-border"
                                             :type="this.errors.item_description ? 'is-danger':''"
                                             :message="this.errors.item_description ? this.errors.item_description[0] : ''">
                                        <b-input v-model="fields.item_description"
                                                 placeholder="Item Description" required>
                                        </b-input>
                                    </b-field>
                                </div>
                            </div>
                        </div>
                    </section>
                    <footer class="modal-card-foot">
                        <button
                            :class="btnClass"
                            label="Save"
                            type="is-success">SAVE</button>
                        <b-button
                            label="Close"
                            @click="isModalCreate=false"/>

                    </footer>
                </div>
            </form><!--close form-->
        </b-modal>
        <!--close modal-->


    </div>
</template>

<script>

export default{
    data() {
        return{
            data: [],
            total: 0,
            loading: false,
            sortField: 'inventory_adjustment_id',
            sortOrder: 'desc',
            page: 1,
            perPage: 5,
            defaultSortDirection: 'asc',

            global_id : 0,

            search: {
                item_name: '',
                barcode: '',
                serial: '',
            },

            isModalCreate: false,

            fields: {
                barcode : '',
                serial: '',
                model: '',
                brand: '',
                item_name: '',
                item_description: '',
            },
            errors: {},


            btnClass: {
                'is-success': true,
                'button': true,
                'is-loading':false,
            },

        }

    },

    methods: {
        /*
        * Load async data
        */
        loadAsyncData() {
            const params = [
                `sort_by=${this.sortField}.${this.sortOrder}`,
                `item=${this.search.item_name}`,
                `perpage=${this.perPage}`,
                `page=${this.page}`
            ].join('&')

            this.loading = true
            axios.get(`/get-inventory-adjustments?${params}`)
                .then(({ data }) => {
                    this.data = [];
                    let currentTotal = data.total
                    if (data.total / this.perPage > 1000) {
                        currentTotal = this.perPage * 1000
                    }

                    this.total = currentTotal
                    data.data.forEach((item) => {
                        //item.release_date = item.release_date ? item.release_date.replace(/-/g, '/') : null
                        this.data.push(item)
                    })
                    this.loading = false
                })
                .catch((error) => {
                    this.data = []
                    this.total = 0
                    this.loading = false
                    throw error
                })
        },
        /*
        * Handle page-change event
        */
        onPageChange(page) {
            this.page = page
            this.loadAsyncData()
        },

        onSort(field, order) {
            this.sortField = field
            this.sortOrder = order
            this.loadAsyncData()
        },

        setPerPage(){
            this.loadAsyncData()
        },

        openModal(){
            this.isModalCreate=true;
            this.fields = {};
            this.errors = {};
        },



        submit: function(){
            if(this.global_id > 0){
                //update
                axios.put('/items/'+this.global_id, this.fields).then(res=>{
                    if(res.data.status === 'updated'){
                        this.$buefy.dialog.alert({
                            title: 'UPDATED!',
                            message: 'Successfully updated.',
                            type: 'is-success',
                            onConfirm: () => {
                                this.loadAsyncData();
                                this.clearFields();
                                this.global_id = 0;
                                this.isModalCreate = false;
                            }
                        })
                    }
                }).catch(err=>{
                    if(err.response.status === 422){
                        this.errors = err.response.data.errors;
                    }
                })
            }else{
                //INSERT HERE
                axios.post('/items', this.fields).then(res=>{
                    if(res.data.status === 'saved'){
                        this.$buefy.dialog.alert({
                            title: 'SAVED!',
                            message: 'Successfully saved.',
                            type: 'is-success',
                            confirmText: 'OK',
                            onConfirm: () => {
                                this.isModalCreate = false;
                                this.loadAsyncData();
                                this.clearFields();
                                this.global_id = 0;
                            }
                        })
                    }
                }).catch(err=>{
                    if(err.response.status === 422){
                        this.errors = err.response.data.errors;
                    }
                });


            }
        },


        //alert box ask for deletion
        confirmDelete(delete_id) {
            this.$buefy.dialog.confirm({
                title: 'DELETE!',
                type: 'is-danger',
                message: 'Are you sure you want to delete this data?',
                cancelText: 'Cancel',
                confirmText: 'Delete?',
                onConfirm: () => this.deleteSubmit(delete_id)
            });
        },
        //execute delete after confirming
        deleteSubmit(delete_id) {
            axios.delete('/items/' + delete_id).then(res => {
                this.loadAsyncData();
            }).catch(err => {
                if (err.response.status === 422) {
                    this.errors = err.response.data.errors;
                }
            });
        },

        clearFields(){
            this.fields = {
                barcode : '',
                serial: '',
                model: '',
                brand: '',
                item_name: '',
                item_description: '',
            };
        },


        //update code here
        getData: function(data_id){
            this.clearFields();
            this.global_id = data_id;
            this.isModalCreate = true;


            //nested axios for getting the address 1 by 1 or request by request
            axios.get('/items/'+data_id).then(res=>{
                this.fields = res.data;
            });
        },



    },

    mounted() {
        this.loadAsyncData();
    }
}
</script>


<style>


</style>
