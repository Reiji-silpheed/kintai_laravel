@extends('layouts.api')
@section('title','勤怠管理')
@section('active-kintai_master','active')

@section('content')
    <div class="card">
        <div class="card-header">
            検索条件
        </div>
        <div class="card-body">
            <div class="container">
                <div class="row">
                    <div class="col-3">
                        <label class="form-label">年月:</label>
                        <input type="month" class="form-control" name="searchMonth" v-model="searchMonth">
                    </div>
                    <div class="col-3">
                        <label class="form-label">ステータス:</label>
                        <select class="form-select">
                            <option value="0">一般</option>
                            <option value="1">管理者</option>
                        </select>
                    </div>
                    <div class="col-3">
                        <label class="form-label">社員番号:</label>
                        <input type="text" class="form-control" name="searchNumber" v-model="searchNumber">
                    </div>
                    <div class="col-3">
                        <label class="form-label">社員名:</label>
                        <input type="text" class="form-control" name="searchName" v-model="searchName">
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
