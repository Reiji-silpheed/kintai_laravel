<template>
    <div v-if="alert">
        <div class="alert alert-warning" role="alert">検索結果がありません</div>
    </div>
    <div class="card">
        <div class="card-header">
            検索条件
        </div>
        <div class="card-body">
            <div class="container">
                <div class="row">
                    <div class="col-2">
                        <label class="form-label">社員番号:</label>
                        <input type="text" class="form-control" name="searchNumber" v-model="searchNumber">
                    </div>
                    <div class="col-3">
                        <label class="form-label">社員名:</label>
                        <input type="text" class="form-control" name="searchName" v-model="searchName">
                    </div>
                    <div class="col-3">
                        <label class="form-label">メールアドレス:</label>
                        <input type="text" class="form-control" name="searchEmail" v-model="searchEmail">
                    </div>
                    <div class="col-2">
                        <label class="form-label">入社日:</label>
                        <input type="date" class="form-control" name="searchDate" v-model="searchDate">
                    </div>
                    <div class="col-2">
                        <label class="form-label">権限:</label>
                        <select class="form-select" v-model="searchRole_cd">
                            <option value="0">一般</option>
                            <option value="1">管理者</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="gap-2 d-md-flex justify-content-md-end mt-2">
                <button type="button" class="btn btn-warning md-2" @click="clear">クリア</button>
                <input type="button" class="btn btn-info" name="searchBtn" value="検索" @click="search">
            </div>
        </div>
    </div>
    <div>
        <!-- 子コンポーネントから送られた値はpageChageの第一引数に自動的に渡される -->
        <employee-table-component :items='items' :offset='offset' @page-item="pageChange"></employee-table-component>
    </div>

</template>

<script>
import EmployeeTableComponent from "./EmployeeTableComponent.vue";
import axios from "axios";
export default{
    components:{EmployeeTableComponent},
    data(){
        return{
            searchNumber:"",
            searchName:"",
            searchEmail:"",
            searchDate:"",
            searchRole_cd:"",
            alert:false,
            offset:1,
            items:[],
        }
    },
    created(){
        this.hello();
    },
    methods:{
        async hello(){
            let res=await axios.get("/api/employee_api");
            const data=res.data.startItems;
            this.items=data;
            const count=res.data.items;
            const offset=(count.length)/5;
            this.offset=Math.ceil(offset);
        },
        async search(){
            this.alert=false;
            let res=await axios.get("/api/employee_api/search?searchNumber="+this.searchNumber+"&searchName="+this.searchName+"&searchEmail="+this.searchEmail+"&searchDate="+this.searchDate+"&searchRole_cd="+this.searchRole_cd);
            const data=res.data.startItems;
            this.items=data;
            const count=res.data.items;
            const offset=(count.length)/5;
            this.offset=Math.ceil(offset);
            console.log(this.offset);
            if(count.length===0){
                this.alert=true;
            }
        },
        async clear(){
            this.searchNumber="";
            this.searchName="";
            this.searchEmail="";
            this.searchDate="";
            this.searchRole_cd="";
        },
        async pageChange(page){
            const res=await axios.get("/api/employee_api/page",{params:{page:page,searchNumber:this.searchNumber,searchName:this.searchName,searchEmail:this.searchEmail,searchDate:this.searchDate,searchRole_cd:this.searchRole_cd}});
            const data=res.data.startItems;
            this.items=data;
            const count=res.data.items;
            const offset=(count.length)/5;
            this.offset=Math.ceil(offset);
        }
    }
}
</script>
