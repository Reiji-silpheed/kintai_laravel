<template>
    <div v-if="error.month">
            <div class="alert alert-danger" role="alert">{{error.month[0]}}</div>
    </div>
    <div v-if="updateAlert">
            <div class="alert alert-primary" role="alert">保存が完了しました。</div>
    </div>
    <div v-if="appAlert">
        <div class="alert alert-success" role="alert">申請が完了しました。</div>
    </div>
    <div class="container">
        <div v-if="sendBack" class="card mb-4">
            <div class="card-header">
                差戻一覧
            </div>
            <div class="card-body">
                <div class="container">
                    <table class="table">
                        <thead>
                            <tr>
                                <th class="table-dark">日付</th>
                                <th class="table-dark">理由</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="n in count" :key="n">
                                <td>{{sendBack_yyyymm[n-1]}}</td>
                                <td>{{sendBack_comment[n-1]}}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                入力
            </div>
            <div class="card-body">
                <div class="container">
                    <div class="row">
                        <div class="col-4">
                            <label class="form-lavel">年月:</label>
                            <input type="month" class="form-control" name="month" v-model="month">
                        </div>
                    </div>
                </div>
                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                    <button type="button" class="btn btn-info" @click="display">表示</button>
                </div>
            </div>
        </div>
    </div>
    <kintai-table-component :items="items" :holidays="holidays" :holidayName="holidayName" :attendance_details="attendance_details" :disabled="disabled" :weekDay="weekDay" :lastDay="lastDay" :month="month" @updateAlert="updateAlert=$event" @appAlert="appAlert=$event" @appDisabled="disabled=$event" @updateSendBack="updateSendBack"></kintai-table-component>
</template>

<script>
import KintaiTableComponent from "./KintaiTableComponent.vue";
export default{
    components:{KintaiTableComponent},
    data(){
        return{
            items:[],
            holidays:[],
            holidayName:[],
            attendance_details:[],
            weekDay:[],
            month:"",
            lastDay:"",
            disabled:"",
            updateAlert:"",
            appAlert:"",
            sendBack:"",
            count:"",
            sendBack_yyyymm:[],
            sendBack_comment:[],
            error:{}
        }

    },
    created(){
        this.hello();
    },
    methods:{
        async hello(){
            const today=new Date();
            let year=today.getFullYear();
            let month=today.getMonth();
            let yyyymm="";
            if(month>=9){
                this.month=year+"-"+(month+1);
                yyyymm=`${year}${month+1}`
            }
            else{
                this.month=year+"-0"+(month+1);
                yyyymm=`${year}0${month+1}`
            }
            const lastDay=new Date(year,month+1,0).getDate();
            this.lastDay=lastDay;
            let date=[];
            for(let i=1;i<=lastDay;i++){
                let yyyymmdd="";
                if(i<=9){
                    yyyymmdd=this.month+"-0"+i
                }
                else{
                    yyyymmdd=this.month+"-"+i;
                }
                date.push(yyyymmdd)
            }
            let res=await axios.get("api/kintai_entry_api",{params:{
                'date':date,
                'yyyymm':yyyymm,
                'id':window.login.id
            }});
            const items=res.data.items;
            this.items=items;
            const holidays=res.data.holidays;
            this.holidays=holidays;
            const holidayName=res.data.holidayName;
            this.holidayName=holidayName;
            const attendance_details=res.data.attendance_details;
            this.attendance_details=attendance_details;
            const disabled=res.data.disabled;
            this.disabled=disabled;
            const sendBack=res.data.sendBack;
            this.sendBack=sendBack;
            const sendBack_yyyymm=res.data.sendBack_yyyymm;
            this.sendBack_yyyymm=sendBack_yyyymm;
            const sendBack_comment=res.data.sendBack_comment;
            this.sendBack_comment=sendBack_comment;
            const count=res.data.count;
            this.count=count;
            const weekDays=["日","月","火","水","木","金","土"];
            const weekName=[];
            for(let i=1;i<=lastDay;i++){
                let date=new Date(year,month,i);
                const dayIndex=date.getDay();
                weekName.push(weekDays[dayIndex]);
            }
            this.weekDay=weekName;
        },
        async display(){
            this.error={};
            this.updateAlert=false;
            this.appAlert=false;
            try{
                const display=this.month;
                let year=display.slice(0,4);
                let month=display.slice(5,6);
                let yyyymm="";
                if(month==0){
                    month=display.slice(6,7)-1;
                    yyyymm=`${year}0${month+1}`;
                }
                else{
                    month=display.slice(5,8)-1;
                    yyyymm=`${year}${month+1}`;
                }
                const lastDay=new Date(year,month+1,0).getDate();
                this.lastDay=lastDay;
                let date=[];
                for(let i=1;i<=lastDay;i++){
                    let yyyymmdd="";
                    if(i<=9){
                        yyyymmdd=this.month+"-0"+i;
                    }
                    else{
                        yyyymmdd=this.month+"-"+i;
                    }
                    date.push(yyyymmdd);
                }
                let res=await axios.get("api/kintai_entry_api/display",{params:{
                    month:this.month,
                    date:date,
                    yyyymm:yyyymm,
                    id:window.login.id,
                    }});
                const weekDays=["日","月","火","水","木","金","土"];
                const weekName=[];
                for(let i=1;i<=lastDay;i++){
                    let date=new Date(year,month,i);
                    const dayIndex=date.getDay();
                    weekName.push(weekDays[dayIndex]);
                }
                this.weekDay=weekName;
                const items=res.data.items;
                this.items=items;
                const holidays=res.data.holidays;
                this.holidays=holidays;
                const holidayName=res.data.holidayName;
                this.holidayName=holidayName;
                const attendance_details=res.data.attendance_details;
                this.attendance_details=attendance_details;
                const disabled=res.data.disabled;
                this.disabled=disabled;
            }
            catch(error){
                this.error=error.response.data.errors;
            }
        },
        async updateSendBack(){
            let res=await axios.get("api/kintai_entry_api/updateSendBack",{params:{'id':window.login.id}});
            const sendBack=res.data.sendBack;
            this.sendBack=sendBack;
            const sendBack_yyyymm=res.data.sendBack_yyyymm;
            this.sendBack_yyyymm=sendBack_yyyymm;
            const sendBack_comment=res.data.sendBack_comment;
            this.sendBack_comment=sendBack_comment;
            const count=res.data.count;
            this.count=count;
        }
    }
}
</script>
