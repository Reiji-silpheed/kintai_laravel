<template>
    <div class="container mt-4">
        <div class="card">
            <div class="card-header">
                カレンダー
            </div>
            <div class="card-body">
                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                    <button type="button" class="btn btn-primary">保存</button>
                    <button type="button" class="btn btn-success">申請</button>
                </div>
                <table class="table mt-2 align-middle" id="kintaiEntryTable">
                    <thead>
                        <tr class="table-dark">
                            <th>日</th>
                            <th>曜日</th>
                            <th>区分</th>
                            <th>開始時刻</th>
                            <th>終了時刻</th>
                            <th>昼休憩時間</th>
                            <th>夜休憩時間</th>
                            <th>備考</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="text-center" v-for="n in lastDay" :key="n">
                            <td :class="tdClass(n-1)">
                                {{n}}
                            </td>
                            <td :class="tdClass(n-1)">
                                {{weekDay[n-1]}}
                            </td>
                            <td :class="tdClass(n-1)" v-if="weekDay[n-1]=='土' || weekDay[n-1]=='日' || holidayCheck[n-1]===true">
                                <select class="form-select" v-model="kubun[n-1]" @change="select(n-1)">
                                    <option value="holiday">休日</option>
                                    <option value="work">休出</option>
                                </select>
                            </td>
                            <td :class="tdClass(n-1)" v-else>
                                <select class="form-select" v-model="kubun[n-1]" @change="select(n-1)">
                                    <option value="work">出勤</option>
                                    <option value="holiday">有給</option>
                                    <option value="holiday">欠勤</option>
                                    <option value="holiday">特休</option>
                                    <option value="holiday">代休</option>
                                    <option value="holiday">振休</option>
                                </select>
                            </td>
                            <td :class="tdClass(n-1)">
                                <input type="time" class="form-control" v-model="tdStartTime[n-1]" :readonly="kubun[n-1]==='holiday'">
                            </td>
                            <td :class="tdClass(n-1)">
                                <input type="time" class="form-control" v-model="tdEndTime[n-1]" :readonly="kubun[n-1]==='holiday'">
                            </td>
                            <td :class="tdClass(n-1)">
                                <input type="time" class="form-control" v-model="tdLunchBreak[n-1]" :readonly="kubun[n-1]==='holiday'">
                            </td>
                            <td :class="tdClass(n-1)">
                                <input type="time" class="form-control" v-model="tdNightBreak[n-1]" :readonly="kubun[n-1]==='holiday'">
                            </td>
                            <td :class="tdClass(n-1)">
                                <input type="text" class="form-control" :value="holidayName[n-1]">
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>

<script>
export default{
    name:'NumberList',
    props:{
        items:[],
        holidays:[],
        holidayName:[],
        weekDay:[],
        lastDay:"",
        month:""
    },
    data(){
        return{
            kubun:[],
            tdStartTime:[],
            tdEndTime:[],
            tdLunchBreak:[],
            tdNightBreak:[],
            holidayCheck:[],
        }
    },
    watch:{
        weekDay(){
            this.initData();
        }
    },
    methods:{
        initData(){
            this.kubun=[];
            this.tdStartTime=[];
            this.tdEndTime=[];
            this.tdLunchBreak=[];
            this.tdNightBreak=[];
            this.holidayCheck=[];
            for(let i=0;i<this.lastDay;i++){
                let yyyymmdd=this.month;
                if(i<9){
                    yyyymmdd+="-0"+(i+1);
                }
                else{
                    yyyymmdd+="-"+(i+1);
                }
                if(this.weekDay[i]==='土' || this.weekDay[i]==='日' || this.holidays.some(holiday =>holiday.yyyymmdd===yyyymmdd)){
                    this.kubun.push("holiday");
                    this.tdStartTime.push("");
                    this.tdEndTime.push("");
                    this.tdLunchBreak.push("");
                    this.tdNightBreak.push("");
                }
                else{
                    this.kubun.push("work");
                    this.tdStartTime.push("09:00");
                    this.tdEndTime.push("18:00");
                    this.tdLunchBreak.push("01:00");
                    this.tdNightBreak.push("00:00")
                }
                if(this.holidays.some(holiday =>holiday.yyyymmdd===yyyymmdd)){
                    this.holidayCheck.push(true);
                }
                else{
                    this.holidayCheck.push(false);
                }
            }
        },
        tdClass(index){
            if(this.kubun[index]==="work"){
                return "";
            }
            else if(this.weekDay[index]==="土"){
                return  "bg-info-subtle text-info";
            }
            else if(this.weekDay[index]==="日" || this.kubun[index]==="holiday"){
                return "bg-danger-subtle text-danger";
            }
        },
        select(index){
            if(this.kubun[index]==="work"){
                this.tdStartTime[index]="09:00";
                this.tdEndTime[index]="18:00";
                this.tdLunchBreak[index]="01:00";
                this.tdNightBreak[index]="00:00";
            }
            else{
                this.tdStartTime[index]="";
                this.tdEndTime[index]="";
                this.tdLunchBreak[index]="";
                this.tdNightBreak[index]="";
            }
        }
    }
};
</script>

<style scoped src="./layout.css"></style>
