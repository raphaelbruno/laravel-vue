<template>
    <div v-bind:id="id" class="subitem">
        <div class="form-group">
            <label>{{ label }}</label>
            <div class="card card-outline card-secondary">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover m-0 subitems">
                            <thead>
                                <tr>
                                    <th>{{ trans('crud.title') }}</th>
                                    <th class="text-center actions">{{ trans('crud.delete') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(item, index) in list" v-bind:key="index">
                                    <td class="align-middle">
                                        <select v-model="item.id" v-bind:name="`${validName}[]`" v-select2 class="form-control select2 w-100">
                                            <option v-for="(option, optionKey) in optionList"
                                                v-bind:value="option.key">
                                                {{ option.value }}
                                            </option>
                                        </select>
                                    </td>
                                    <td class="align-middle text-center">
                                        <button type="button" v-bind:title="trans('crud.delete')" v-on:click="deleteItem(index)" class="btn btn-danger"><i class="fas fa-trash"></i></button>
                                    </td>
                                </tr>
                                <tr v-if="list.length < 1">
                                    <td class="align-middle" colspan="2">{{ trans('admin.no-items') }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer">
                        <button type="button" v-bind:title="trans('crud.new')" v-on:click="addItem" class="btn btn-sm btn-success float-left">
                            <i class="fas fa-plus"></i> {{ trans('crud.new') }}
                        </button>
                        <div class="float-right">
                            {{ trans('crud.total') }}: <span class="total">0</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['id', 'name', 'options', 'label', 'addedItems'],
        data(){
            return {
                optionList: [],
                list: [],
            }
        },
        mounted(){
            let items = this.addedItems ? JSON.parse(this.addedItems) : [];
            if(items.length > 0 && typeof items[0] != 'object')
                for(let i = 0; i < items.length; i++)
                    items[i] = { id: items[i] };

            this.optionList = this.options ? JSON.parse(this.options) : [];
            this.list = items;
        },
        watch: {
            list(value){
                $(function(){
                    $('.subitem select.select2[data-select2-id]').select2('destroy');
                    $('.subitem select.select2').select2();
                })
            }
        },
        computed: {
            validName(){
                return this.name ? this.name : 'subitems';
            }
        },
        methods: {
            addItem(){
                this.list.push({});
            },
            deleteItem(index){
                this.list.splice(index, 1);
            }
        }
    }
</script>

<style>
    .subitem .actions { width: 100px; }
</style>