<template>
    <div class="container mt-4">
        <div class="card">
            <div class="card-header">
                カレンダー
            </div>
            <div class="card-body">
                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                    <button type="button" class="btn btn-primary" @click="save">保存</button>
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
                            <th>勤務時間</th>
                            <th>残業時間</th>
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
                            <td :class="tdClass(n-1)" v-if="holidayCheck[n-1]===true">
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
                                <input type="time" class="form-control" v-model="tdStartTime[n-1]" @change="time(n-1)" :readonly="kubun[n-1]==='holiday'">
                            </td>
                            <td :class="tdClass(n-1)">
                                <input type="time" class="form-control" v-model="tdEndTime[n-1]" @change="time(n-1)" :readonly="kubun[n-1]==='holiday'">
                            </td>
                            <td :class="tdClass(n-1)">
                                <input type="time" class="form-control" v-model="tdLunchBreak[n-1]" @change="time(n-1)" :readonly="kubun[n-1]==='holiday'">
                            </td>
                            <td :class="tdClass(n-1)">
                                <input type="time" class="form-control" v-model="tdNightBreak[n-1]" @change="time(n-1)" :readonly="kubun[n-1]==='holiday'">
                            </td>
                            <td :class="tdClass(n-1)">
                                <input type="time" class="form-control" v-model="tdWorkTime[n-1]" readonly>
                            </td>
                            <td :class="tdClass(n-1)">
                                <input type="time" class="form-control" v-model="tdOverTime[n-1]" readonly>
                            </td>
                            <td :class="tdClass(n-1)">
                                <input type="text" class="form-control" v-model="holidayName[n-1]">
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
            day:[],
            kubun:[],
            tdStartTime:[],
            tdEndTime:[],
            tdLunchBreak:[],
            tdNightBreak:[],
            tdWorkTime:[],
            tdOverTime:[],
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
            this.day=[];
            this.kubun=[];
            this.tdStartTime=[];
            this.tdEndTime=[];
            this.tdLunchBreak=[];
            this.tdNightBreak=[];
            this.tdWorkTime=[];
            this.tdOverTime=[];
            this.holidayCheck=[];
            for(let i=0;i<this.lastDay;i++){
                this.day.push(i+1);
                let yyyymmdd=this.month;
                if(i<9){
                    yyyymmdd+="-0"+(i+1);
                }
                else{
                    yyyymmdd+="-"+(i+1);
                }
                if(this.weekDay[i]==='土' || this.weekDay[i]==='日' || this.holidayName[i]!==""){
                    this.kubun.push("holiday");
                    this.tdStartTime.push("");
                    this.tdEndTime.push("");
                    this.tdLunchBreak.push("");
                    this.tdNightBreak.push("");
                    this.tdWorkTime.push("");
                    this.tdOverTime.push("");
                    this.holidayCheck.push(true);
                }
                else{
                    this.kubun.push("work");
                    this.tdStartTime.push("09:00");
                    this.tdEndTime.push("18:00");
                    this.tdLunchBreak.push("01:00");
                    this.tdNightBreak.push("00:00");
                    this.tdWorkTime.push("08:00");
                    this.tdOverTime.push("00:00");
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
                this.tdWorkTime[index]="08:00";
                this.tdOverTime[index]="00:00"
            }
            else{
                this.tdStartTime[index]="";
                this.tdEndTime[index]="";
                this.tdLunchBreak[index]="";
                this.tdNightBreak[index]="";
                this.tdWorkTime[index]="";
                this.tdOverTime[index]="";
            }
        },
        time(index){
            let yyyymmdd=this.month;
            if(index<=9){
                yyyymmdd+="-0"+(index+1);
            }
            else{
                yyyymmdd+="-"+(index+1);
            }
            let startTime=new Date(yyyymmdd+"T"+this.tdStartTime[index]+":00");
            let endTime=new Date(yyyymmdd+"T"+this.tdEndTime[index]+":00");
            let diffTime=(endTime-startTime)/1000;
            const lunchPart=this.tdLunchBreak[index].split(":").map(Number);
            const nightPart=this.tdNightBreak[index].split(":").map(Number);
            let lunchTime=lunchPart[0]*3600+lunchPart[1]*60;
            let nightTime=nightPart[0]*3600+nightPart[1]*60;
            let workTime=diffTime-lunchTime-nightTime;
            let workHour=Math.floor(workTime/3600);
            let workMinute=Math.floor((workTime%3600)/60);
            let workHH=String(workHour).padStart(2,'0');
            let workMM=String(workMinute).padStart(2,'0');
            this.tdWorkTime[index]=workHH+":"+workMM;
            if(workTime>=28800){
                let overTime=diffTime-lunchTime-nightTime-28800;
                let overHour=Math.floor(overTime/3600);
                let overMitute=Math.floor((overTime%3600)/60);
                let overHH=String(overHour).padStart(2,'0');
                let overMM=String(overMitute).padStart(2,'0');
                this.tdOverTime[index]=overHH+":"+overMM;
            }
            else{
                this.tdOverTime[index]="00:00";
            }
        },
        async save(){
            const display=this.month;
            let year=display.slice(0,4);
            let month=display.slice(5,7);
            let yyyymm=year+month;
            let res=await axios.post('api/kintai_entry_api/save',{
                id:window.login.id,
                yyyymm:yyyymm,
                day:this.day,
                kbn:this.kubun,
                start_time:this.tdStartTime,
                end_time:this.tdEndTime,
                rest_time:this.tdLunchBreak,
                night_rest_time:this.tdNightBreak,
                work_time:this.tdWorkTime,
                over_time:this.tdOverTime,
                remarks:this.holidayName
            })
        }
    }
};
</script>

<style scoped src="./layout.css"></style>
