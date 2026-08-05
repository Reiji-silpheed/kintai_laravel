<template>
    <div v-if="alert">
        <div class="alert alert-warning" role="alert">検索結果がありませんでした。</div>
    </div>
    <div class="container">
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
                                <option selected hidden value="" ></option>
                                <option value="0">一般</option>
                                <option value="1">管理者</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="container">
                    <div class="gap-2 d-md-flex justify-content-md-end mt-2">
                        <button type="button" class="btn btn-warning md-2" @click="clear">クリア</button>
                        <input type="button" class="btn btn-info" name="searchBtn" value="検索" @click="search">
                    </div>
                </div>
            </div>
        </div>
        <div>
            <!-- 子コンポーネントから送られた値はpageChageの第一引数に自動的に渡される -->
            <employee-table-component :items='items' :offset='offset' :resetPage='resetPage' :disabled='disabled' @page-item="pageChange" @selected-item="modal" @radio-item="radio=$event"></employee-table-component>
        </div>
    </div>


    <!--新規モーダル-->
    <div class="modal fade modal-xl" id="newModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header bg-info">
                <h1 class="modal-title fs-5 text-white">社員登録</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="閉じる"></button>
            </div>
            <div class="modal-body">
                <div class="card mx-2 my-3">
                    <div class="card-header">
                        社員情報
                    </div>
                    <div class="card-body">
                        <div class="container">
                            <div class="row">
                                <div class="col-3">
                                    <label class="form-label">社員番号:</label>
                                    <input type="text" class="form-control" :class="{'is-invalid':error.newNumber}" name="newNumber" v-model="newNumber">
                                    <span v-if="error.newNumber" class="text-danger">
                                        {{error.newNumber[0]}}
                                    </span>

                                </div>
                                <div class="col-4">
                                    <label class="form-label">社員名:</label>
                                    <input type="text" class="form-control" :class="{'is-invalid':error.newName}" name="newName" v-model="newName">
                                    <span v-if="error.newName" class="text-danger">
                                        {{error.newName[0]}}
                                    </span>
                                </div>
                                <div class="col-3">
                                    <label class="form-label">入社日:</label>
                                    <input type="date" class="form-control" :class="{'is-invalid':error.newDate}" name="newDate" v-model="newDate">
                                    <span v-if="error.newDate" class="text-danger">
                                        {{error.newDate[0]}}
                                    </span>
                                </div>
                                <div class="col-2">
                                    <label class="form-label">権限:</label>
                                    <select class="form-select" :class="{'is-invalid':error.newRole_cd}" name="newRole_cd" v-model="newRole_cd">
                                        <option value="0">一般</option>
                                        <option value="1">管理者</option>
                                    </select>
                                    <span v-if="error.newRole_cd" class="text-danger">
                                        {{error.newRole_cd[0]}}
                                    </span>
                                </div>
                                <div class="col-4 mt-2">
                                    <label class="form-label">メールアドレス:</label>
                                    <input type="text" class="form-control" :class="{'is-invalid':error.newEmail}" name="newEmail" v-model="newEmail">
                                    <span v-if="error.newEmail" class="text-danger">
                                        {{error.newEmail[0]}}
                                    </span>
                                </div>
                                <div class="col-4 mt-2">
                                    <label class="form-label">パスワード:</label>
                                    <input type="password" class="form-control" :class="{'is-invalid':error.newPassword}" name="newPassword" v-model="newPassword">
                                    <span v-if="error.newPassword" class="text-danger">
                                        {{error.newPassword[0]}}
                                    </span>
                                </div>
                                <div class="col-4 mt-2">
                                    <label class="form-label">確認用パスワード:</label>
                                    <input type="password" class="form-control" :class="{'is-invalid':error.newCheckPassword}" name="newCheckPassword" v-model="newCheckPassword">
                                    <span v-if="error.newCheckPassword" class="text-danger">
                                        {{error.newCheckPassword[0]}}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">キャンセル</button>
                <button type="button" class="btn btn-success" data-bs-dismiss="modal" @click="addItem">登録</button>
            </div><!-- /.modal-footer -->
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

    <!-- 更新モーダル -->
    <div class="modal fade modal-xl" id="updateModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header bg-info">
                <h1 class="modal-title fs-5 text-white">社員更新</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="閉じる"></button>
            </div>
            <div class="modal-body">
                <div class="card mx-2 my-3">
                    <div class="card-header">
                        社員情報
                    </div>
                    <div class="card-body">
                        <div class="container">
                            <div class="row">
                                <div class="col-3">
                                    <label class="form-label">社員番号:</label>
                                    <input type="text" class="form-control" :class="{'is-invalid':error.updateNumber}" name="updateNumber" v-model="updateData.user_no">
                                    <div v-if="error.updateNumber" class="text-danger">
                                        {{error.updateNumber[0]}}
                                    </div>
                                </div>
                                <div class="col-4">
                                    <label class="form-label">社員名:</label>
                                    <input type="text" class="form-control" :class="{'is-invalid':error.updateName}" name="updateName" v-model="updateData.name">
                                    <div v-if="error.updateName" class="text-danger">
                                        {{error.updateName[0]}}
                                    </div>
                                </div>
                                <div class="col-3">
                                    <label class="form-label">入社日:</label>
                                    <input type="date" class="form-control" :class="{'is-invalid':error.updateDate}" name="updateDate" v-model="updateData.start_date">
                                    <div v-if="error.updateDate" class="text-danger">
                                        {{error.updateDate[0]}}
                                    </div>
                                </div>
                                <div class="col-2">
                                    <label class="form-label">権限:</label>
                                    <select class="form-select" :class="{'is-invalid':error.updateRole_cd}" name="updateRole_cd" v-model="updateData.role_cd">
                                        <option value="0">一般</option>
                                        <option value="1">管理者</option>
                                    </select>
                                    <div v-if="error.updateRole_cd" class="text-danger">
                                        {{error.updateRole_cd[0]}}
                                    </div>
                                </div>
                                <div class="col-4">
                                    <label class="form-label">メールアドレス:</label>
                                    <input type="text" class="form-control" :class="{'is-invalid':error.updateEmail}" name="updateEmail" v-model="updateData.email" disabled>
                                    <div v-if="error.updateEmail" class="text-danger">
                                        {{error.updateEmail[0]}}
                                    </div>
                                </div>
                                <div class="col-4">
                                    <label class="form-label">パスワード:</label>
                                    <input type="password" class="form-control" :class="{'is-invalid':error.updatePassword}" name="updatePassword" v-model="updatePassword">
                                    <div v-if="error.updatePassword" class="text-danger">
                                        {{error.updatePassword[0]}}
                                    </div>
                                </div>
                                <div class="col-4">
                                    <label class="form-label">確認用パスワード:</label>
                                    <input type="password" class="form-control" name="updateCheckPassword" v-model="updateCheckPassword">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">キャンセル</button>
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal" @click="editItem">更新</button>
            </div><!-- /.modal-footer -->
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

    <!-- 削除モーダル -->
    <div class="modal fade" id="deleteModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header bg-info">
                <h1 class="modal-title fs-5 text-white">社員削除</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="閉じる"></button>
            </div>
            <div class="modal-body">
                <p>選択した社員を削除しますか？</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">キャンセル</button>
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal" @click="deleteItem">削除</button>
            </div><!-- /.modal-footer -->
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
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
            newNumber:"",
            newName:"",
            newDate:"",
            newRole_cd:0,
            newEmail:"",
            newPassword:"",
            newCheckPassword:"",
            updatePassword:"",
            updateCheckPassword:"",
            alert:false,
            page:"",
            resetPage:0,
            offset:1,
            radio:"",
            disabled:false,
            items:[],
            createSearch:[],
            error:{},
            updateData:{}
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
            this.disabled=false;
            let res=await axios.get("/api/employee_api/search?searchNumber="+this.searchNumber+"&searchName="+this.searchName+"&searchEmail="+this.searchEmail+"&searchDate="+this.searchDate+"&searchRole_cd="+this.searchRole_cd);
            this.createSearch=[
                this.searchNumber,
                this.searchName,
                this.searchEmail,
                this.searchDate,
                this.searchRole_cd
            ];
            const data=res.data.startItems;
            this.items=data;
            const count=res.data.items;
            const offset=(count.length)/5;
            this.resetPage=1;
            this.offset=Math.ceil(offset);
            if(count.length===0){
                this.alert=true;
            }
            this.disabled=true;
        },
        async clear(){
            this.searchNumber="";
            this.searchName="";
            this.searchEmail="";
            this.searchDate="";
            this.searchRole_cd="";
            this.resetPage=0;
        },
        async pageChange(page){
            this.resetPage=0;
            this.disabled=false;
            const res=await axios.get("/api/employee_api/page",{params:{
                page:page,
                createSearch:this.createSearch
            }});
            this.page=page;
            const data=res.data.startItems;
            this.items=data;
            const count=res.data.items;
            const offset=(count.length)/5;
            this.offset=Math.ceil(offset);
            this.disabled=true;
        },
        async addItem(){
            try{
                let res=await axios.post("/api/employee_api/add",{
                    newNumber:this.newNumber,
                    newName:this.newName,
                    newDate:this.newDate,
                    newRole_cd:this.newRole_cd,
                    newEmail:this.newEmail,
                    newPassword:this.newPassword,
                    newCheckPassword:this.newCheckPassword
                });
                this.newNumber="";
                this.newName="";
                this.newDate="";
                this.newRole_cd="";
                this.newEmail="";
                this.newPassword="";
                this.newCheckPassword="";
                this.error={};
                this.pageChange(this.page);
            }
            catch(error){
                this.error=error.response.data.errors;
                $("#newModal").modal("show");
            }

        },
        async modal(selected){
            let res=await axios.post("/api/employee_api/updateModal",{selected:selected});
            const data=res.data;
            this.updateData=data[0];
        },
        async editItem(){
            this.disabled=false;
            try{
                let res=await axios.post("/api/employee_api/edit",{
                    selected:this.updateData.id,
                    updateNumber:this.updateData.user_no,
                    updateName:this.updateData.name,
                    updateDate:this.updateData.start_date,
                    updateRole_cd:this.updateData.role_cd,
                    updateEmail:this.updateData.email,
                    updatePassword:this.updatePassword,
                    updateCheckPassword:this.updateCheckPassword
                });
                this.error={};
                this.disabled=true;
                console.log(this.page);
                this.pageChange(this.page);
            }
            catch(error){
                this.error=error.response.data.errors;
                $("#updateModal").modal("show");
            }
        },
        async deleteItem(){
            this.disabled=false;
            try{
                let res=await axios.post("/api/employee_api/delete",{radio:this.radio});
                this.disabled=true;
                this.pageChange(this.page);
            }
            catch(error){
                this.error=error.response.data.errors;
                $("#deleteModal").modal("show");
            }
        }
    }
}
</script>
