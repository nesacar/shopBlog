<?php namespace App\Http\Controllers;

use App\Cart;
use App\Click;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Newsletter;
use App\Statistic;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StatisticsController extends Controller {

    public function __construct() {
        $this->middleware('admin');
    }

    public function yearNewsletter($id){
        $slug = 'newsletters';
        \Session::forget('search_od');
        \Session::forget('search_do');
        $start_date = date('Y-m-d', strtotime('-1 year'));
        $end_date = Carbon::now();
        $newsletter = Newsletter::find($id);
        $statistics = Statistic::getLastYearNewsletter($id, $start_date, $end_date);
        $clicks =  Statistic::prepareLastYearNewsletter($statistics, $end_date->month);
        $sum = Click::where('newsletter_id', $newsletter->id)->whereBetween('created_at', [$start_date, $end_date])->count();
        $average = round($sum/12);
        $search = false; $month = $end_date->month;
        return view('admin.statistics.yearNewsletter', compact('clicks', 'newsletter', 'sum', 'average', 'search', 'start_date', 'end_date', 'slug', 'month'));
    }

    public function lastYearNewsletter($id){
        $slug = 'newsletters';
        \Session::forget('search_od');
        \Session::forget('search_do');
        $start_date = date('Y-m-d', strtotime('-2 year'));
        $end_date = date('Y-m-d', strtotime('-1 year'));
        $newsletter = Newsletter::find($id);
        $statistics = Statistic::getLastYearNewsletter($id, $start_date, $end_date);
        $clicks =  Statistic::prepareLastYearNewsletter($statistics, date("m",strtotime($end_date)));
        $sum = Click::where('newsletter_id', $newsletter->id)->whereBetween('created_at', [$start_date, $end_date])->count();
        $average = round($sum/12);
        $search = false; $month = date("m",strtotime($end_date));
        return view('admin.statistics.lastYearNewsletter', compact('clicks', 'newsletter', 'sum', 'average', 'search', 'start_date', 'end_date', 'slug', 'month'));
    }

    public function monthNewsletter($id){
        $slug = 'newsletters';
        \Session::forget('search_od');
        \Session::forget('search_do');
        $start_date = date('Y-m-d', strtotime('-1 month'));
        $end_date = Carbon::now();
        $start_date = Carbon::parse($start_date);

        $newsletter = Newsletter::find($id);
        $month = $start_date->month; $day = date('d');
        $statistics = Statistic::getLastMonthNewsletter($id, $start_date, $end_date);
        $clicks =  Statistic::prepareLastMonthNewsletter($statistics, $month, $day);
        $sum = Click::where('newsletter_id', $newsletter->id)->whereBetween('created_at', [$start_date, $end_date])->count();
        $days = Statistic::getDays($month);
        $average = round($sum/$days);
        $search = false;
        return view('admin.statistics.monthNewsletter', compact('clicks', 'newsletter', 'sum', 'average', 'days', 'search', 'start_date', 'end_date', 'slug', 'day', 'month'));
    }

    public function lastMonthNewsletter($id){
        $slug = 'newsletters';
        \Session::forget('search_od');
        \Session::forget('search_do');
        $start_date = date('Y-m-d', strtotime('-2 month'));
        $end_date = date('Y-m-d', strtotime('-1 month'));
        $start_date = Carbon::parse($start_date);
        $end_date = Carbon::parse($end_date);
        $newsletter = Newsletter::find($id);
        $month = $start_date->month;
        $day = date('d');
        $statistics = Statistic::getLastMonthNewsletter($id, $start_date, $end_date);
        $clicks =  Statistic::prepareLastMonthNewsletter($statistics, $month, $day);
        $sum = Click::where('newsletter_id', $newsletter->id)->whereBetween('created_at', [$start_date, $end_date])->count();
        $days = Statistic::getDays($month);
        $average = round($sum/$days);
        $search = false;
        return view('admin.statistics.lastMonthNewsletter', compact('clicks', 'newsletter', 'sum', 'average', 'days', 'search', 'start_date', 'end_date', 'slug', 'day', 'month'));
    }

    public function dayNewsletter($id){
        $slug = 'newsletters';
        \Session::forget('search_od');
        \Session::forget('search_do');
        $start_date = date('Y-m-d H:m:s', strtotime('-1 day'));
        $end_date = Carbon::now();
        $start_date = Carbon::parse($start_date);
        $newsletter = Newsletter::find($id);
        $hour = $end_date->hour;
        $statistics = Statistic::getLastDayNewsletter($id, $start_date, $end_date);
        $clicks =  Statistic::prepareLastDayNewsletter($statistics, $hour);
        $sum = Click::where('newsletter_id', $newsletter->id)->whereBetween('created_at', [$start_date, $end_date])->count();
        $average = round($sum/24);
        $search = false;
        return view('admin.statistics.dayNewsletter', compact('clicks', 'newsletter', 'sum', 'average', 'search', 'start_date', 'end_date', 'slug', 'hour'));
    }

    public function lastDayNewsletter($id){
        $slug = 'newsletters';
        \Session::forget('search_od');
        \Session::forget('search_do');
        $start_date = date('Y-m-d H:m:s', strtotime('-2 day'));
        $end_date = date('Y-m-d H:m:s', strtotime('-1 day'));
        $start_date = Carbon::parse($start_date);
        $end_date = Carbon::parse($end_date);
        $newsletter = Newsletter::find($id);
        $hour = $end_date->hour;
        $statistics = Statistic::getLastDayNewsletter($id, $start_date, $end_date);
        $clicks =  Statistic::prepareLastDayNewsletter($statistics, $hour);
        $sum = Click::where('newsletter_id', $newsletter->id)->whereBetween('created_at', [$start_date, $end_date])->count();
        $average = round($sum/24);
        $search = false;
        return view('admin.statistics.lastDayNewsletter', compact('clicks', 'newsletter', 'sum', 'average', 'search', 'start_date', 'end_date', 'slug', 'hour'));
    }

    public function searchNewsletter(Requests\SearchNewsletterClicks $request){
        $slug = 'newsletters';
        $start_date = $request->input('od');
        $end_date = $request->input('do');
        if(($start_date == '')){
            return redirect()->back()->with('error', 'Pogrešan opseg datuma!');
        }
        if(($end_date == '')){
            $end_date = Carbon::now();
        }
        $start_date = Carbon::parse($start_date);
        $end_date = Carbon::parse($end_date);

        \Session::set('search_od', $start_date);
        \Session::set('search_do', $end_date);

        $newsletter = Newsletter::find($request->input('newsletter'));
        $difference = $start_date->diffInDays($end_date);
        $year = $end_date->year;
        $month = $end_date->month;
        $day = $end_date->day;
        $hour = $end_date->hour;
        if($difference < 1){
            $differenceInHours = $start_date->diffInHours($end_date); $differenceInHours++;
            $statistics = Statistic::getLastDayNewsletter($newsletter->id, $start_date, $end_date);
            $clicks =  Statistic::prepareSearchDayNewsletter($statistics, 'h', $start_date, $end_date);
            $sum = Click::where('newsletter_id', $newsletter->id)->whereBetween('created_at', [$start_date, $end_date])->count();

            $average = round($sum/$differenceInHours);
            $search = true;
            return view('admin.statistics.lastDaySearchNewsletter', compact('clicks', 'newsletter', 'sum', 'average', 'search', 'start_date', 'end_date', 'slug', 'year', 'month', 'day', 'hour'));
        }elseif($difference < 30){
            $differenceInDays = $start_date->diffInDays($end_date); $differenceInDays++;
            $month = $start_date->month;
            $statistics = Statistic::getLastMonthNewsletter($newsletter->id, $start_date, $end_date);
            $clicks =  Statistic::prepareSearchMonthNewsletter($statistics, 'd', $start_date, $end_date, $month);
            $sum = Click::where('newsletter_id', $newsletter->id)->whereBetween('created_at', [$start_date, $end_date])->count();

            $days = Statistic::getDays($month);
            $average = round($sum/$differenceInDays);
            $search = true;
            return view('admin.statistics.lastMonthSearchNewsletter', compact('clicks', 'newsletter', 'sum', 'average', 'days', 'search', 'start_date', 'end_date', 'slug', 'year', 'month', 'day', 'hour'));
        }elseif($difference < 365){
            $differenceInMonth = $start_date->diffInMonths($end_date); $differenceInMonth++;
            $statistics = Statistic::getLastYearNewsletter($newsletter->id, $start_date, $end_date);
            $clicks =  Statistic::prepareSearchYearNewsletter($statistics, $start_date, $end_date);
            $sum = Click::where('newsletter_id', $newsletter->id)->whereBetween('created_at', [$start_date, $end_date])->count();

            $average = round($sum/$differenceInMonth);
            $search = true;
            return view('admin.statistics.lastYearSearchNewsletter', compact('clicks', 'newsletter', 'sum', 'average', 'search', 'start_date', 'end_date', 'slug', 'year', 'month', 'day', 'hour'));
        }else{
            $differenceInYear = $start_date->diffInYears($end_date); $differenceInYear++;
            $statistics = Statistic::getMoreYearNewsletter($newsletter->id, $start_date, $end_date);
            $clicks =  Statistic::prepareMoreYearNewsletter($statistics, $start_date, $end_date);
            $sum = Click::where('newsletter_id', $newsletter->id)->whereBetween('created_at', [$start_date, $end_date])->count();

            $average = round($sum/$differenceInYear);
            $search = true;
            return view('admin.statistics.lastMoreSearchNewsletter', compact('clicks', 'newsletter', 'sum', 'average', 'search', 'start_date', 'end_date', 'slug', 'year', 'month', 'day', 'hour'));
        }
    }

    public function searchSubscribers(Request $request){
        $slug = 'newsletters';
        $subscribers = Click::getNewsletterSubscriberClicks($request->input('newsletter'), $request->input('start'), $request->input('end'));
        $newsletter = Newsletter::find($request->input('newsletter'));
        return view('admin.statistics.newsletterSubscribers', compact('subscribers', 'newsletter', 'slug'));
    }

    public function yearCart(){
        \Session::forget('search_od');
        \Session::forget('search_do');
        $start_date = date('Y-m-d h:m:s', strtotime('-1 year'));
        $end_date = Carbon::now();
        $month = $end_date->month;

        $statistics = Statistic::getYearCart($start_date,  $end_date, 0);
        $hold = Statistic::prepareLastYearNewsletter($statistics, $end_date->month);

        $statistics = Statistic::getYearCart($start_date,  $end_date, 1);
        $confirmed = Statistic::prepareLastYearNewsletter($statistics, $end_date->month);

        $statistics = Statistic::getYearCart($start_date,  $end_date, 2);
        $rejected = Statistic::prepareLastYearNewsletter($statistics, $end_date->month);

        $statistics = Statistic::getYearCart($start_date,  $end_date, 3);
        $canceled = Statistic::prepareLastYearNewsletter($statistics, $end_date->month);

        $sum = Cart::select(DB::raw('SUM(sum) AS suma'))->whereBetween('created_at', [$start_date, $end_date])->first();
        $sum = $sum->suma;
        $average = round($sum/12);
        return view('admin.statistics.yearCart', compact('sum', 'average', 'rejected', 'confirmed', 'hold', 'canceled', 'start_date', 'end_date', 'month'));
    }

    public function lastYearCart(){
        \Session::forget('search_od');
        \Session::forget('search_do');
        $start_date = date('Y-m-d', strtotime('-2 year'));
        $end_date = date('Y-m-d', strtotime('-1 year'));
        $start_date = Carbon::parse($start_date);
        $end_date = Carbon::parse($end_date);
        $month = $end_date->month;

        $statistics = Statistic::getYearCart($start_date, $end_date, 0);
        $hold = Statistic::prepareLastYearNewsletter($statistics, $end_date->month); //set by month

        $statistics = Statistic::getYearCart($start_date, $end_date, 1);
        $confirmed = Statistic::prepareLastYearNewsletter($statistics, $end_date->month); //set by month

        $statistics = Statistic::getYearCart($start_date, $end_date, 2);
        $rejected = Statistic::prepareLastYearNewsletter($statistics, $end_date->month); //set by month

        $statistics = Statistic::getYearCart($start_date, $end_date, 3);
        $canceled = Statistic::prepareLastYearNewsletter($statistics, $end_date->month); //set by month

        $sum = Cart::select(DB::raw('SUM(sum) AS suma'))->whereBetween('created_at', [$start_date, $end_date])->first();
        $sum = $sum->suma;
        $average = round($sum/12);
        return view('admin.statistics.lastYearCart', compact('sum', 'average', 'rejected', 'confirmed', 'hold', 'canceled', 'start_date', 'end_date', 'month'));
    }

    public function monthCart(){
        \Session::forget('search_od');
        \Session::forget('search_do');
        $start_date = date('Y-m-d', strtotime('-1 month'));
        $end_date = Carbon::now();
        $start_date = Carbon::parse($start_date);
        $end_date = Carbon::parse($end_date);
        $month = $start_date->month;
        $day = $end_date->day;

        $statistics = Statistic::getLastMonthCart($start_date, $end_date, 0);
        $hold =  Statistic::prepareLastMonthNewsletter($statistics, $month, $day);

        $statistics = Statistic::getLastMonthCart($start_date, $end_date, 1);
        $confirmed =  Statistic::prepareLastMonthNewsletter($statistics, $month, $day);

        $statistics = Statistic::getLastMonthCart($start_date, $end_date, 2);
        $rejected =  Statistic::prepareLastMonthNewsletter($statistics, $month, $day);

        $statistics = Statistic::getLastMonthCart($start_date, $end_date, 3);
        $canceled =  Statistic::prepareLastMonthNewsletter($statistics, $month, $day);


        $sum = Cart::select(DB::raw('SUM(sum) AS suma'))->whereBetween('created_at', [$start_date, $end_date])->first();
        $sum = $sum->suma;
        $days = Statistic::getDays($month);
        $average = round($sum/$days);
        $search = false;
        return view('admin.statistics.monthCart', compact('rejected', 'confirmed', 'hold', 'canceled', 'newsletter', 'sum', 'average', 'days', 'search', 'start_date', 'end_date', 'month', 'day'));
    }

    public function lastMonthCart(){
        \Session::forget('search_od');
        \Session::forget('search_do');
        $start_date = date('Y-m-d', strtotime('-2 month'));
        $end_date = date('Y-m-d', strtotime('-1 month'));
        $start_date = Carbon::parse($start_date);
        $end_date = Carbon::parse($end_date);
        $month = $start_date->month;
        $day = $end_date->day;

        $statistics = Statistic::getLastMonthCart($start_date, $end_date, 0);
        $hold =  Statistic::prepareLastMonthNewsletter($statistics, $month, $day);

        $statistics = Statistic::getLastMonthCart($start_date, $end_date, 1);
        $confirmed =  Statistic::prepareLastMonthNewsletter($statistics, $month, $day);

        $statistics = Statistic::getLastMonthCart($start_date, $end_date, 2);
        $rejected =  Statistic::prepareLastMonthNewsletter($statistics, $month, $day);

        $statistics = Statistic::getLastMonthCart($start_date, $end_date, 3);
        $canceled =  Statistic::prepareLastMonthNewsletter($statistics, $month, $day);

        $sum = Cart::select(DB::raw('SUM(sum) AS suma'))->whereBetween('created_at', [$start_date, $end_date])->first();
        $sum = $sum->suma;
        $days = Statistic::getDays($month);
        $average = round($sum/$days);
        $search = false;
        return view('admin.statistics.lastMonthCart', compact('rejected', 'confirmed', 'hold', 'canceled', 'newsletter', 'sum', 'average', 'days', 'search', 'start_date', 'end_date', 'month', 'day'));
    }

    public function dayCart(){
        \Session::forget('search_od');
        \Session::forget('search_do');
        $start_date = date('Y-m-d H:m:s', strtotime('-1 day'));
        $end_date = Carbon::now();
        $start_date = Carbon::parse($start_date);
        $hour = $end_date->hour;

        $statistics = Statistic::getLastDayCart($start_date, $end_date, 0);
        $hold =  Statistic::prepareLastDayNewsletter($statistics, $hour);

        $statistics = Statistic::getLastDayCart($start_date, $end_date, 1);
        $confirmed =  Statistic::prepareLastDayNewsletter($statistics, $hour);

        $statistics = Statistic::getLastDayCart($start_date, $end_date, 2);
        $rejected =  Statistic::prepareLastDayNewsletter($statistics, $hour);

        $statistics = Statistic::getLastDayCart($start_date, $end_date, 3);
        $canceled =  Statistic::prepareLastDayNewsletter($statistics, $hour);

        $sum = Cart::select(DB::raw('SUM(sum) AS suma'))->whereBetween('created_at', [$start_date, $end_date])->first();
        $sum = $sum->suma;
        $average = round($sum/24);
        $search = false;
        return view('admin.statistics.dayCart', compact('rejected', 'confirmed', 'hold', 'canceled', 'newsletter', 'sum', 'average', 'search', 'start_date', 'end_date', 'hour'));
    }

    public function lastDayCart(){
        \Session::forget('search_od');
        \Session::forget('search_do');
        $start_date = date('Y-m-d H:m:s', strtotime('-2 day'));
        $end_date = date('Y-m-d H:m:s', strtotime('-1 day'));
        $start_date = Carbon::parse($start_date);
        $end_date = Carbon::parse($end_date);
        $hour = $end_date->hour;

        $statistics = Statistic::getLastDayCart($start_date, $end_date, 0);
        $hold =  Statistic::prepareLastDayNewsletter($statistics, $hour);

        $statistics = Statistic::getLastDayCart($start_date, $end_date, 1);
        $confirmed =  Statistic::prepareLastDayNewsletter($statistics, $hour);

        $statistics = Statistic::getLastDayCart($start_date, $end_date, 2);
        $rejected =  Statistic::prepareLastDayNewsletter($statistics, $hour);

        $statistics = Statistic::getLastDayCart($start_date, $end_date, 3);
        $canceled =  Statistic::prepareLastDayNewsletter($statistics, $hour);

        $sum = Cart::select(DB::raw('SUM(sum) AS suma'))->whereBetween('created_at', [$start_date, $end_date])->first();
        $sum = $sum->suma;
        $average = round($sum/24);
        $search = false;
        return view('admin.statistics.lastDayCart', compact('rejected', 'confirmed', 'hold', 'canceled', 'newsletter', 'sum', 'average', 'search', 'start_date', 'end_date', 'hour'));
    }

    public function searchCart(Requests\SearchNewsletterClicks $request){
        $start_date = $request->input('od');
        $end_date = $request->input('do');
        if(($start_date == '')){
            return redirect()->back()->with('error', 'Pogrešan opseg datuma!');
        }
        if(($end_date == '')){
            $end_date = Carbon::now();
        }
        $start_date = Carbon::parse($start_date);
        $end_date = Carbon::parse($end_date);

        \Session::set('search_od', $start_date);
        \Session::set('search_do', $end_date);

        $difference = $start_date->diffInDays($end_date);
        $month = $end_date->month;
        if($difference < 1){
            $differenceInHour = $start_date->diffInHours($end_date); $differenceInHour++;
            $hour = $start_date->hour;
            $statistics = Statistic::getLastDayCart($start_date, $end_date, 0);
            $hold =  Statistic::prepareSearchDayNewsletter($statistics, 'h', $start_date, $end_date);

            $statistics = Statistic::getLastDayCart($start_date, $end_date, 1);
            $confirmed =  Statistic::prepareSearchDayNewsletter($statistics, 'h', $start_date, $end_date);

            $statistics = Statistic::getLastDayCart($start_date, $end_date, 2);
            $rejected =  Statistic::prepareSearchDayNewsletter($statistics, 'h', $start_date, $end_date);

            $statistics = Statistic::getLastDayCart($start_date, $end_date, 3);
            $canceled =  Statistic::prepareSearchDayNewsletter($statistics, 'h', $start_date, $end_date);

            $sum = Cart::select(DB::raw('SUM(sum) AS suma'))->whereBetween('created_at', [$start_date, $end_date])->first();
            $sum = $sum->suma;
            $average = round($sum/$differenceInHour);
            $search = true;
            return view('admin.statistics.lastDaySearchCart', compact('rejected', 'confirmed', 'hold', 'canceled', 'newsletter', 'sum', 'average', 'search', 'start_date', 'end_date', 'hour'));
        }elseif($difference < 30){
            $differenceInDays = $start_date->diffInDays($end_date); $differenceInDays++;
            $month = $start_date->month;
            $day = $start_date->day;
            $statistics = Statistic::getLastMonthCart($start_date, $end_date, 0);
            $hold =  Statistic::prepareSearchMonthNewsletter($statistics, 'd', $start_date, $end_date, $month);

            $statistics = Statistic::getLastMonthCart($start_date, $end_date, 1);
            $confirmed =  Statistic::prepareSearchMonthNewsletter($statistics, 'd', $start_date, $end_date, $month);

            $statistics = Statistic::getLastMonthCart($start_date, $end_date, 2);
            $rejected =  Statistic::prepareSearchMonthNewsletter($statistics, 'd', $start_date, $end_date, $month);

            $statistics = Statistic::getLastMonthCart($start_date, $end_date, 3);
            $canceled =  Statistic::prepareSearchMonthNewsletter($statistics, 'd', $start_date, $end_date, $month);

            $sum = Cart::select(DB::raw('SUM(sum) AS suma'))->whereBetween('created_at', [$start_date, $end_date])->first();
            $sum = $sum->suma;
            $days = Statistic::getDays($month);
            $average = round($sum/$differenceInDays);
            $search = true;
            return view('admin.statistics.lastMonthSearchCart', compact('rejected', 'confirmed', 'hold', 'canceled', 'newsletter', 'sum', 'average', 'days', 'search', 'start_date', 'end_date', 'month', 'day'));
        }elseif($difference < 365){
            $differenceInMonth = $start_date->diffInMonths($end_date); $differenceInMonth++;
            $month = $start_date->month;
            $statistics = Statistic::getYearCart($start_date, $end_date, 0);
            $hold =  Statistic::prepareSearchYearNewsletter($statistics, $start_date, $end_date);

            $statistics = Statistic::getYearCart($start_date, $end_date, 1);
            $confirmed =  Statistic::prepareSearchYearNewsletter($statistics, $start_date, $end_date);

            $statistics = Statistic::getYearCart($start_date, $end_date, 2);
            $rejected =  Statistic::prepareSearchYearNewsletter($statistics, $start_date, $end_date);

            $statistics = Statistic::getYearCart($start_date, $end_date, 3);
            $canceled =  Statistic::prepareSearchYearNewsletter($statistics, $start_date, $end_date);

            $sum = Cart::select(DB::raw('SUM(sum) AS suma'))->whereBetween('created_at', [$start_date, $end_date])->first();
            $sum = $sum->suma;
            $average = round($sum/$differenceInMonth);
            $search = true;
            return view('admin.statistics.lastYearSearchCart', compact('rejected', 'confirmed', 'hold', 'canceled', 'newsletter', 'sum', 'average', 'search', 'start_date', 'end_date', 'month'));
        }else{
            $differenceInYear = $start_date->diffInYears($end_date); $differenceInYear++;

            $statistics = Statistic::getMoreYearCart($start_date, $end_date, 0);
            $hold =  Statistic::prepareMoreYearNewsletter($statistics, $start_date, $end_date);

            $statistics = Statistic::getMoreYearCart($start_date, $end_date, 1);
            $confirmed =  Statistic::prepareMoreYearNewsletter($statistics, $start_date, $end_date);

            $statistics = Statistic::getMoreYearCart($start_date, $end_date, 2);
            $rejected =  Statistic::prepareMoreYearNewsletter($statistics, $start_date, $end_date);

            $statistics = Statistic::getMoreYearCart($start_date, $end_date, 3);
            $canceled =  Statistic::prepareMoreYearNewsletter($statistics, $start_date, $end_date);

            $sum = Cart::select(DB::raw('SUM(sum) AS suma'))->whereBetween('created_at', [$start_date, $end_date])->first();
            $sum = $sum->suma;
            $average = round($sum/$differenceInYear);
            $search = true;
            return view('admin.statistics.lastYearMoreSearchCart', compact('rejected', 'confirmed', 'hold', 'canceled', 'newsletter', 'sum', 'average', 'search', 'start_date', 'end_date', 'month'));
        }
    }

    public function yearOrder(){
        \Session::forget('search_od');
        \Session::forget('search_do');
        $start_date = date('Y-m-d', strtotime('-1 year'));
        $start_date = Carbon::parse($start_date);
        $end_date = Carbon::now();
        $month = $end_date->month;

        $statistics = Statistic::getYearOrder($start_date, $end_date, 0);
        $hold = Statistic::prepareLastYearNewsletter($statistics, $month);

        $statistics = Statistic::getYearOrder($start_date, $end_date, 1);
        $confirmed = Statistic::prepareLastYearNewsletter($statistics, $month);

        $statistics = Statistic::getYearOrder($start_date, $end_date, 2);
        $rejected = Statistic::prepareLastYearNewsletter($statistics, $month);

        $statistics = Statistic::getYearOrder($start_date, $end_date, 3);
        $canceled = Statistic::prepareLastYearNewsletter($statistics, $month);

        $sum = Cart::whereBetween('created_at', [$start_date, $end_date])->count();
        $average = round($sum/12);
        $order = true;
        return view('admin.statistics.yearCart', compact('sum', 'average', 'rejected', 'confirmed', 'hold', 'canceled', 'start_date', 'end_date', 'order', 'month'));
    }

    public function lastYearOrder(){
        \Session::forget('search_od');
        \Session::forget('search_do');
        $start_date = date('Y-m-d', strtotime('-2 year'));
        $end_date = date('Y-m-d', strtotime('-1 year'));
        $start_date = Carbon::parse($start_date);
        $end_date = Carbon::parse($end_date);
        $month = $end_date->month;

        $statistics = Statistic::getYearOrder($start_date, $end_date, 0);
        $hold = Statistic::prepareLastYearNewsletter($statistics, $month); //set by month

        $statistics = Statistic::getYearOrder($start_date, $end_date, 1);
        $confirmed = Statistic::prepareLastYearNewsletter($statistics, $month); //set by month

        $statistics = Statistic::getYearOrder($start_date, $end_date, 2);
        $rejected = Statistic::prepareLastYearNewsletter($statistics, $month); //set by month

        $statistics = Statistic::getYearOrder($start_date, $end_date, 3);
        $canceled = Statistic::prepareLastYearNewsletter($statistics, $month); //set by month

        $sum = Cart::whereBetween('created_at', [$start_date, $end_date])->count();
        $average = round($sum/12);
        $order = true;
        return view('admin.statistics.lastYearCart', compact('sum', 'average', 'rejected', 'confirmed', 'hold', 'canceled', 'start_date', 'end_date', 'order', 'month'));
    }

    public function monthOrder(){
        \Session::forget('search_od');
        \Session::forget('search_do');
        $start_date = date('Y-m-d', strtotime('-1 month'));
        $start_date = Carbon::parse($start_date);
        $end_date = Carbon::now();
        $month = $start_date->month; $day = $start_date->day;

        $statistics = Statistic::getLastMonthOrder($start_date, $end_date, 0);
        $hold =  Statistic::prepareLastMonthNewsletter($statistics, $month, $day);

        $statistics = Statistic::getLastMonthOrder($start_date, $end_date, 1);
        $confirmed =  Statistic::prepareLastMonthNewsletter($statistics, $month, $day);

        $statistics = Statistic::getLastMonthOrder($start_date, $end_date, 2);
        $rejected =  Statistic::prepareLastMonthNewsletter($statistics, $month, $day);

        $statistics = Statistic::getLastMonthOrder($start_date, $end_date, 3);
        $canceled =  Statistic::prepareLastMonthNewsletter($statistics, $month, $day);

        $sum = Cart::whereBetween('created_at', [$start_date, $end_date])->count();
        $days = Statistic::getDays($month);
        $average = round($sum/$days);
        $search = false; $order = true;
        return view('admin.statistics.monthCart', compact('rejected', 'confirmed', 'hold', 'canceled', 'newsletter', 'sum', 'average', 'days', 'search', 'start_date', 'end_date', 'order', 'month', 'day'));
    }

    public function lastMonthOrder(){
        \Session::forget('search_od');
        \Session::forget('search_do');
        $start_date = date('Y-m-d', strtotime('-2 month'));
        $end_date = date('Y-m-d', strtotime('-1 month'));
        $start_date = Carbon::parse($start_date);
        $end_date = Carbon::parse($end_date);
        $month = $start_date->month; $day = $end_date->day;

        $statistics = Statistic::getLastMonthOrder($start_date, $end_date, 0);
        $hold =  Statistic::prepareLastMonthNewsletter($statistics, $month, $day);

        $statistics = Statistic::getLastMonthOrder($start_date, $end_date, 1);
        $confirmed =  Statistic::prepareLastMonthNewsletter($statistics, $month, $day);

        $statistics = Statistic::getLastMonthOrder($start_date, $end_date, 2);
        $rejected =  Statistic::prepareLastMonthNewsletter($statistics, $month, $day);

        $statistics = Statistic::getLastMonthOrder($start_date, $end_date, 3);
        $canceled =  Statistic::prepareLastMonthNewsletter($statistics, $month, $day);

        $sum = Cart::whereBetween('created_at', [$start_date, $end_date])->count();
        $days = Statistic::getDays($month);
        $average = round($sum/$days);
        $search = false; $order = true;
        return view('admin.statistics.lastMonthCart', compact('rejected', 'confirmed', 'hold', 'canceled', 'newsletter', 'sum', 'average', 'days', 'search', 'start_date', 'end_date', 'order', 'month', 'day'));
    }

    public function dayOrder(){
        \Session::forget('search_od');
        \Session::forget('search_do');
        $start_date = date('Y-m-d H:m:s', strtotime('-1 day'));
        $end_date = Carbon::now();
        $start_date = Carbon::parse($start_date);
        $hour = $start_date->hour;

        $statistics = Statistic::getLastDayOrder($start_date, $end_date, 0);
        $hold =  Statistic::prepareLastDayNewsletter($statistics, $hour);

        $statistics = Statistic::getLastDayOrder($start_date, $end_date, 1);
        $confirmed =  Statistic::prepareLastDayNewsletter($statistics, $hour);

        $statistics = Statistic::getLastDayOrder($start_date, $end_date, 2);
        $rejected =  Statistic::prepareLastDayNewsletter($statistics, $hour);

        $statistics = Statistic::getLastDayOrder($start_date, $end_date, 3);
        $canceled =  Statistic::prepareLastDayNewsletter($statistics, $hour);

        $sum = Cart::whereBetween('created_at', [$start_date, $end_date])->count();
        $average = round($sum/24);
        $search = false; $order = true;
        return view('admin.statistics.dayCart', compact('rejected', 'confirmed', 'hold', 'canceled', 'newsletter', 'sum', 'average', 'search', 'start_date', 'end_date', 'order', 'hour'));
    }

    public function lastDayOrder(){
        \Session::forget('search_od');
        \Session::forget('search_do');
        $start_date = date('Y-m-d H:m:s', strtotime('-2 day'));
        $end_date = date('Y-m-d H:m:s', strtotime('-1 day'));
        $start_date = Carbon::parse($start_date);
        $end_date = Carbon::parse($end_date);
        $hour = $end_date->hour;

        $statistics = Statistic::getLastDayOrder($start_date, $end_date, 0);
        $hold =  Statistic::prepareLastDayNewsletter($statistics, $hour);

        $statistics = Statistic::getLastDayOrder($start_date, $end_date, 1);
        $confirmed =  Statistic::prepareLastDayNewsletter($statistics, $hour);

        $statistics = Statistic::getLastDayOrder($start_date, $end_date, 2);
        $rejected =  Statistic::prepareLastDayNewsletter($statistics, $hour);

        $statistics = Statistic::getLastDayOrder($start_date, $end_date, 3);
        $canceled =  Statistic::prepareLastDayNewsletter($statistics, $hour);

        $sum = Cart::whereBetween('created_at', [$start_date, $end_date])->count();
        $average = round($sum/24);
        $search = false; $order = true;
        return view('admin.statistics.lastDayCart', compact('rejected', 'confirmed', 'hold', 'canceled', 'newsletter', 'sum', 'average', 'search', 'start_date', 'end_date', 'order', 'hour'));
    }

    public function searchOrder(Requests\SearchNewsletterClicks $request){
        $start_date = $request->input('od');
        $end_date = $request->input('do');
        if(($start_date == '')){
            return redirect()->back()->with('error', 'Pogrešan opseg datuma!');
        }
        if(($end_date == '')){
            $end_date = Carbon::now();
        }
        $start_date = Carbon::parse($start_date);
        $end_date = Carbon::parse($end_date);

        \Session::set('search_od', $start_date);
        \Session::set('search_do', $end_date);

        $difference = $start_date->diffInDays($end_date);
        $month = $end_date->month;
        if($difference < 1){
            $differenceInHours = $start_date->diffInHours($end_date); $differenceInHours++;
            $hour = $end_date->hour;
            $statistics = Statistic::getLastDayOrder($start_date, $end_date, 0);
            $hold =  Statistic::prepareSearchDayNewsletter($statistics, 'h', $start_date, $end_date);

            $statistics = Statistic::getLastDayOrder($start_date, $end_date, 1);
            $confirmed =  Statistic::prepareSearchDayNewsletter($statistics, 'h', $start_date, $end_date);

            $statistics = Statistic::getLastDayOrder($start_date, $end_date, 2);
            $rejected =  Statistic::prepareSearchDayNewsletter($statistics, 'h', $start_date, $end_date);

            $statistics = Statistic::getLastDayOrder($start_date, $end_date, 3);
            $canceled =  Statistic::prepareSearchDayNewsletter($statistics, 'h', $start_date, $end_date);

            $sum = Cart::whereBetween('created_at', [$start_date, $end_date])->count();
            $average = round($sum/$differenceInHours);
            $search = true; $order = true;
            return view('admin.statistics.lastDaySearchCart', compact('rejected', 'confirmed', 'hold', 'canceled', 'newsletter', 'sum', 'average', 'search', 'start_date', 'end_date', 'order', 'hour'));
        }elseif($difference < 30){
            $differenceInDays = $start_date->diffInDays($end_date); $differenceInDays++;
            $month = $start_date->month;
            $statistics = Statistic::getLastMonthOrder($start_date, $end_date, 0);
            $hold =  Statistic::prepareSearchMonthNewsletter($statistics, 'd', $start_date, $end_date, $month);

            $statistics = Statistic::getLastMonthOrder($start_date, $end_date, 1);
            $confirmed =  Statistic::prepareSearchMonthNewsletter($statistics, 'd', $start_date, $end_date, $month);

            $statistics = Statistic::getLastMonthOrder($start_date, $end_date, 2);
            $rejected =  Statistic::prepareSearchMonthNewsletter($statistics, 'd', $start_date, $end_date, $month);

            $statistics = Statistic::getLastMonthOrder($start_date, $end_date, 3);
            $canceled =  Statistic::prepareSearchMonthNewsletter($statistics, 'd', $start_date, $end_date, $month);

            $sum = Cart::whereBetween('created_at', [$start_date, $end_date])->count();
            $days = Statistic::getDays($month);
            $average = round($sum/$differenceInDays);
            $search = true; $order = true;
            return view('admin.statistics.lastMonthSearchCart', compact('rejected', 'confirmed', 'hold', 'canceled', 'newsletter', 'sum', 'average', 'days', 'search', 'start_date', 'end_date', 'order'));
        }elseif($difference < 365){
            $differenceInMonts = $start_date->diffInMonths($end_date); $differenceInMonts++;
            $statistics = Statistic::getYearOrder($start_date, $end_date, 0);
            $hold =  Statistic::prepareSearchYearNewsletter($statistics, $start_date, $end_date);

            $statistics = Statistic::getYearOrder($start_date, $end_date, 1);
            $confirmed =  Statistic::prepareSearchYearNewsletter($statistics, $start_date, $end_date);

            $statistics = Statistic::getYearOrder($start_date, $end_date, 2);
            $rejected =  Statistic::prepareSearchYearNewsletter($statistics, $start_date, $end_date);

            $statistics = Statistic::getYearOrder($start_date, $end_date, 3);
            $canceled =  Statistic::prepareSearchYearNewsletter($statistics, $start_date, $end_date);

            $sum = Cart::whereBetween('created_at', [$start_date, $end_date])->count();
            $average = round($sum/$differenceInMonts);
            $search = true; $order = true;
            return view('admin.statistics.lastYearSearchCart', compact('rejected', 'confirmed', 'hold', 'canceled', 'newsletter', 'sum', 'average', 'search', 'start_date', 'end_date', 'order', 'month'));
        }else{
            $differenceInYears = $start_date->diffInYears($end_date); $differenceInYears++;
            $statistics = Statistic::getMoreYearOrder($start_date, $end_date, 0);
            $hold =  Statistic::prepareMoreYearNewsletter($statistics, $start_date, $end_date);

            $statistics = Statistic::getMoreYearOrder($start_date, $end_date, 1);
            $confirmed =  Statistic::prepareMoreYearNewsletter($statistics, $start_date, $end_date);

            $statistics = Statistic::getMoreYearOrder($start_date, $end_date, 2);
            $rejected =  Statistic::prepareMoreYearNewsletter($statistics, $start_date, $end_date);

            $statistics = Statistic::getMoreYearOrder($start_date, $end_date, 3);
            $canceled =  Statistic::prepareMoreYearNewsletter($statistics, $start_date, $end_date);

            $sum = Cart::whereBetween('created_at', [$start_date, $end_date])->count();
            $average = round($sum/$differenceInYears);
            $search = true; $order = true;
            return view('admin.statistics.lastYearMoreSearchCart', compact('rejected', 'confirmed', 'hold', 'canceled', 'newsletter', 'sum', 'average', 'search', 'start_date', 'end_date', 'order', 'month'));

        }
    }

    public function remove(){
        \Session::forget('search_od');
        \Session::forget('search_do');
        return redirect('admin/statistics/year');
    }

    public function removeNews($id){
        \Session::forget('search_od');
        \Session::forget('search_do');
        return redirect('admin/statistics/'.$id.'/yearNewsletter');
    }

}
