<template>
    <div class="container mt-4">
        <div class="card">
            <div class="card-header">
                カレンダー
            </div>
            <div class="card-body">
                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                    <button type="button" class="btn btn-primary">保存</button>
                    <button type="button" class="btn btn-success">申請</button>
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
                            <th>備考</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="text-center" v-for="n in lastDay" :key="n">
                            <td class="bg-info-subtle text-info" v-if="weekDay[n-1]=='土'">
                                {{n}}
                            </td>
                            <td class="bg-danger-subtle text-danger" v-else-if="weekDay[n-1]=='日'">
                                {{n}}
                            </td>
                            <td v-else>
                                {{n}}
                            </td>
                            <td class="bg-info-subtle text-info" v-if="weekDay[n-1]=='土'">
                                {{weekDay[n-1]}}
                            </td>
                            <td class="bg-danger-subtle text-danger" v-else-if="weekDay[n-1]=='日'">
                                {{weekDay[n-1]}}
                            </td>
                            <td v-else>
                                {{weekDay[n-1]}}
                            </td>
                            <td class="bg-info-subtle" v-if="weekDay[n-1]=='土'">
                                <select class="form-select">
                                    <option value="holiday">休日</option>
                                    <option value="work">休出</option>
                                </select>
                            </td>
                            <td class="bg-danger-subtle" v-else-if="weekDay[n-1]=='日'">
                                <select class="form-select">
                                    <option value="holiday">休日</option>
                                    <option value="work">休出</option>
                                </select>
                            </td>
                            <td v-else>
                                <select class="form-select">
                                    <option value="work">出勤</option>
                                    <option value="holiday">有給</option>
                                    <option value="holiday">欠勤</option>
                                    <option value="holiday">特休</option>
                                    <option value="holiday">代休</option>
                                    <option value="holiday">振休</option>
                                </select>
                            </td>
                            <td class="bg-info-subtle" v-if="weekDay[n-1]=='土'">
                                <input type="time" class="form-control" readonly>
                            </td>
                            <td class="bg-danger-subtle" v-else-if="weekDay[n-1]=='日'">
                                <input type="time" class="form-control" readonly>
                            </td>
                            <td v-else>
                                <input type="time" class="form-control" value="09:00">
                            </td>
                            <td class="bg-info-subtle" v-if="weekDay[n-1]=='土'">
                                <input type="time" class="form-control" readonly>
                            </td>
                            <td class="bg-danger-subtle" v-else-if="weekDay[n-1]=='日'">
                                <input type="time" class="form-control" readonly>
                            </td>
                            <td v-else>
                                <input type="time" class="form-control" value="18:00">
                            </td>
                            <td class="bg-info-subtle" v-if="weekDay[n-1]=='土'">
                                <input type="time" class="form-control" readonly>
                            </td>
                            <td class="bg-danger-subtle" v-else-if="weekDay[n-1]=='日'">
                                <input type="time" class="form-control" readonly>
                            </td>
                            <td v-else>
                                <input type="time" class="form-control" value="01:00">
                            </td>
                            <td class="bg-info-subtle" v-if="weekDay[n-1]=='土'">
                                <input type="time" class="form-control" readonly>
                            </td>
                            <td class="bg-danger-subtle" v-else-if="weekDay[n-1]=='日'">
                                <input type="time" class="form-control" readonly>
                            </td>
                            <td v-else>
                                <input type="time" class="form-control" value="00:00">
                            </td>
                            <td class="bg-info-subtle" v-if="weekDay[n-1]=='土'">
                                <input type="text" class="form-control">
                            </td>
                            <td class="bg-danger-subtle" v-else-if="weekDay[n-1]=='日'">
                                <input type="text" class="form-control">
                            </td>
                            <td v-else>
                                <input type="text" class="form-control">
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>

<script>
export default{
    name:'NumberList',
    props:{items:[],
        weekDay:[],
        lastDay:""
    },
    data(){

    }
};
</script>

<style scoped src="./layout.css"></style>
