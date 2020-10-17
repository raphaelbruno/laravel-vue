<template>
    <div class="col-lg-3 col-6">
        <div v-bind:class="`small-box bg-${color}`">
            <div class="inner">
                <h3>{{ value }}</h3>
                <p>{{ description }}</p>
            </div>
            <div class="icon"><i v-bind:class="`fas fa-${icon}`"></i></div>
            <a vbind:href="href" class="small-box-footer">{{ linkLabel }} <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['value', 'description', 'linkLabel', 'href', 'color', 'icon']
    }
</script>