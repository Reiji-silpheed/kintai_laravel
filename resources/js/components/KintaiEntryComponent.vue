<template>
   <div v-if="error.month">
        <div class="alert alert-danger" role="alert">{{error.month[0]}}</div>
   </div>
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
    <kintai-table-component :items="items" :weekDay="weekDay" :lastDay="lastDay" :month="month" ></kintai-table-component>
</template>

<script>
import KintaiTableComponent from "./KintaiTableComponent.vue";
export default{
    components:{KintaiTableComponent},
    data(){
        return{
            items:[],
            holiday:[],
            weekDay:[],
            month:"",
            lastDay:"",
            error:{}
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
            const lastDay=new Date(year,month+1,0).getDate();
            this.lastDay=lastDay;
            const items=res.data.items;
            this.items=items;
            const holidays=res.data.holidays;
            this.holidays=holidays;
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
            try{
                let res=await axios.get("api/kintai_entry_api/display",{params:{month:this.month}});
                const display=this.month;
                let year=display.slice(0,4);
                let month=display.slice(5,6);
                if(month==0){
                    month=display.slice(6,7)-1;
                }
                else{
                    month=display.slice(6,8)-1;
                }
                const lastDay=new Date(year,month+1,0).getDate();
                this.lastDay=lastDay;
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
            }
            catch(error){
                this.error=error.response.data.errors;
            }

        }
    }
}
</script>
