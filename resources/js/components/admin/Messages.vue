<template>
    <span :id="id" class="messages">
        <button type="button" class="action btn btn-sm btn-primary" v-on:click="openMessages()" :title="title ? title : trans('crud.messages')">
            <i class="fas fa-envelope"></i>
            <span v-if="total" class="badge bg-red">{{total}}</span>
        </button>

        <div ref="modal" class="modal" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ title ? title : trans('crud.messages') }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div ref="historic" class="historic">
                            <div :class="'message ' + (message.user.me ? 'me' : '')" v-for="(message, key) in messages" :key="key">
                                <div class="user" v-if="!message.user.me">{{message.user.short_name}}</div>
                                <div class="content" v-html="message.content_html_line"></div>
                                <small class="datetime">{{message.created_at | date | moment("DD/MM/YYYY HH:mm") }}</small>
                            </div>
                        </div>
                    </div>
                    <div v-if="form" class="modal-footer row">
                        <div class="form-group col mb-0">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-comment-alt"></i></span>
                                </div>
                                <textarea ref="content" v-model="message.content" v-on:keyup="resizeField()" v-on:keydown.enter.exact.prevent="sendMessage()" :rows="rows" :disabled="loading" class="form-control"></textarea>
                                <div class="input-group-append">
                                    <button type="button" class="btn btn-primary" :title="trans('crud.send')" v-on:click="sendMessage()">
                                        <i class="fas fa-paper-plane"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </span>
</template>

<script>
    export default {
        props: ['id', 'title', 'session', 'amount', 'form'],
        data(){
            return {
                total: !isNaN(this.amount) ? this.amount : 0,
                loading: false,
                rows: 1,
                message: {
                    session: this.session,
                    content: '',
                },
                messages: [],
            }
        },
        mounted(){
            if(this.amount && isNaN(this.amount)){
                window.addEventListener('load', e => {
                    this.loadMessages()
                })
            }
        },
        methods: {
            scrollDown(){
                this.$nextTick(() => {
                    this.$refs.historic.scrollTop = this.$refs.historic.scrollHeight
                    this.$refs.content.focus()
                })
            },
            openMessages(){
                $(this.$refs.modal).modal()
                this.loadMessages()
                this.scrollDown()
            },
            resizeField(){
                let lines = this.message.content.split("\n").length
                this.rows = lines > 4 ? 4 : lines
            },
            sendMessage(){
                if(this.message.content.trim().length < 1) return
                
                this.loading = true
                loaderShow()
                axios
                    .post(APP_URL + `/admin/messages/${this.session}/send`, {...this.message})
                    .then(response => {
                            this.message.content = ''
                            this.resizeField()
                            this.loadMessages(true)
                        }
                    )
            },
            loadMessages(force = false){
                if(!force && this.messages.length) return

                this.loading = true
                loaderShow()
                axios
                    .get(APP_URL + `/admin/messages/${this.session}`)
                    .then(response => {
                            this.messages = response.data
                            this.total = this.messages.length
                            this.scrollDown()
                            loaderHide()
                            this.loading = false
                        }
                    )
            },
        }
    }
</script>