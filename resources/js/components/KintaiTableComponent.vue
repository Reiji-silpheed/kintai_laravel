<template>
    <div class="container mt-4">
        <div class="card">
            <div class="card-header">
                カレンダー
            </div>
            <div class="card-body">
                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                    <button type="button" class="btn btn-primary" @click="save" :disabled="disabled">保存</button>
                    <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#appModal" :disabled="disabled">申請</button>
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
                        <tr  class="text-center" v-for="n in lastDay" :key="n">
                            <td :class="tdClass(n-1)">
                                {{n}}
                            </td>
                            <td :class="tdClass(n-1)">
                                {{weekDay[n-1]}}
                            </td>
                            <td :class="tdClass(n-1)" v-if="holidayCheck[n-1]===true">
                                <select class="form-select" v-model="kubun[n-1]" @change="select(n-1)" :disabled="disabled">
                                    <option value="2">休日</option>
                                    <option value="4">休出</option>
                                </select>
                            </td>
                            <td :class="tdClass(n-1)" v-else>
                                <select class="form-select" v-model="kubun[n-1]" @change="select(n-1)" :disabled="disabled">
                                    <option value="1">出勤</option>
                                    <option value="3">有給</option>
                                    <option value="5">欠勤</option>
                                    <option value="6">特休</option>
                                    <option value="7">代休</option>
                                    <option value="8">振休</option>
                                </select>
                            </td>
                            <td :class="tdClass(n-1)">
                                <input type="time" class="form-control" v-model="tdStartTime[n-1]" @change="time(n-1)" :readonly="kubun[n-1]!=='1' && kubun[n-1]!=='4'" :disabled="disabled">
                            </td>
                            <td :class="tdClass(n-1)">
                                <input type="time" class="form-control" v-model="tdEndTime[n-1]" @change="time(n-1)" :readonly="kubun[n-1]!=='1' && kubun[n-1]!=='4'" :disabled="disabled">
                            </td>
                            <td :class="tdClass(n-1)">
                                <input type="time" class="form-control" v-model="tdLunchBreak[n-1]" @change="time(n-1)" :readonly="kubun[n-1]!=='1' && kubun[n-1]!=='4'" :disabled="disabled">
                            </td>
                            <td :class="tdClass(n-1)">
                                <input type="time" class="form-control" v-model="tdNightBreak[n-1]" @change="time(n-1)" :readonly="kubun[n-1]!=='1' && kubun[n-1]!=='4'" :disabled="disabled">
                            </td>
                            <td :class="tdClass(n-1)">
                                <input type="time" class="form-control" v-model="tdWorkTime[n-1]" readonly :disabled="disabled">
                            </td>
                            <td :class="tdClass(n-1)">
                                <input type="time" class="form-control" v-model="tdOverTime[n-1]" readonly :disabled="disabled">
                            </td>
                            <td :class="tdClass(n-1)">
                                <input type="text" class="form-control" v-model="remarks[n-1]" :disabled="disabled">
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="modal fade" id="appModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header bg-info">
                <h1 class="modal-title fs-5 text-white">勤怠申請</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="閉じる"></button>
            </div>
            <div class="modal-body">
                <p>入力した勤怠を申請しますか？</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">キャンセル</button>
                <button type="button" class="btn btn-success" data-bs-dismiss="modal" @click="app">申請</button>
            </div><!-- /.modal-footer -->
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
</template>

<script>
export default{
    name:'NumberList',
    props:{
        items:[],
        holidays:[],
        holidayName:[],
        attendance_details:[],
        weekDay:[],
        lastDay:"",
        month:"",
        disabled:""
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
            remarks:[],
            holidayCheck:[],
            updateAlert:false,
            appAlert:false
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
            this.remarks=[];
            if(this.attendance_details.length==0){
                this.remarks=this.holidayName;
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
                        this.kubun.push("2");
                        this.tdStartTime.push("");
                        this.tdEndTime.push("");
                        this.tdLunchBreak.push("");
                        this.tdNightBreak.push("");
                        this.tdWorkTime.push("");
                        this.tdOverTime.push("");
                        this.holidayCheck.push(true);
                    }
                    else{
                        this.kubun.push("1");
                        this.tdStartTime.push("09:00:00");
                        this.tdEndTime.push("18:00:00");
                        this.tdLunchBreak.push("01:00:00");
                        this.tdNightBreak.push("00:00:00");
                        this.tdWorkTime.push("08:00:00");
                        this.tdOverTime.push("00:00:00");
                        this.holidayCheck.push(false);
                    }
                }
            }
            else{
                for(let i=0;i<this.lastDay;i++){
                    this.day.push(this.attendance_details[i]['day']);
                    let yyyymmdd=this.month;
                    if(i<9){
                        yyyymmdd+="-0"+(i+1);
                    }
                    else{
                        yyyymmdd+="-"+(i+1);
                    }
                    if(this.attendance_details[i]['kbn']==="2" || this.attendance_details[i]['kbn']==="4"){
                        this.holidayCheck.push(true);
                    }
                    else{
                        this.holidayCheck.push(false);
                    }
                    this.kubun.push(this.attendance_details[i]['kbn']);
                    this.remarks.push(this.attendance_details[i]['remarks']);
                    if(this.attendance_details[i]['kbn']==="1" || this.attendance_details[i]['kbn']==="4"){
                        this.tdStartTime.push(this.attendance_details[i]['start_time']);
                        this.tdEndTime.push(this.attendance_details[i]['end_time']);
                        this.tdLunchBreak.push(this.attendance_details[i]['rest_time']);
                        this.tdNightBreak.push(this.attendance_details[i]['night_rest_time']);
                        this.tdWorkTime.push(this.attendance_details[i]['work_time']);
                        this.tdOverTime.push(this.attendance_details[i]['over_time']);
                    }
                    else{
                        this.tdStartTime.push("");
                        this.tdEndTime.push("");
                        this.tdLunchBreak.push("");
                        this.tdNightBreak.push("");
                        this.tdWorkTime.push("");
                        this.tdOverTime.push("");
                    }
                }
            }
        },
        tdClass(index){
            if(this.kubun[index]==="1" || this.kubun[index]==="4"){
                return "";
            }
            else if(this.weekDay[index]==="土"){
                return  "bg-info-subtle text-info";
            }
            else if(this.weekDay[index]==="日" || this.kubun[index]!=="1" || this.kubun[index]!=="4"){
                return "bg-danger-subtle text-danger";
            }
        },
        select(index){
            if(this.kubun[index]==="1" || this.kubun[index]==="4"){
                this.tdStartTime[index]="09:00:00";
                this.tdEndTime[index]="18:00:00";
                this.tdLunchBreak[index]="01:00:00";
                this.tdNightBreak[index]="00:00:00";
                this.tdWorkTime[index]="08:00:00";
                this.tdOverTime[index]="00:00:00"
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
            let startTime=new Date(yyyymmdd+"T"+this.tdStartTime[index]);
            let endTime=new Date(yyyymmdd+"T"+this.tdEndTime[index]);
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
                this.tdOverTime[index]="00:00:00";
            }
        },
        async save(){
            this.updateAlert=false;
            this.appAlert=false;
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
            let data=res.data.updateAlert;
            this.updateAlert=data;
            if(this.updateAlert===true){
                this.$emit('updateAlert',true);
            }
            if(this.appAlert===false){
                this.$emit('appAlert',false);
            }
            this.$emit("updateSendBack");
        },
        async app(){
            this.updateAlert=false;
            this.appAlert=false;
            const display=this.month;
            let year=display.slice(0,4);
            let month=display.slice(5,7);
            let yyyymm=year+month;
            let res=await axios.post('api/kintai_entry_api/app',{
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
            let data=res.data.appAlert;
            this.appAlert=data;
            if(this.appAlert===true){
                this.$emit('appAlert',true);
            }
            if(this.updateAlert===false){
                this.$emit('updateAlert',false);
            }
            this.$emit('appDisabled',true);
            this.$emit("updateSendBack");
        }
    }
};
</script>

<style scoped src="./layout.css"></style>
