<!doctype html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>

    <title>Document</title>

    <link rel="stylesheet" media="screen" href="{{url('assets/xb-riyaz.css')}}" type="text/css"/>
    {{--<link href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet" type="text/css" />--}}
    <link href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet"
          type="text/css"/>
    <style>
        body, th, td {
            font-family: 'xbriyaz', sans-serif;

        }

        th, td {
            font-size: 13;

        }

        td {
            font-size: 12;

        }

        .table-title {
            border: 0;
        }

        .ttable, th, td {

            border: 1px solid grey;

        }


        .table {

            border-collapse: collapse;

        }

        .column {
            float: left;
            width: 50%;
        }

        /* Clear floats after the columns */
        .row:after {
            content: "";
            display: table;
            clear: both;
        }

        hr {
            display: block;
            height: 1px;
            border: 0;
            border-top: 1px solid #ccc;
            margin: 1em 0;
            padding: 0;

        }

        @page {
            header: html_otherpages;
            footer: page-footer;
        }

        @page :first {
            header: page-header;
            footer: page-footer;
        }
    </style>
</head>
<body>

<htmlpageheader name="page-header">
<!--   <div><img src="{{url('')}}/assets/pages/img/norwac.jpg" alt="logo"
              width="40%"/></div>-->
    <br/>
</htmlpageheader>
<htmlpageheader name="otherpages" style="display:none">
    <div style="text-align:center">{PAGENO}</div>
</htmlpageheader>
<br/>
<br/>

<div style="width: 100%; text-align: center">
    <h1 class="page-title">Patient Statistic Monthly</h1>
    <h2>&nbsp;Date&nbsp;{{(isset($fromdate))?$fromdate:''}}&nbsp;To&nbsp;{{(isset($todate))?$todate:''}}
        &nbsp;</h2>
</div>
<br/>

<div class="page-content">
    <div class="row">
        <div class="col-md-6">
            <table class="table  " width="100%" id="report_tbl" cellpadding="10">
                <thead>
                <tr>
                    <th style="background-color:#9cc2cb;vertical-align:bottom" colspan="3">New Patients</th>
                </tr>
                <tr>
                    <th width="10%">#</th>
                    <th width="30%">Name</th>
                    <th width="15%">Date</th>
                </tr>
                </thead>
                <tbody>
                {!! $newPatients; !!}

                </tbody>

            </table>
        </div>
    </div>
    <br/>
    <div class="row">
        <div class="col-md-6">
            <table class="table  " width="100%" id="report_tbl" cellpadding="10">
                <thead>
                <tr>
                    <th style="background-color:#9cc2cb;vertical-align:bottom" colspan="3">Follow Up Patients</th>
                </tr>
                <tr>
                    <th width="10%">#</th>
                    <th width="30%">Name</th>
                    <th width="15%">Date</th>
                </tr>
                </thead>
                <tbody>
                {!! $followupPatients; !!}

                </tbody>

            </table>
        </div>
    </div>
    <br/>
    <div class="row">
        <div class="col-md-6">
            <table class="table  " width="100%" id="report_tbl" cellpadding="10">
                <thead>
                <tr>
                    <th style="background-color:#9cc2cb;vertical-align:bottom" colspan="3">Last Follow Up Patients</th>
                </tr>
                <tr>
                    <th width="10%">#</th>
                    <th width="30%">Name</th>
                    <th width="15%">Date</th>
                </tr>
                </thead>
                <tbody>
                {!! $lastFollowupPatients; !!}

                </tbody>

            </table>
        </div>
    </div>
    <br/>
    <div class="row">
        <div class="col-md-6">
            <table class="table  " width="100%" id="report_tbl" cellpadding="10">
                <thead>
                <tr>
                    <th style="background-color:#9cc2cb;vertical-align:bottom" colspan="3">Absent Patients</th>
                </tr>
                <tr>
                    <th width="10%">#</th>
                    <th width="30%">Name</th>
                    <th width="15%">Date</th>
                </tr>
                </thead>
                <tbody>
                {!! $absentPatients; !!}

                </tbody>

            </table>
        </div>
    </div>
</div>


<htmlpagefooter name="page-footer">
    <div class="row">

        <div class="column">
            Page {nb}/{PAGENO}
        </div>
        <div class="column">
            Print date: {{ date('d-m-Y') }}
        </div>

    </div>

</htmlpagefooter>
</body>
</html>
