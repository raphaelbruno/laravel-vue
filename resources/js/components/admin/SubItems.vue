<template>
    <div :id="id" class="subitem">
        <div class="form-group">
            <label>{{ label }}</label>
            <div class="card card-outline card-secondary">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover m-0 subitems">
                            <thead>
                                <tr>
                                    <th>{{ trans('crud.title') }}</th>
                                    <th class="align-middle" v-for="(field, fieldKey) in fieldList" :key="fieldKey">
                                        {{ trans(field) }}
                                    </th>
                                    <th class="text-center actions">{{ trans('crud.delete') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(item, key) in list" :key="key">
                                    <td class="align-middle" v-if="isEditable">
                                        <input type="hidden" v-model="item.id" :name="`${validName}[${key}][id]`" />
                                        <input type="text" v-model="item.title" :name="`${validName}[${key}][title]`" v-focus class="form-control" required />
                                        <div class="invalid-feedback">{{trans('crud.invalid-field', [validName])}}</div>
                                    </td>
                                    <td class="align-middle" v-else>
                                        <select v-model="item.id" :name="`${validName}[]`" v-select2 class="form-control select2 w-100">
                                            <option v-for="(option, optionKey) in optionList" :value="option.key" :key="optionKey">
                                                {{ option.value }}
                                            </option>
                                        </select>
                                    </td>
                                    <td class="align-middle" v-for="(field, fieldKey) in fieldList" :key="fieldKey">
                                        <input type="text" v-model="item[fieldKey]" :name="`${validName}[${key}][${fieldKey}]`" class="form-control" />
                                        <div class="invalid-feedback">{{trans('crud.invalid-field', [field])}}</div>
                                    </td>
                                    <td class="align-middle text-center">
                                        <button type="button" :title="trans('crud.delete')" v-on:click="deleteItem(key)" class="btn btn-danger"><i class="fas fa-trash"></i></button>
                                    </td>
                                </tr>
                                <tr v-if="list.length < 1">
                                    <td class="align-middle" colspan="2">{{ trans('admin.no-items') }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer">
                        <button type="button" :title="trans('crud.new')" v-on:click="addItem" class="btn btn-sm btn-success float-left">
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
        props: ['id', 'name', 'options', 'fields', 'label', 'addedItems', 'editable'],
        data(){
            return {
                optionList: [],
                fieldList: [],
                list: [],
            }
        },
        mounted(){
            let items = this.addedItems ? JSON.parse(this.addedItems) : []
            if(items.length > 0 && typeof items[0] != 'object')
                for(let i = 0; i < items.length; i++)
                    items[i] = { id: items[i] }

            this.optionList = this.options ? JSON.parse(this.options) : []
            this.fieldList = this.fields ? JSON.parse(this.fields) : []
            this.list = items
        },
        watch: {
            list(value){
                $(() => {
                    if(!$.fn.select2) return
                    $('.subitem select.select2[data-select2-id]').select2('destroy')
                    $('.subitem select.select2').select2()
                })
            },
        },
        computed: {
            validName(){
                return this.name ? this.name : 'subitems'
            },
            isEditable(){
                return this.editable && this.editable.toLowerCase() != 'false'
            },
        },
        methods: {
            addItem(){
                this.list.push({})
            },
            deleteItem(key){
                this.list.splice(key, 1)
            },
        }
    }
</script>

<style>
    .subitem .actions { width: 100px; }
</style>