<template>
    <div class="container">
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
                    <button type="button" class="btn btn-primary" @click="display">表示</button>
                </div>
            </div>
        </div>
    </div>
    <kintai-table-component :item="item" :weekDay="weekDay" :lastDay="lastDay"></kintai-table-component>
</template>

<script>
import KintaiTableComponent from "./KintaiTableComponent.vue";
export default{
    components:{KintaiTableComponent},
    data(){
        return{
            items:[],
            weekDay:[],
            month:"",
            lastDay:""
        }
    },
    created(){
        this.hello();
    },
    methods:{
        async hello(){
            let res=await axios.get("api/kintai_entry_api");
            const today=new Date();
            let year=today.getFullYear();
            let month=today.getMonth();
            if(month>=9){
                this.month=year+"-"+(month+1);
            }
            else{
                this.month=year+"-0"+(month+1);
            }
            console.log(this.month);
            const lastDay=new Date(year,month,0).getDate();
            this.lastDay=lastDay;
            const weekDays=["日","月","火","水","木","金","土"];
            const weekName=[];
            for(let i=1;i<=lastDay+1;i++){
                let date=new Date(year,month,i);
                const dayIndex=date.getDay();
                weekName.push(weekDays[dayIndex]);
            }
            this.weekDay=weekName;
            const data=res.data;
            this.items=data;
        }
    }
}
</script>
