<template>
    <div :id="'reply-'+id" class="panel panel-default">
        <div class="panel-heading">
            <div class="level">
                <h5 class="flex">
                    <a :href="'/profiles/'+data.owner.name" v-text="data.owner.name">
                    </a> said {{ data.created_at }}
                </h5>
                <div v-if="signedIn">
                    <favorite :reply="data"></favorite>
                </div>
            </div>
        </div>

        <div class="panel-body">
            <div v-if="editing">
                <div class="form-group">
                  <textarea class="form-control" id="body" name="body" placeholder="Write a reply..."cols="5" rows="5" v-model="body"></textarea>
                </div>

                <button class="btn btn-xs btn-primary" @click="update">Update</button>
                <button class="btn btn-xs btn-link" @click="editing = false">Cancel</button>
            </div>

            <div v-else v-text="body"></div>
        </div>
  
            <div class="panel-footer level" v-if="canUpdate">
                <button class="btn btn-xs mr-1" @click="editing = true">Edit
                </button>
                <button class="btn btn-danger btn-xs" @click="destroy">Delete
                </button>
            </div>                       
    </div>
</template>

<script>

    import Favorite from './Favorite.vue';

    export default {
        props: ['data'],

        components: { Favorite },

        data() {
            return {
                editing: false,
                id: this.data.id,
                body: this.data.body
            }
        },

        computed: {
            signedIn() {
                return window.App.signedIn;
            },

            canUpdate() {
                return this.authorize(user => this.data.user_id == user.id);
            }
        },

        methods: {
            update() {
                axios.patch('/forcasts/public/replies/' + this.data.id, {
                    body: this.body
                });

                this.editing = false;

                flash('Reply updated')
            },

            destroy() {
               axios.delete('/forcasts/public/replies/' + this.data.id);

               this.$emit('deleted', this.data.id)

               // $(this.$el).fadeOut(300, () => {
               //     flash('Your reply has been deleted'); 
               // });
            }
        }
    };
</script>
