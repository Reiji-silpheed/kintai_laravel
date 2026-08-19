@extends('layouts.api')
@section('title','勤怠管理')
@section('active-kintai_master','active')

@section('content')
    <div class="container">
        @if($AttendanceHeads->isEmpty())
            <div class="alert alert-warning" role="alert">検索結果がありませんでした。</div>
        @endif
        @error('check')
        {{-- {!! !!}:タグを文字列として扱わなくなる（エスケープ処理） --}}
            <div class="alert alert-danger" role="alert">{!! $message !!}</div>
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
                                        <button type="button" name="checkBtn" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#checkModal{{$AttendanceHead->id}}" value="{{$AttendanceHead->yyyymm}}">確認</button>
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

    {{-- 確認モーダル --}}
    @foreach($AttendanceHeads as $AttendanceHead)
        <div class="modal fade" id="checkModal{{$AttendanceHead->id}}" tabindex="-1">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header bg-info">
                        <h1 class="modal-title fs-5 text-white">勤怠確認</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="閉じる"></button>
                    </div>
                    <div class="modal-body">
                        <div class="container">
                            <table class="table" id="kintaiMaterTable">
                                <thead>
                                    <tr class="table-dark">
                                        <th>日</th>
                                        <th>曜日</th>
                                        <th>区分</th>
                                        <th>開始時刻</th>
                                        <th>終了時刻</th>
                                        <th>昼休憩時間</th>
                                        <th>夜休憩時間</th>
                                        <th>勤務時間</th>
                                        <th>残業時間</th>
                                        <th>備考</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($AttendanceHead->attendance_details as $attendance_detail)
                                        <tr>
                                            <td class="@if($attendance_detail->kbn!=='1' && $attendance_detail->kbn!=='4') text-danger bg-danger-subtle @endif ">{{$attendance_detail->day}}</td>
                                            <td id="date{{$attendance_detail->day}}" class="@if($attendance_detail->kbn!=='1' && $attendance_detail->kbn!=='4') text-danger bg-danger-subtle @endif"></td>
                                            @if($attendance_detail->kbn==1)
                                                <td>出勤</td>
                                            @endif
                                            @if($attendance_detail->kbn==2)
                                                <td class="text-danger bg-danger-subtle">休日</td>
                                            @endif
                                            @if($attendance_detail->kbn==3)
                                                <td class="text-danger bg-danger-subtle">有給</td>
                                            @endif
                                            @if($attendance_detail->kbn==4)
                                                <td>休出</td>
                                            @endif
                                            @if($attendance_detail->kbn==5)
                                                <td class="text-danger bg-danger-subtle">欠勤</td>
                                            @endif
                                            @if($attendance_detail->kbn==6)
                                                <td class="text-danger bg-danger-subtle">特休</td>
                                            @endif
                                            @if($attendance_detail->kbn==7)
                                                <td class="text-danger bg-danger-subtle">代休</td>
                                            @endif
                                            @if($attendance_detail->kbn==8)
                                                <td class="text-danger bg-danger-subtle">振休</td>
                                            @endif
                                            @if($attendance_detail->kbn!=='1' && $attendance_detail->kbn!=='4')
                                                <td class="bg-danger-subtle"></td>
                                            @else
                                                <td>{{substr($attendance_detail->start_time,0,5)}}</td>
                                            @endif
                                            @if($attendance_detail->kbn!=='1' && $attendance_detail->kbn!=='4')
                                                <td class="bg-danger-subtle"></td>
                                            @else
                                                <td>{{substr($attendance_detail->end_time,0,5)}}</td>
                                            @endif
                                            @if($attendance_detail->kbn!=='1' && $attendance_detail->kbn!=='4')
                                                <td class="bg-danger-subtle"></td>
                                            @else
                                                <td>{{substr($attendance_detail->rest_time,0,5)}}</td>
                                            @endif
                                            @if($attendance_detail->kbn!=='1' && $attendance_detail->kbn!=='4')
                                                <td class="bg-danger-subtle"></td>
                                            @else
                                                <td>{{substr($attendance_detail->night_rest_time,0,5)}}</td>
                                            @endif
                                            @if($attendance_detail->kbn!=='1' && $attendance_detail->kbn!=='4')
                                                <td class="bg-danger-subtle"></td>
                                            @else
                                                <td>{{substr($attendance_detail->work_time,0,5)}}</td>
                                            @endif
                                            @if($attendance_detail->kbn!=='1' && $attendance_detail->kbn!=='4')
                                                <td class="bg-danger-subtle"></td>
                                            @else
                                                <td>{{substr($attendance_detail->over_time,0,5)}}</td>
                                            @endif
                                            <td class="@if($attendance_detail->kbn!=='1' && $attendance_detail->kbn!=='4') bg-danger-subtle @endif">{{$attendance_detail->remarks}}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">閉じる</button>
                    </div><!-- /.modal-footer -->
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->
    @endforeach

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
            });
            $(document).on("click","[name='checkBtn']",function(){
                let yyyymm=$(this).val();
                let year=yyyymm.slice(0,4);
                let month=yyyymm.slice(4,5);
                if(month==0){
                    month=yyyymm.slice(5,6);
                }
                else{
                    month=yyyymm.slice(4,6);
                }
                let lastDay=new Date(year,month,0).getDate();
                /* 今開いているモーダル */
                let modal = $(this).data('bs-target');
                const dateList=['日','月','火','水','木','金','土'];
                for(let i=1;i<=lastDay;i++){
                    let date=new Date(year,month-1,i);
                    let dayOfWeek=date.getDay();
                    $(modal).find(`#date${i}`).text(dateList[dayOfWeek]);
                }
            })
        })
    </script>
@endsection
