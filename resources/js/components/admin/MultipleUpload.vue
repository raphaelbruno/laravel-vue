<template>
    <div ref="that" class="multiple-upload">
        <div class="drop-here text-primary d-none">
            <i class="fas fa-upload"></i>
            <span>{{trans('crud.drop-files-here')}}</span>
        </div>
        <div class="header d-flex justify-content-between">
            <span class="ml-1">
                {{trans('crud.total')}}: {{list.length}}
            </span>
            <label :for="validName" class="btn btn-primary btn-sm">
                <input :id="name" class="d-none" type="file" 
                    :name="name" multiple="multiple" 
                    :accept="extensions" />
                {{trans('crud.choose-files')}}
            </label>
        </div>

        <ul class="multiple-upload-preview d-flex justify-content-around flex-wrap">
            <li v-for="(item, key) in list" :key="key">
                <button type="button" class="delete" v-on:click="deleteFile(key)">X</button>
                <img :src="(item.id && urlBase ? urlBase : '') + item.src" />
                <input type="hidden" :name="'item['+validName+'][]'" :value="item | stringfy">
            </li>
        </ul>
    </div>
</template>

<script>
    export default {
        props: ['id', 'name', 'extensions', 'urlBase', 'addedItems'],
        data(){
            return {
                list: [],
            }
        },
        mounted(){
            let items = this.addedItems ? JSON.parse(this.addedItems) : []
            if(items.length > 0 && typeof items[0] != 'object')
                for(let i = 0; i < items.length; i++)
                    items[i] = { id: items[i] }

            this.list = items

            let element = this.$refs.that
            let component = this
            document.addEventListener("dragenter", function(event){
                Object.values(document.getElementsByClassName('drop-here')).forEach(function(item){
                    item.classList.remove('d-none')
                })
            })

            document.addEventListener("dragleave", function(event){
                if(event.clientX == 0 && event.clientX == 0)
                Object.values(document.getElementsByClassName('drop-here')).forEach(function(item){
                    item.classList.add('d-none');
                })
            })

            element.querySelector('[type=file]').addEventListener('change', function(event) {
                component.addFiles(Object.values(this.files))
                this.value = "";
            });

            element.addEventListener('dragover', function(event) {
                event.preventDefault();
            });
            element.addEventListener('drop', function(event) {
                event.preventDefault();
                Object.values(document.getElementsByClassName('drop-here')).forEach(function(item){
                    item.classList.add('d-none');
                });
                
                if (event.dataTransfer.items) {
                    for (var i = 0; i < event.dataTransfer.items.length; i++)
                        if (event.dataTransfer.items[i].kind === 'file')                    
                            component.addFiles(event.dataTransfer.items[i].getAsFile());
                } else {
                    for (var i = 0; i < event.dataTransfer.files.length; i++)
                        component.addFiles(event.dataTransfer.files[i]);
                }
            });

        },
        computed: {
            validName(){
                return this.name ? this.name : 'images'
            },
            element(){
                return document.querySelector('#multiple-upload-'+name);
            },
        },
        filters: {
            stringfy(object){
                return JSON.stringify(object)
            }
        },
        methods: {
            addFiles(files){
                if(!Array.isArray(files)) files = [files]

                let component = this
                files.forEach((file, key) => {
                    if(file.hasOwnProperty('id')){
                        component.list.push(json);
                    } else {
                        var reader = new FileReader();
                        reader.addEventListener('load', function(event) {
                            var json = { id: null, src: event.target.result }
                            component.list.push(json);
                        });
                        reader.readAsDataURL(file);
                    }
                })
            },
            deleteFile(key){
                this.list.splice(key, 1)
            },
        }
    }
</script>