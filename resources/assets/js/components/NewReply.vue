<template>
	<div v-if="signedIn">
	    <div class="form-group">
	        <textarea class="form-control" id="body" name="body" 
	        placeholder="Write a reply..."cols="5" rows="5" 
	        v-model="body" required></textarea>
	    </div>

	    <button type="submit" class="btn btn-default" @click="addReply">Post</button>
    </div>
    <div v-else>
    	<p class="centered text-center">Please <a href="/login">sign in</a> to participate in this discussion.</p>
    </div>
</template>

<script>

	export default {
		props: ['endpoint'],

		data()  {
			return {
				body: '',
				endpoint: ''
			};
		},

		computed: {
			signedIn() {
				return window.App.signedIn;
			},
		},

		methods: {
			addReply() {
				axios.post(this.endpoint, { body: this.body })
				.then(({data}) => {
					this.body = '';

					flash('Your reply has been posted');

					this.$emit('created', data);
				});
			},
		}
	}
</script>