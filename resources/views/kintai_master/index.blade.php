@extends('layouts.api')
@section('title','勤怠管理')
@section('active-kintai_master','active')

@section('content')
    <div class="container">
        @if($AttendanceHeads->isEmpty())
            <div class="alert alert-warning" role="alert">検索結果がありませんでした。</div>
        @endif
        @error('check')
            <div class="alert alert-danger" role="alert">{{$message}}</div>
        @enderror
        <div class="card">
            <div class="card-header">
                検索条件
            </div>
            <div class="card-body">
                <form action="/kintai_master/form" method="GET">
                    @csrf
                    <div class="container">
                        <div class="row">
                            <div class="col-3">
                                <label class="form-label">年月:</label>
                                <input type="month" class="form-control" name="searchMonth" value="{{$searchMonth}}" >
                            </div>
                            <div class="col-3">
                                <label class="form-label">社員番号:</label>
                                <input type="text" class="form-control" name="searchNumber" value="{{$searchNumber}}">
                            </div>
                            <div class="col-3">
                                <label class="form-label">社員名:</label>
                                <input type="text" class="form-control" name="searchName" value="{{$searchName}}">
                            </div>
                            <div class="col-3">
                                <label class="form-label">ステータス:</label>
                                <select class="form-select" name="searchStatus">
                                    <option value="" hidden @selected($searchStatus==='')></option>
                                    <option value="0" @selected($searchStatus==='0')>入力中</option>
                                    <option value="1" @selected($searchStatus==='1')>申請中</option>
                                    <option value="2" @selected($searchStatus==='2')>差戻中</option>
                                    <option value="3" @selected($searchStatus==='3')>承認済</option>
                                </select>
                            </div>
                        </div>
                        <div class="d-grid gap-2 mt-2 d-md-flex justify-content-md-end">
                            <button type="submit" name="action" value="clear" class="btn btn-warning">クリア</button>
                            <button type="submit" name="action" value="search" class="btn btn-info">検索</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="card mt-4">
            <div class="card-header">
                検索結果
            </div>
            <div class="card-body">
                <div class="container">
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <button type="button" id="approvalBtn" class="btn btn-success "data-bs-toggle="modal" data-bs-target="#approvalModal">承認</button>
                        <button type="button" class="btn btn-danger">差戻</button>
                        <button type="button" class="btn btn-light">Excel出力</button>
                        <button type="button" class="btn btn-light">PDF出力</button>
                    </div>
                    <table class="table mt-2">
                        <thead>
                            <tr class="table-dark">
                                <th>#</th>
                                <th scope="col">年月</th>
                                <th scope="col">社員番号</th>
                                <th scope="col">社員名</th>
                                <th scope="col">ステータス</th>
                                <th scope="col">確認</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($AttendanceHeads as $AttendanceHead)
                                <tr>
                                    <td>
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" name="checkBox" value="{{$AttendanceHead->id}}">
                                        </div>
                                    </td>
                                    <td>{{$AttendanceHead->yyyymm}}</td>
                                    <td>{{$AttendanceHead->user->user_no}}</td>
                                    <td>{{$AttendanceHead->user->name}}</td>
                                    @if($AttendanceHead->status==="0")
                                        <td>入力中</td>
                                    @elseif($AttendanceHead->status==="1")
                                        <td>申請中</td>
                                    @elseif($AttendanceHead->status==="2")
                                        <td>差戻中</td>
                                    @elseif($AttendanceHead->status==="3")
                                        <td>承認済</td>
                                    @endif
                                    <td>
                                        <button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#checkModal">確認</button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <nav>
                        <ul class="pagination justify-content-center">
                            <li class="page-item @if((int)$page===1) disabled @endif"><a class="page-link" href="/kintai_master/page_front">前</a></li>
                            @for($i=1;$i<=(int)$count;$i++)
                                <li class="page-item @if((int)$page===$i) active @endif"><a class="page-link" href="{{route('kintai_master/page',['page'=>$i])}}">{{$i}}</a></li>
                            @endfor
                            <li class="page-item @if((int)$page===(int)$count) disabled @endif"><a class="page-link" href="/kintai_master/page_next">次</a></li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>
    {{-- 承認モーダル --}}
    <div class="modal fade" id="approvalModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content ">
                <form action="/kintai_master/approval" method="POST">
                    @csrf
                    <div class="modal-header bg-info">
                        <h1 class="modal-title fs-5 text-white">承認</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="閉じる"></button>
                    </div>
                    <div class="modal-body">
                        <p>選択した勤怠の承認をを行いますか？</p>
                        <input type="hidden" id="check" name="check" value="">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">キャンセル</button>
                        <button type="submit" name="approvalModalBtn" class="btn btn-success">承認</button>
                    </div><!-- /.modal-footer -->
                </form>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

    <script>
        $(function(){
            $(document).on("click","#approvalBtn",function(){
                let selected=[];
                $('[name="checkBox"]:checked').each(function(){
                    selected.push($(this).val());
                });
                $('#check').val(selected);
                console.log($('#check').val());
            });
        })
    </script>
@endsection
