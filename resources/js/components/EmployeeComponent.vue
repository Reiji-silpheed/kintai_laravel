<template>
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
                        <select class="form-select">
                            <option value="0">一般</option>
                            <option value="1">管理者</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="gap-2 d-md-flex justify-content-md-end mt-2">
                <button type="button" class="btn btn-warning md-2" @click="clear">クリア</button>
                <button type="button" class="btn btn-info" @click="search">検索</button>
            </div>
        </div>
    </div>
    <div>
        <employee-table-component :items='items'></employee-table-component>
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
            items:[],
        }
    },
    created(){
        this.hello();
    },
    methods:{
        async hello(){
            let res=await axios.get("/api/employee_api");
            console.log(res.data);
            const data=res.data;
            this.items=data;
        }
    }
}
</script>
