<template>
    <div class="dashboard-main">
        <div id="result">
            <div v-for="(player, index) in players" :key="index">
                <div id="'user_' + player.id " class="custom-container quiz-start">
                    <div id="circle">
                        <i v-if="player.end_time" class="fa fa-trophy bounce"></i>
                        <svg v-else class="custom-loader" xmlns="http://www.w3.org/2000/svg" version="1.2" baseProfile="tiny" x="0" y="0" viewBox="0 0 200 200" xml:space="preserve"><path class="loaderreverse" d="M200 100c0-30.3-13.5-57.5-34.8-75.8 -4.8-4.1-12.2-3-15.8 2.3v0c-3 4.5-2.4 10.7 1.8 14.2 16.6 14.4 27.1 35.6 27.1 59.3s-10.5 44.9-27.1 59.3c-4.1 3.6-4.8 9.7-1.8 14.2v0c3.6 5.3 11 6.4 15.8 2.3C186.5 157.5 200 130.3 200 100z"/><path d="M156.7 100c0-14.9-5.8-28.5-15.2-38.6 -4.6-4.9-12.6-4.1-16.3 1.4l-0.4 0.6c-2.8 4.1-2.2 9.5 1.2 13.2 5.7 6.2 9.1 14.4 9.1 23.5 0 9-3.4 17.3-9.1 23.5 -3.3 3.7-3.9 9-1.2 13.2l0.4 0.6c3.7 5.6 11.7 6.3 16.3 1.4C150.9 128.5 156.7 114.9 156.7 100z"/></svg>
                    </div>
                    <h1 style="margin-top:50px; ">{{ player.nick_name }}</h1>
                    <p v-if="player.end_time">Thank You!!!</p>
                    <p v-else class="reveal-text">Quiz In-Progress</p>
                    <div class="description">
                        <i class="fa fa-clock-o start" aria-hidden="true"></i>{{ player.start_time }}
                        <div v-if="player.end_time">
                            <div class="clearfix"></div>
                            <i class="fa fa-clock-o end" aria-hidden="true"></i>{{ player.end_time }}
                        </div>
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
                players: []
            }
        },

        created() {
            this.fetchPlayers();
            Echo.channel('quiz-dashboard.' + this.slug)
                .listen('QuizDashboardEvent', (e) => {
                    this.players = e.players.reverse();
                });
        },

        methods: {
            fetchPlayers() {
                axios.get('/quiz/' + this.slug + '/fetch-players').then(response => {
                    this.players = response.data;
                });
            }
        }
    }
</script>

<style scoped>

</style>