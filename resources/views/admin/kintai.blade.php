@extends('layouts.layout_admin_menu')

@section('admin_content')
<div class="row">
    <!-- 職番抽出 -->
    @php
        $employee_nums = $items->sort()->pluck('name','employee_num')->unique();
    @endphp
    <!-- 職番抽出end -->

    <div class="col-3">
        @php
            // $itemから最新データを取得するため空回しする
            foreach ($items as $item){}

            // 入力初期値設定
            if ($item->date != date("Y-m-d")) {
                $atdate = date("Y-m-d");
                $atstarttime = null;
                $atendtime = null;
                $atresthours = null;
            } else {
                $atdate = $item->date;
                $atstarttime = $item->starttime;
                $atendtime = $item->endtime;
                $atresthours = $item->resthours;
            }
            // 入力初期値設定end

            // 修正氏名の選択
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

            // 表示月の選択
            if(isset($_GET['atmonth'])){
                $this_month = $_GET['atmonth'];
            } else {
                $this_month = date("Y-m"); // 現在の年月を取得
            }

            $months = array($this_month); // 配列を作成（実績と共有）
            // 表示月の選択end

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
            <form class="form w-100" method="get" onsubmit="return false;" action="/admin/kintai/{{Auth::id()}}" >
                <table class="table">
                    <tr>
                        <th>対象者</th>
                        <td>
                            <select class="form-control form-select w-100" name="atname" aria-label="出退勤修正対象者を表示">
                                <option selected value="">選択してください</option>
                                @foreach ($employee_nums as $employee_num => $employee_name)
                                    @if ($this_name == $employee_name)
                                        <option selected value="{{$employee_name}}">{{$employee_name}}</option>
                                    @else
                                        <option value="{{$employee_name}}">{{$employee_name}}</option>
                                    @endif
                                @endforeach
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th>表示月</th>
                        <td>
                            <input class="form-control" name="atmonth" type="month" max="{{date("Y-m")}}" value="{{$this_month}}">
                        </td>
                    </tr>
                    <tr>
                        <th>グラフ表示</th>
                        <td>
                            <select class="form-control form-select w-100" name="atgrapf" aria-label="グラフ表示内容を表示">
                                @foreach ($grapf_contents as $grapf_content => $grapf_title)
                                    @if ($this_graph == $grapf_content)
                                        <option selected value="{{$grapf_content}}">{{$grapf_title}}</option>
                                    @else
                                        <option value="{{$grapf_content}}">{{$grapf_title}}</option>
                                    @endif
                                @endforeach
                            </select>
                        </td>
                    </tr>
                </table>
                <div class="d-grid text-center">
                    <button class="btn btn-secondary btn-sm w-75" type="button" onclick="submit();">表示更新</button>
                </div>
            </form>
        </div>

        <div class="row">
            <form class="form mt-5 w-100" method="POST" onsubmit="return false;" action="/admin/update/{{ $this_id ?? ''}}">
                @csrf
                <table class="table">
                    <tr>
                        <th>氏名</th>
                        <td><input type="text" value="{{$this_name}}" name="name" class="form-control" readonly></td>
                    </tr>
                    <tr>
                        <th>職番</th>
                        <td><input type="number" value="{{$this_employee_num ?? ''}}" name="employee_num" class="form-control" readonly></td>
                    </tr>
                    <tr>
                        <th>日付</th>
                        <td><input class="form-control" name="date" type="date" max="{{date("Y-m-d")}}" value="{{$atdate}}"></td>
                    </tr>
                    <tr>
                        <th>出勤<br>時間</th>
                        <td>
                            <div class="btn-group" role="group" aria-label="出勤ボタングループ">
                                <button id="nowstart" type="button" class="btn btn-primary">現在時刻</button>
                                <button id="resetstart" type="button" class="btn btn-danger">初期化</button>
                            </div>
                            <input type="time" value="{{$atstarttime}}" name="starttime" class="form-control">
                        </td>
                    </tr>
                    <tr>
                        <th>退勤<br>時間</th>
                        <td>
                            <div class="btn-group" role="group" aria-label="退勤ボタングループ">
                                <button id="nowend" type="button" class="btn btn-primary">現在時刻</button>
                                <button id="resetend" type="button" class="btn btn-danger">初期化</button>
                            </div>
                            <input type="time" value="{{$atendtime}}" name="endtime" class="form-control">
                        </td>
                    </tr>
                    <tr>
                        <th>休憩<br>時間</th>
                        <td><input type="number" value="{{$atresthours}}" step="0.25" min="1.00" name="resthours" class="form-control"></td>
                    </tr>
                </table>
                <div class="input_alert">
                    @if (($item->workhours_each_day != "") && ($item->resthours != ""))
                    <p>本日の勤怠は入力済です。</p>
                    @elseif (($item->starttime != "") && ($item->endtime == ""))
                    <p>本日の出勤打刻は入力済です。<br>退勤打刻を忘れずに入力をお願いします。</p>
                    @endif
                </div>
                <div class="d-grid text-center">
                    <button class="btn btn-success btn-lg w-75" type="button" onclick="submit();">登録する</button>
                </div>
            </form>
        </div>
    </div>
    <div class="col-3 table-responsive">
        <div class="row">
            <TABLE style="font-size:0.5rem">
                <TR>
                @foreach ($months as $month)
                    @php
                        $count_employee_num = 1; // 初回ユーザー判定変数
                    @endphp

                    @foreach ($employee_nums as $employee_num => $employee_name)
                        @if ($this_name == $employee_name)
                            <TD>
                                @if ($count_employee_num == 1)
                                    <table class="table table-bordered table-sm table-striped text-right">
                                        <tr class="table-primary text-center">
                                            <th colspan="2"></th>
                                            <th colspan="6">{{$employee_num}}：{{$employee_name}}</th>
                                        </tr>
                                        <tr class="table-primary text-center">
                                            <th rowspan="2">日付</th>
                                            <th rowspan="2">曜日</th>
                                            <th colspan="2">打刻時間</th>
                                            <th colspan="2">時間[h]</th>
                                            <th colspan="2">累積[h]</th>
                                        </tr>
                                        <tr class="table-primary text-center">
                                            <th>出勤</th>
                                            <th>退勤</th>
                                            <th>休憩</th>
                                            <th>実労</th>
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

                                        @for ($num = 1; $num <= date('t', strtotime($month)); $num++)
                                            @php $is_holiday=1; @endphp
                                            <tr>
                                                @if ($num <= $maxday)
                                                    @php
                                                        $this_day=mktime(0, 0, 0, date("m", strTotime($month)), $num, date("Y", strTotime($month)));
                                                        $youbi=$week[date('w', $this_day)];
                                                    @endphp
                                                    <th class="table-primary text-center">{{$num}}</th>
                                                    <th class="table-primary text-center">{{$youbi}}</th>
                                                @else
                                                    <th class="table-primary text-center">&nbsp;</th>
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

                                                @if ($is_holiday == 1 && date("Y-m-d", $this_day) <= date("Y-m-d"))
                                                    <td colspan="6" class="text-center text-info">休</td>
                                                @elseif (date("Y-m-d", $this_day) > date("Y-m-d"))
                                                    <td colspan="6" class="text-center text-info">&nbsp;</td>
                                                @else
                                                    @php
                                                        $is_holiday = 1;
                                                    @endphp
                                                @endif
                                            </tr>
                                        @endfor

                                        <tr class="table-primary">
                                            <td colspan="2" class="text-center">合計</td>
                                            <td colspan="4" class="text-center">勤務日数：{{$workdays}}日</td>
                                            <td>{{$this_workhours_each_month}}</td>
                                            <td>{{$this_workhours_each_year}}</td>
                                        </tr>
                                    </table>
                                    @php
                                    $count_employee_num = 0;
                                    @endphp
                                
                                @endif
                            </TD>
                        @endif
                    @endforeach
                @endforeach
                </TR>
            </TABLE>
        </div>
    </div>
    <div class="col-6 bg-light p-1">
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
                    if ($this_name != '') {
                        echo '["日付","', $this_graph_title, '"],';
                        foreach ($grapf_values as $grapf_value) {
                            if ($this_employee_num == $grapf_value['employee_num']) {
                                echo '[ "', $grapf_value['day'],'",', $grapf_value[$this_graph], '],';
                            };
                        };
                    };
                    ?>
                ]);

                var view = new google.visualization.DataView(data);
                view.setColumns([0, 1, { calc: "stringify", sourceColumn: 1, type: "string", role: "annotation"}]);
                var options = {
                    height: 980,
                    chartArea: {'top': '50', 'height':'90%'},
                    bar: {groupWidth: "95%"},
                    vAxis:{textStyle: {fontSize:9}},
                    hAxis:{textStyle: {fontSize:12}},
                    title: "【{{$this_name}}さん】の{{$this_graph_title}}",
                    legend: { position: "none" },
                    annotations: {textStyle: {fontSize:9}},
                };
                var chart = new google.visualization.BarChart(document.getElementById("chartPNG"));
                chart.draw(view, options);
            }
        </script>
    </div>
</div>
@endsection