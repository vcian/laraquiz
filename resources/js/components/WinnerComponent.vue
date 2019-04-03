<template>
    <div class="container">
        <div class="title" align="center">
            <h1 style="text-transform: uppercase;font-size: 50px; color: #fff">Contest Winners</h1>
        </div>
		<div class="row">
            <div class="col-md-4" v-for="(winner, index) in winners" :key="index">
                <div id="'user_' + winner.id " class="custom-container quiz-end">
                    <div id="circle">
                        <i class="fa fa-trophy bounce"></i>
                    </div>
                    <h1 style="margin-top:50px; ">{{ winner.full_name }}</h1>
                    <h4 style="margin-top:20px; ">{{ '(' + winner.nick_name + ')' }}</h4>
                    <h1 align="center" style="font-size: 60px;">{{ (index + 1) }}</h1>
                    <div class="description">
                        <i class="fa fa-clock-o end" aria-hidden="true"></i>  {{ winner.diff }}
                    </div>
                </div>
            </div>
		</div>
	</div>
</template>

<script>
    export default {
        props: ['slug'],
        data() {
            return {
                winners: []
            }
        },
        created() {
            Echo.channel('quiz.' + this.slug)
                .listen('QuizWinnerEvent', (e) => {
                    console.log("event => " + JSON.stringify(e.winners));
                    this.winners = e.winners;
                });
            this.fetchWinners();
        },

        methods: {
            fetchWinners() {
                axios.get('/quiz/' + this.slug + '/fetch-winners').then(response => {
                    console.log("response => " + JSON.stringify(response.data));
                    this.winners = response.data;
                });
            }
        }
    }
</script>
