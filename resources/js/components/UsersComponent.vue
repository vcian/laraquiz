<template>
    <div>
        <div v-if="quiz.users.length === 0">
            <div class="list-head"></div>
            <div class="list-contain">
                <div class="list-type attempt">
                    <div class="banner-title" align="center">
                        <h1 align="center">{{ quiz.name ? quiz.name: '' }}</h1><br/>
                    </div>
                    <h1 class="txt" style=" text-align: center; text-transform: uppercase;color: red">
                        <label>Quiz not started yet!</label>
                    </h1>
                </div>
            </div>
        </div>
        <div v-else>
            <div>
                <div class="title" align="center">
                    <h1 style="text-transform: uppercase;font-size: 50px">Contest Winners</h1>
                </div>
                <div class="dashboard-main">
                    <div class="container">
                        <div class="list-box" v-for="(winner, key) in quiz.result" style="width: 33.33%">
                            <div class="list-inner completed">
                                <div class="list-head" style="color:#fff;text-align:center;font-size:30px;">{{ winner.name ? winner.name : '' }}</div>
                                <div class="list-contain">
                                    <div style="text-align:center" class="result-data">
                                        <h1 align="center" style="font-size: 60px;">{{ key+1 }}</h1>
                                    </div>
                                </div>
                                <div class="list-footer" style="background:#259b24;color:#fff;padding:12px 10px; font-size: 30px;">
                                    Time Taken : {{ winner.diff }}
                                    <!--Time Taken : {{ date('i:s',strtotime($response->diff)) }}-->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div>
                <div class="title" align="center">
                    <h1 style="text-transform: uppercase;font-size: 50px">Quiz Status</h1>
                </div>
                <div v-for="user in quiz.users" class="list-box">
                    <div class="list-inner" v-bind:class="[ user.quiz_complete ? 'completed': '' ]">
                        <div class="list-head">{{user.name}}</div>
                        <div class="list-contain">
                            <div v-if="user.quiz_complete">
                                <div style="text-align:center" class="result-data">
                                    <img src="/img/tick-circle.png">
                                    <h1 align="center">Thank you for attempt</h1>
                                </div>
                            </div>
                            <div v-else>
                                <div class="loader"></div>
                                <h1 align="center">Quiz Started</h1>
                            </div>
                        </div>
                        <div class="list-footer">
                            Start Time: {{ user.start_time }}
                            <br/>
                            <span v-if="user.end_time">End Time: {{ user.end_time }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
    export default {
        props: ['quiz'],
        data: function () {
            return {
                users: 0
            }
        }
    }
</script>