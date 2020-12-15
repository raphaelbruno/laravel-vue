<template>
    <div class="select-action">
        <div class="form-inline input-group" v-if="list.length > 1">
            <div class="input-group-prepend">
                <span class="input-group-text">
                    <i :class="`nav-icon fas fa-${icon} mr-1`" v-if="icon"></i>
                </span>
            </div>
            <select v-model="item.id" v-select2 class="form-control select2 w-100">
                <option v-for="(option, optionKey) in list" :value="option.key" :key="optionKey">
                    {{ option.value }}
                </option>
            </select>
        </div>
        <span :class="`nav-link ${!list.length ? ' bg-danger rounded' : ''}`" v-else>
            <i :class="`nav-icon fas fa-${icon} mr-1`" v-if="icon"></i>
            {{ list.length ? itemName : trans(noItemMessage) }}
        </span>
    </div>
</template>

<script>
    export default {
        props: ['options', 'value', 'action', 'noItem', 'icon'],
        data(){
            return {
                list: [],
                item: { id: null },
            }
        },
        mounted(){
            this.list = this.options ? JSON.parse(this.options) : []
            this.item.id = this.value ?? null
        },
        computed: {
            itemName(){
                return _.first(_.filter(this.list, { key: parseInt(this.item.id) })).value
            },
            noItemMessage(){
                return this.noItem ?? 'admin.no-items'
            }
        },
        watch: {
            item: {
                handler(item){
                    if(item.id == this.value || !Number.isInteger(parseInt(item.id))) return;
                    window.location = this.action.replace(':id', item.id)
                },
                deep: true
            }
        },
    }
</script>
