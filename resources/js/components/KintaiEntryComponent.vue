<template>
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
</template>

<script>
export default{
    data(){
        return{
            month:"",
            items:[],
        }
    },
    created(){
        this.hello();
    },
    methods:{
        async hello(){
            let res=await axios.get("api/kintai_entry");
            const data=res.data;
            this.items=data;
        }
    }
}
</script>
