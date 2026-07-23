<template>
    <div class="card mt-2">
        <div class="card-header">
            検索結果
        </div>
        <div class="card-body">
            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                <button class="btn btn-success" type="button" @click="newBtn">新規</button>
                <button class="btn btn-primary" type="button" @click="updateBtn" :disabled="selected===''">更新</button>
                <button class="btn btn-danger" type="button" @click="deleteBtn" :disabled="selected===''">削除</button>
            </div>
            <table class="table mt-2">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th scope="col">社員番号</th>
                        <th scope="col">社員名</th>
                        <th scope="col">メールアドレス</th>
                        <th scope="col">入社日</th>
                        <th scope="col">権限</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="item in items" :key="item.id">
                        <td>
                            <div class="form-check">
                                <label >
                                    <input type="radio" class="form-check-input" name="radio" :value="item.id" v-model="selected">
                                </label>
                            </div>
                        </td>
                        <td>{{item.user_no}}</td>
                        <td>{{item.name}}</td>
                        <td>{{item.email}}</td>
                        <td>{{item.start_date}}</td>
                        <td>
                            <span v-if="item.role_cd==0">一般</span>
                            <span v-else-if="item.role_cd==1">管理者</span>
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="container">
                <nav>
                    <ul class="pagination justify-content-center">
                        <li class="page-item"><input type="button" class="page-link" :class="{disabled:actived==1}" @click="page_front" value="前" ></li>
                        <!-- templateタグ:HTMLに出力されないタグ -->
                        <template v-for="n in offset" :key="n">
                            <li class="page-item"><input type="button" class="page-link" :class="{active:actived==n}" name="page" :value="n" @click="page($event)" ></li>
                        </template>
                        <li class="page-item"><input type="button" class="page-link" :class="{disabled:actived==offset || offset==0}" @click="page_next" value="次"></li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>
</template>

<script>
export default{
    name:'NumberList',
    props:{
        items:[],
        offset:1
    },
    data(){
        return{
            selected:"",
            actived:1
        };
    },
    methods:{
        async page(event){
            let pageValue=event.target.value;
            this.actived=pageValue;
            this.$emit("page-item",pageValue);
        },
        async page_front(){
            let pageValue=this.actived-1;
            this.actived=pageValue;
            this.$emit("page-item",pageValue);
        },
        async page_next(){
            let pageValue=this.actived+1;
            this.actived=pageValue;
            this.$emit("page-item",pageValue);
        }
    }

}
</script>
