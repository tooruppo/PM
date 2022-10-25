<?php

namespace App\Http\Controllers\Admin; // Adminを追加

use App\Http\Controllers\Controller; //　追加

use Illuminate\Http\Request;
use App\User;
use App\Dayworkhour;
use Illuminate\Support\Facades\DB;


class KintaiController extends Controller
{
    public function show()
    {
        $items = User::where('id', '!=', null)
            ->leftjoin('dayworkhours', 'users.employee_num', '=', 'dayworkhours.employee_num')
            ->where('date', '!=', null)
            ->where('date', '<=', date('Y-m-d'))
            ->orderBy('date')
            ->get();
        return view('admin.kintai', ['items' => $items]);
    }

    public function index()
    {
        $items = User::where('id', '!=', null)
            ->leftjoin('dayworkhours', 'users.employee_num', '=', 'dayworkhours.employee_num')
            ->orderBy('date')
            ->get();
        return view('admin.jisseki', ['items' => $items]);
    }

    public function update(Request $request, $id)
    {
        // 入力日付データをデータベースから取得しておく（念のため同日の重複データがあった場合はアップデート日最新を取得）
        $requestitem = Dayworkhour::where('employee_num', '=', $request->employee_num)
            ->where('date', '=', $request->date)
            ->orderBy('updated_at', 'desc')
            ->First();
        // 退勤打刻時の場合
        if (($request->starttime == null) && ($request->endtime != null) && ($requestitem->starttime != null)) {
            $request->starttime = $requestitem->starttime; // は、DBの出勤打刻データを使用する
        };
        // 労働時間算出のための出勤時間算出（15分単位）
        if ($request->starttime != "") {
            //出勤時間の切り上げ操作
            $timestamp = strtotime($request->starttime);
            $minute = $timestamp / 3600 - floor($timestamp / 3600);
            if ($minute < 0.25) {
                $minute = 0.25;
            } elseif ($minute < 0.5) {
                $minute = 0.5;
            } elseif ($minute < 0.75) {
                $minute = 0.75;
            } else {
                $minute = 1;
            }
            $retimestamp = (floor($timestamp / 3600) + $minute) * 3600;
        };
        // 労働時間算出のための退勤時間算出（15分単位）
        if ($request->endtime != "") {
            //退勤時間の切り下げ操作
            $timestamp2 = strtotime($request->endtime);
            $minute2 = $timestamp2 / 3600 - floor($timestamp2 / 3600);
            if ($minute2 < 0.25) {
                $minute2 = 0;
            } elseif ($minute2 < 0.5) {
                $minute2 = 0.25;
            } elseif ($minute2 < 0.75) {
                $minute2 = 0.5;
            } else {
                $minute2 = 0.75;
            }
            $retimestamp2 = (floor($timestamp2 / 3600) + $minute2) * 3600;
        };
        // 1日労働時間算出
        if (($request->starttime != "") && ($request->endtime != "")) {
            $request->workhours_each_day = round((($retimestamp2 - $retimestamp) / 3600 - $request->resthours), 2);
        };
        // データベースへのデータ保存（データベースにデータがなければ新規生成、あればアップデートを行う）
        if (empty($requestitem)) {
            Dayworkhour::where('employee_num', '=', $request->employee_num)
                ->create([
                    'employee_num' => $request->employee_num,
                    'date' => $request->date,
                    'starttime' => $request->starttime,
                    'endtime' => $request->endtime,
                    'resthours' => $request->resthours,
                    'workhours_each_day' => $request->workhours_each_day,
                ]);
        } elseif (isset($requestitem)) {
            Dayworkhour::where('employee_num', '=', $request->employee_num)
                ->where('date', '=', $request->date)
                ->update([
                    'starttime' => $request->starttime,
                    'endtime' => $request->endtime,
                    'resthours' => $request->resthours,
                    'workhours_each_day' => $request->workhours_each_day,
                ]);
        };
        //  1日労働時間を基に、先ほどの入力データ日付以前も含めて、年／月の累積労働時間を再計算
        //　再度データを読み込む
        $updateitems = Dayworkhour::where('employee_num', '=', $request->employee_num)
            ->where('date', '!=', null)
            ->orderBy('date')
            ->get();
        //　今日の日付を取得
        $today = date("Y-m-d");
        //　データ保存
        foreach ($updateitems as $updateitem) {
            if ($updateitem->date <= $today) {
                $updateitem->workhours_each_month = Dayworkhour::where('employee_num', '=', $request->employee_num)
                    ->where('date', '!=', null)
                    ->orderBy('date')
                    ->where('date', '<=', $updateitem->date)
                    ->whereYear('date', date("Y", strTotime($updateitem->date)))
                    ->whereMonth('date', date("m", strTotime($updateitem->date)))
                    ->sum('workhours_each_day');

                $updateitem->workhours_each_year = Dayworkhour::where('employee_num', '=', $request->employee_num)
                    ->where('date', '!=', null)
                    ->orderBy('date')
                    ->where('date', '<=', $updateitem->date)
                    ->whereYear('date', date("Y", strTotime($updateitem->date)))
                    ->sum('workhours_each_day');
            }

            Dayworkhour::where('employee_num', '=', $request->employee_num)
                ->where('date', '=', $updateitem->date)
                ->update([
                    'workhours_each_month' => $updateitem->workhours_each_month,
                    'workhours_each_year' => $updateitem->workhours_each_year,
                ]);
        }
        //　全てを終了し「メニュー画面」に戻る
        return redirect('/admin/home');
    }
}
