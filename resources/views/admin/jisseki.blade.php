@extends('layouts.layout_admin_menu')

@section('admin_content')
    <div class="row">
        <!-- 職番抽出 -->
        @php
            $employee_nums = $items->sort()->pluck('name','employee_num')->unique();
        @endphp

        <div class="col-12">
            @php
                // 表示氏名の選択
                if(isset($_GET['atname'])){
                    $this_name = $_GET['atname'];
                    foreach ($items as $item) {
                        if ($item->name == $this_name) {
                            $this_employee_num = $item->employee_num;
                            $this_id = $item->id;
                        }
                    }
                } else {
                    $this_name = null;
                    $this_employee_num = null;
                    $this_id = null;
                }
                // 修正氏名の選択end

                // 表示最終月の選択
                if(isset($_GET['atmonth'])){
                    $this_month = $_GET['atmonth'];
                    } else {
                    $this_month = date("Y-m"); // 現在の年月を取得
                }

                $last_month = date("Y-m", strTotime("$this_month -1 month"));
                $twoago_month = date("Y-m", strTotime("$this_month -2 month"));
                $months = array($twoago_month, $last_month, $this_month); // ３か月の配列を作成
                // 表示最終月の選択end

                // グラフ表示内容の選択
                $grapf_contents = array(
                    'workhours_each_day' => '1日労働時間',
                    'workhours_each_month' => '月累積労働時間',
                    'workhours_each_year' => '年累積労働時間'
                );

                $this_graph = 'workhours_each_day';
                $this_graph_title = '1日労働時間';
                if(isset($_GET['atgrapf'])){
                    $this_graph = $_GET['atgrapf'];
                    foreach ($grapf_contents as $grapf_content => $grapf_title) {
                        if ($this_graph == $grapf_content) {
                            $this_graph_title = $grapf_title;
                        }
                    }
                }
                // グラフ表示内容の選択end

            @endphp

            <div class="row">
                <div class="col mt-1" style="margin-bottom:-15px">
                    <form class="d-flex" action="{{ route('admin.jisseki', ['id'=> auth()->id()]) }}" method="get">
                        <div class="col-3 d-flex">
                            <p>表示対象：</p>
                            <select class="form-control form-select w-100 col" name="atname" aria-label="勤怠実績表示対象者を表示">
                                <option selected value="all">全員</option>
                                @php $count = 1; @endphp
                                @foreach ($employee_nums as $employee_num => $employee_name)
                                    @if ($this_name == $employee_name)
                                        <option selected value="{{$employee_name}}">{{$employee_name}}</option>
                                    @else
                                        <option value="{{$employee_name}}">{{$employee_name}}</option>
                                    @endif
                                    @php $count++ @endphp
                                @endforeach
                            </select>
                        </div>
                        <div class="col-3 d-flex">
                            <p>３か月表示最終月：</p>
                            <input class="form-control" name="atmonth" type="month" max="{{date("Y-m")}}" value="{{$this_month}}">
                        </div>
                        <div class="col-3 d-flex">
                            <p>グラフ表示</p>
                            <select class="form-control form-select w-100" name="atgrapf" aria-label="グラフ表示内容を表示">
                                @foreach ($grapf_contents as $grapf_content => $grapf_title)
                                    @if ($this_graph == $grapf_content)
                                        <option selected value="{{$grapf_content}}">{{$grapf_title}}</option>
                                    @else
                                        <option value="{{$grapf_content}}">{{$grapf_title}}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                        <div class="col-2">
                            <button class="btn btn-secondary btn-sm" type="submit">表示更新</button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="row">
                <div class="col-8 table-responsive">
                    <TABLE style="font-size:0.5rem">
                        <TR>
                            @foreach ($months as $month)
                                @php
                                    $count_employee_num = 1; // ユーザー表示初回判定変数
                                @endphp

                                @foreach ($employee_nums as $employee_num => $employee_name)
                                    @if ($this_name == "all")
                                        <TD>
                                            @if ($count_employee_num == 1)
                                            <table class="table table-bordered table-sm table-striped text-right">
                                                <tr class="table-primary text-center">
                                                    <th colspan="7">{{$employee_num}}：{{$employee_name}}</th>
                                                </tr>
                                                <tr class="table-primary text-center">
                                                    <th colspan="7">{{$month}}</th>
                                                </tr>
                                                <tr class="table-primary text-center">
                                                    <th rowspan="2">日付</th>
                                                    <th colspan="2">打刻<br>時間</th>
                                                    <th colspan="2">時間<br>[h]</th>
                                                    <th colspan="2">累積<br>[h]</th>
                                                </tr>
                                                <tr class="table-primary text-center">
                                                    <th>出<br>勤</th>
                                                    <th>退<br>勤</th>
                                                    <th>休<br>憩</th>
                                                    <th>実<br>労</th>
                                                    <th>月</th>
                                                    <th>年</th>
                                                </tr>
                                                @php
                                                    $maxday = date('t', strtotime($month)); // 月の日数取得
                                                    $workdays = 0; // 勤務日数初期化
                                                    $week = array("日", "月", "火", "水", "木", "金", "土");
                                                    $this_workhours_each_month = 0; // 月労働時間初期化
                                                    $this_workhours_each_year = 0; // 年労働時間初期化
                                                @endphp

                                                @for ($num = 1; $num <= 31; $num++)
                                                    @php $is_holiday=1; @endphp
                                                    <tr>
                                                        @if ($num <= $maxday) 
                                                            @php 
                                                                $this_day=mktime(0, 0, 0, date("m", strTotime($month)), $num, date("Y", strTotime($month))); $youbi=$week[date('w', $this_day)]; 
                                                            @endphp
                                                            <th class="table-primary text-center">{{$num}}</th>
                                                        @else
                                                            <th class="table-primary text-center">&nbsp;</th>
                                                        @endif

                                                        @foreach ($items as $item)
                                                            @php
                                                                $item_month = date("Y-m", strTotime($item->date)); // データの年月を取得
                                                                $item_day = date("d", strTotime($item->date)); // データの日付を取得
                                                            @endphp

                                                            @if (($item->employee_num == $employee_num) && ($item_month == $month) && ($item_day == $num) && ($item->workhours_each_day != null))
                                                                <td>{{date('H:i', strtotime($item->starttime))}}</td>
                                                                <td>{{date('H:i', strtotime($item->endtime))}}</td>
                                                                <td>{{$item->resthours}}</td>
                                                                <td>{{$item->workhours_each_day}}</td>
                                                                <td>{{$item->workhours_each_month}}</td>
                                                                <td>{{$item->workhours_each_year}}</td>
                                                                @php
                                                                    $workdays++;
                                                                    $is_holiday = 0;
                                                                @endphp

                                                            @elseif (($item->employee_num == $employee_num) && ($item_month == $month) && ($item_day == $num) && ($item->starttime != null) && ($item->endtime == null))
                                                                <td>{{date('H:i', strtotime($item->starttime))}}</td>
                                                                <td colspan="2" class="text-center">未入力</td>
                                                                <td colspan="3" class="text-center">未計算</td>
                                                                @php
                                                                    $is_holiday = 0;
                                                                @endphp
                                                            @endif

                                                            @if ($item_month == $month)
                                                                @php
                                                                $this_workhours_each_month = $item->workhours_each_month;
                                                                $this_workhours_each_year = $item->workhours_each_year;
                                                                @endphp
                                                            @endif
                                                        @endforeach

                                                        @if ($is_holiday == 1 && $num <= $maxday && date("Y-m-d", $this_day) <= date("Y-m-d"))
                                                            <td colspan="6" class="text-center text-info">休</td>
                                                        @elseif ($num > $maxday || date("Y-m-d", $this_day) > date("Y-m-d"))
                                                            <td colspan="6" class="text-center text-info">&nbsp;</td>
                                                        @else
                                                            @php
                                                                $is_holiday = 1;
                                                            @endphp
                                                        @endif
                                                    </tr>
                                                @endfor

                                                <tr class="table-primary">
                                                    <td class="text-center">計</td>
                                                    <td colspan="4" class="text-center">勤務日数：{{$workdays}}日</td>
                                                    <td>{{$this_workhours_each_month}}</td>
                                                    <td>{{$this_workhours_each_year}}</td>
                                                </tr>
                                            </table>
                                                @php
                                                $count_employee_num = 0;
                                                @endphp
                                            @else
                                            <table class="table table-bordered table-sm table-striped text-right">
                                                <tr class="table-primary text-center">
                                                    <th colspan="6">{{$employee_num}}：{{$employee_name}}</th>
                                                </tr>
                                                <tr class="table-primary text-center">
                                                    <th colspan="6">{{$month}}</th>
                                                </tr>
                                                <tr class="table-primary text-center">
                                                    <th colspan="2">打刻<br>時間</th>
                                                    <th colspan="2">時間<br>[h]</th>
                                                    <th colspan="2">累積<br>[h]</th>
                                                </tr>
                                                <tr class="table-primary text-center">
                                                    <th>出<br>勤</th>
                                                    <th>退<br>勤</th>
                                                    <th>休<br>憩</th>
                                                    <th>実<br>労</th>
                                                    <th>月</th>
                                                    <th>年</th>
                                                </tr>
                                                @php
                                                    $maxday = date('t', strtotime($month)); // 月の日数取得
                                                    $workdays = 0; // 勤務日数初期化
                                                    $week = array("日", "月", "火", "水", "木", "金", "土");
                                                    $this_workhours_each_month = 0; // 月労働時間初期化
                                                    $this_workhours_each_year = 0; // 年労働時間初期化
                                                @endphp

                                                @for ($num = 1; $num <= 31; $num++)
                                                    @php
                                                        $is_holiday=1;
                                                        $this_day=mktime(0, 0, 0, date("m", strTotime($month)), $num, date("Y", strTotime($month)));
                                                    @endphp
                                                    <tr>
                                                        @foreach ($items as $item)
                                                            @php
                                                                $item_month = date("Y-m", strTotime($item->date)); // データの年月を取得
                                                                $item_day = date("d", strTotime($item->date)); // データの日付を取得
                                                            @endphp

                                                            @if (($item->employee_num == $employee_num) && ($item_month == $month) && ($item_day == $num) && ($item->workhours_each_day != null))
                                                                <td>{{date('H:i', strtotime($item->starttime))}}</td>
                                                                <td>{{date('H:i', strtotime($item->endtime))}}</td>
                                                                <td>{{$item->resthours}}</td>
                                                                <td>{{$item->workhours_each_day}}</td>
                                                                <td>{{$item->workhours_each_month}}</td>
                                                                <td>{{$item->workhours_each_year}}</td>
                                                                @php
                                                                    $workdays++;
                                                                    $is_holiday = 0;
                                                                @endphp

                                                            @elseif (($item->employee_num == $employee_num) && ($item_month == $month) && ($item_day == $num) && ($item->starttime != null) && ($item->endtime == null))
                                                                <td>{{date('H:i', strtotime($item->starttime))}}</td>
                                                                <td colspan="2" class="text-center">未入力</td>
                                                                <td colspan="3" class="text-center">未計算</td>
                                                                @php
                                                                $is_holiday = 0;
                                                                @endphp

                                                            @endif

                                                            @if ($item_month == $month)
                                                                @php
                                                                $this_workhours_each_month = $item->workhours_each_month;
                                                                $this_workhours_each_year = $item->workhours_each_year;
                                                                @endphp
                                                            @endif
                                                        @endforeach

                                                        @if ($is_holiday == 1 && $num <= $maxday && date("Y-m-d", $this_day) <= date("Y-m-d"))
                                                            <td colspan="6" class="text-center text-info">休</td>
                                                        @elseif ($num > $maxday || date("Y-m-d", $this_day) > date("Y-m-d"))
                                                            <td colspan="6" class="text-center text-info">&nbsp;</td>
                                                        @else
                                                            @php
                                                                $is_holiday = 1;
                                                            @endphp
                                                        @endif
                                                    </tr>
                                                @endfor

                                                <tr class="table-primary">
                                                    <td colspan="4" class="text-center">勤務日数：{{$workdays}}日</td>
                                                    <td>{{$this_workhours_each_month}}</td>
                                                    <td>{{$this_workhours_each_year}}</td>
                                                </tr>
                                            </table>
                                            @endif
                                        </TD>
                                    @elseif ($this_name == $employee_name)
                                        <TD>
                                            @if ($count_employee_num == 1)
                                            <table class="table table-bordered table-sm table-striped text-right">
                                                <tr class="table-primary text-center">
                                                    <th colspan="7">{{$employee_num}}：{{$employee_name}}</th>
                                                </tr>
                                                <tr class="table-primary text-center">
                                                    <th colspan="7">{{$month}}</th>
                                                </tr>
                                                <tr class="table-primary text-center">
                                                    <th rowspan="2">日付</th>
                                                    <th colspan="2">打刻<br>時間</th>
                                                    <th colspan="2">時間<br>[h]</th>
                                                    <th colspan="2">累積<br>[h]</th>
                                                </tr>
                                                <tr class="table-primary text-center">
                                                    <th>出<br>勤</th>
                                                    <th>退<br>勤</th>
                                                    <th>休<br>憩</th>
                                                    <th>実<br>労</th>
                                                    <th>月</th>
                                                    <th>年</th>
                                                </tr>
                                                @php
                                                    $maxday = date('t', strtotime($month)); // 月の日数取得
                                                    $workdays = 0; // 勤務日数初期化
                                                    $week = array("日", "月", "火", "水", "木", "金", "土");
                                                    $this_workhours_each_month = 0; // 月労働時間初期化
                                                    $this_workhours_each_year = 0; // 年労働時間初期化
                                                @endphp

                                                @for ($num = 1; $num <= 31; $num++)
                                                    @php $is_holiday=1; @endphp
                                                    <tr>
                                                        @if ($num <= $maxday) 
                                                            @php 
                                                                $this_day=mktime(0, 0, 0, date("m", strTotime($month)), $num, date("Y", strTotime($month))); $youbi=$week[date('w', $this_day)]; 
                                                            @endphp
                                                            <th class="table-primary text-center">{{$num}}</th>
                                                        @else
                                                            <th class="table-primary text-center">&nbsp;</th>
                                                        @endif

                                                        @foreach ($items as $item)
                                                            @php
                                                                $item_month = date("Y-m", strTotime($item->date)); // データの年月を取得
                                                                $item_day = date("d", strTotime($item->date)); // データの日付を取得
                                                            @endphp

                                                            @if (($item->employee_num == $employee_num) && ($item_month == $month) && ($item_day == $num) && ($item->workhours_each_day != null))
                                                                <td>{{date('H:i', strtotime($item->starttime))}}</td>
                                                                <td>{{date('H:i', strtotime($item->endtime))}}</td>
                                                                <td>{{$item->resthours}}</td>
                                                                <td>{{$item->workhours_each_day}}</td>
                                                                <td>{{$item->workhours_each_month}}</td>
                                                                <td>{{$item->workhours_each_year}}</td>
                                                                @php
                                                                    $workdays++;
                                                                    $is_holiday = 0;
                                                                @endphp

                                                            @elseif (($item->employee_num == $employee_num) && ($item_month == $month) && ($item_day == $num) && ($item->starttime != null) && ($item->endtime == null))
                                                                <td>{{date('H:i', strtotime($item->starttime))}}</td>
                                                                <td colspan="2" class="text-center">未入力</td>
                                                                <td colspan="3" class="text-center">未計算</td>
                                                                @php
                                                                $is_holiday = 0;
                                                                @endphp

                                                            @endif

                                                            @if ($item_month == $month)
                                                                @php
                                                                $this_workhours_each_month = $item->workhours_each_month;
                                                                $this_workhours_each_year = $item->workhours_each_year;
                                                                @endphp
                                                            @endif
                                                        @endforeach

                                                        @if ($is_holiday == 1 && $num <= $maxday && date("Y-m-d", $this_day) <= date("Y-m-d"))
                                                            <td colspan="6" class="text-center text-info">休</td>
                                                        @elseif ($num > $maxday || date("Y-m-d", $this_day) > date("Y-m-d"))
                                                            <td colspan="6" class="text-center text-info">&nbsp;</td>
                                                        @else
                                                            @php
                                                                $is_holiday = 1;
                                                            @endphp
                                                        @endif
                                                    </tr>
                                                @endfor

                                                <tr class="table-primary">
                                                    <td class="text-center">計</td>
                                                    <td colspan="4" class="text-center">勤務日数：{{$workdays}}日</td>
                                                    <td>{{$this_workhours_each_month}}</td>
                                                    <td>{{$this_workhours_each_year}}</td>
                                                </tr>
                                            </table>
                                                @php
                                                $count_employee_num = 0;
                                                @endphp
                                            @else
                                            <table class="table table-bordered table-sm table-striped text-right">
                                                <tr class="table-primary text-center">
                                                    <th colspan="6">{{$employee_num}}：{{$employee_name}}</th>
                                                </tr>
                                                <tr class="table-primary text-center">
                                                    <th colspan="6">{{$month}}</th>
                                                </tr>
                                                <tr class="table-primary text-center">
                                                    <th colspan="2">打刻<br>時間</th>
                                                    <th colspan="2">時間<br>[h]</th>
                                                    <th colspan="2">累積<br>[h]</th>
                                                </tr>
                                                <tr class="table-primary text-center">
                                                    <th>出<br>勤</th>
                                                    <th>退<br>勤</th>
                                                    <th>休<br>憩</th>
                                                    <th>実<br>労</th>
                                                    <th>月</th>
                                                    <th>年</th>
                                                </tr>
                                                @php
                                                    $maxday = date('t', strtotime($month)); // 月の日数取得
                                                    $workdays = 0; // 勤務日数初期化
                                                    $week = array("日", "月", "火", "水", "木", "金", "土");
                                                    $this_workhours_each_month = 0; // 月労働時間初期化
                                                    $this_workhours_each_year = 0; // 年労働時間初期化
                                                @endphp

                                                @for ($num = 1; $num <= 31; $num++)
                                                    @php
                                                        $is_holiday=1;
                                                        $this_day=mktime(0, 0, 0, date("m", strTotime($month)), $num, date("Y", strTotime($month)));
                                                    @endphp
                                                    <tr>
                                                        @foreach ($items as $item)
                                                            @php
                                                                $item_month = date("Y-m", strTotime($item->date)); // データの年月を取得
                                                                $item_day = date("d", strTotime($item->date)); // データの日付を取得
                                                            @endphp

                                                            @if (($item->employee_num == $employee_num) && ($item_month == $month) && ($item_day == $num) && ($item->workhours_each_day != null))
                                                                <td>{{date('H:i', strtotime($item->starttime))}}</td>
                                                                <td>{{date('H:i', strtotime($item->endtime))}}</td>
                                                                <td>{{$item->resthours}}</td>
                                                                <td>{{$item->workhours_each_day}}</td>
                                                                <td>{{$item->workhours_each_month}}</td>
                                                                <td>{{$item->workhours_each_year}}</td>
                                                                @php
                                                                    $workdays++;
                                                                    $is_holiday = 0;
                                                                @endphp

                                                            @elseif (($item->employee_num == $employee_num) && ($item_month == $month) && ($item_day == $num) && ($item->starttime != null) && ($item->endtime == null))
                                                                <td>{{date('H:i', strtotime($item->starttime))}}</td>
                                                                <td colspan="2" class="text-center">未入力</td>
                                                                <td colspan="3" class="text-center">未計算</td>
                                                                @php
                                                                $is_holiday = 0;
                                                                @endphp

                                                            @endif

                                                            @if ($item_month == $month)
                                                                @php
                                                                $this_workhours_each_month = $item->workhours_each_month;
                                                                $this_workhours_each_year = $item->workhours_each_year;
                                                                @endphp
                                                            @endif
                                                        @endforeach

                                                        @if ($is_holiday == 1 && $num <= $maxday && date("Y-m-d", $this_day) <= date("Y-m-d"))
                                                            <td colspan="6" class="text-center text-info">休</td>
                                                        @elseif ($num > $maxday || date("Y-m-d", $this_day) > date("Y-m-d"))
                                                            <td colspan="6" class="text-center text-info">&nbsp;</td>
                                                        @else
                                                            @php
                                                                $is_holiday = 1;
                                                            @endphp
                                                        @endif
                                                    </tr>
                                                @endfor

                                                <tr class="table-primary">
                                                    <td colspan="4" class="text-center">勤務日数：{{$workdays}}日</td>
                                                    <td>{{$this_workhours_each_month}}</td>
                                                    <td>{{$this_workhours_each_year}}</td>
                                                </tr>
                                            </table>
                                            @endif
                                        </TD>
                                    @endif
                                @endforeach
                            @endforeach
                        </TR>
                    </TABLE>
                </div>
                <div class="col-4 bg-light p-1">
                    <div id="chartPNG"></div>
                    @php
                        $grapf_values = array();
                        $graph_dates = array();
                        $i = 0;

                        foreach ($months as $month) { 
                            $maxday = date('t', strtotime($month));
                            for ($num = 1; $num <= $maxday; $num++) {
                                foreach ($employee_nums as $employee_num => $employee_name) {
                                    // 一旦、全ての日付に値を代入しておく
                                    $grapf_values[$i] = array(
                                        'employee_num' => $employee_num,
                                        'name' => $employee_name,
                                        'day' => date("y/m/d", mktime(0, 0, 0, date("m", strTotime($month)), $num, date("Y", strTotime($month)))),
                                        'workhours_each_day' => 0,
                                        'workhours_each_month' => 0,
                                        'workhours_each_year' => 0,
                                    );

                                    // データがある日付に対して、データ挿入する
                                    foreach ($items as $item) {
                                        $item_month = date("Y-m", strTotime($item->date));
                                        $item_day = date("d", strTotime($item->date));
                                        
                                        if ($item->employee_num == $employee_num && $item_month == $month && $item_day == $num && $item->workhours_each_day != null) {
                                            $grapf_values[$i] = array(
                                                'employee_num' => $employee_num,
                                                'name' => $employee_name,
                                                'day' => date("y/m/d", mktime(0, 0, 0, date("m", strTotime($month)), $num, date("Y", strTotime($month)))),
                                                'workhours_each_day' => $item->workhours_each_day,
                                                'workhours_each_month' => $item->workhours_each_month,
                                                'workhours_each_year' => $item->workhours_each_year,
                                            );
                                        };
                                    };
                                    $i++;
                                };
                                $graph_dates[$i] = array(
                                        'day' => date("y/m/d", mktime(0, 0, 0, date("m", strTotime($month)), $num, date("Y", strTotime($month)))),
                                    );
                            };
                        };
                    @endphp

                    <script src="http://code.jquery.com/jquery-1.11.0.min.js"></script>
                    <script src="https://www.google.com/jsapi"></script>
                    <script>
                        google.charts.load("current", {packages:["corechart"]});
                        google.charts.setOnLoadCallback(drawChart);
                        function drawChart() {
                            var data = google.visualization.arrayToDataTable([
                                <?php
                                    if ($this_name != 'all') {
                                        echo '["日付","', $this_graph_title, '"],';
                                        foreach ($grapf_values as $grapf_value) {
                                            if ($this_employee_num == $grapf_value['employee_num']) {
                                                echo '[ "', $grapf_value['day'],'",', $grapf_value[$this_graph], '],';
                                            };
                                        };
                                    }else{
                                        echo '["日付"';
                                        foreach ($employee_nums as $employee_num => $employee_name) {
                                            echo ', "', $employee_name,'"';
                                        };
                                        echo '],';

                                        foreach ($graph_dates as $graph_date) {
                                            echo '[ "', $graph_date['day'],'"';
                                            foreach ($grapf_values as $grapf_value) {
                                                foreach ($employee_nums as $employee_num => $employee_name) {
                                                    if ($grapf_value['day'] == $graph_date['day'] && $employee_num == $grapf_value['employee_num']) {
                                                        echo ',', $grapf_value[$this_graph];
                                                    };
                                                };
                                            };
                                            echo '],';
                                        };
                                        
                                    };
                                ?>
                            ]);
                            var view = new google.visualization.DataView(data);
                            <?php
                                if ($this_name != 'all') {
                                    echo 'view.setColumns([0, 1, { calc: "stringify", sourceColumn: 1, type: "string", role: "annotation"}]);';
                                };
                            ?>
                            var options = {
                                height: 980,
                                chartArea: {'top': '50', 'height':'90%'},
                                bar: {groupWidth: '95%'},
                                vAxis:{textStyle: {fontSize:9}},
                                hAxis:{textStyle: {fontSize:12}},
                                <?php
                                    if ($this_name != 'all') {
                                        echo 'title: "【', $this_name, 'さん】の', $this_graph_title, '",';
                                        echo 'legend: { position: "none" },';
                                        echo 'annotations: {textStyle: {fontSize:9}},';
                                    }else{
                                        echo 'title: "【全員】の', $this_graph_title, '",';
                                        echo 'legend: { position: "top", maxLines:10 },';
                                        echo 'isStacked: true';
                                    }
                                ?>
                            };
                            var chart = new google.visualization.BarChart(document.getElementById("chartPNG"));
                            chart.draw(view, options);
                        }
                    </script>
                </div>
            </div>
        </div>
    </div>
@endsection