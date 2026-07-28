<template>
    <div class="card mt-4">
        <div class="card-header">
            検索結果
        </div>
        <div class="container">
            <div class="card-body">
                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                    <button class="btn btn-success" type="button" data-bs-toggle="modal" data-bs-target="#newModal">新規</button>
                    <button class="btn btn-primary" type="button" data-bs-toggle="modal" data-bs-target="#updateModal" @click="modal" :disabled="selected===''">更新</button>
                    <button class="btn btn-danger" type="button" data-bs-toggle="modal" data-bs-target="#deleteModal" :disabled="selected===''">削除</button>
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
                                        <input type="radio" class="form-check-input" name="radio" :value="item.id" v-model="selected" @change="radio">
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
    </div>

</template>

<script>
export default{
    name:'NumberList',
    props:{
        items:[],
        offset:1,
        resetPage:1,
        disabled:false
    },
    data(){
        return{
            selected:"",
            actived:1,
        };
    },
    /* resetPageが1のとき(検索処理を行うとき)、activedを1にするようにする */
    watch:{
        resetPage(newValue){
            if(newValue==1){
                this.actived=newValue;
            }
        },
        disabled(newValue){
            if(newValue==true){
                this.selected="";
            }
        }
    },
    methods:{
        async page(event){
            let pageValue=event.target.value;
            this.actived=pageValue;
            this.$emit("page-item",pageValue);
        },
        async page_front(){
            let pageValue=Number(this.actived)-1;
            this.actived=pageValue;
            this.$emit("page-item",pageValue);
        },
        async page_next(){
            let pageValue=Number(this.actived)+1;
            this.actived=pageValue;
            this.$emit("page-item",pageValue);
        },
        async modal(){
            let selected=this.selected;
            this.$emit("selected-item",selected);
        },
        async radio(){
            let selected=this.selected;
            this.$emit("radio-item",selected);
        }
    }

}
</script>
