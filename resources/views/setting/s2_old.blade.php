@extends('admin.layout.index')
@section('content')
    <div class="page-content">
        <h1 class="page-title"> {{$title}}
            <small>{{$page_title}}</small>
        </h1>
        <div class="page-bar">
            @include('admin.layout.breadcrumb')

        </div>
        <div class="portlet box green">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-gift"></i>{{$title}}</div>
                <div class="tools">
                    <a href="javascript:;" class="collapse"> </a>

                </div>
            </div>
            <div class="portlet-body form">
                <!-- BEGIN FORM-->
                {{--  <form action="{{url('admin/user/'.$user_id)}}" class="form-horizontal" method="post">--}}
                {{Form::open(['url'=>url('setting/save'),'class'=>'form-horizontal','method'=>"post"])}}

                <div class="form-body">
                    <div class="form-group">
                        <label class="col-md-3 control-label">الوصف</label>
                        <div class="col-md-4">
                            <div class="input-icon">
                                <i class="icon-user"></i>
                                <input name="address" type="text" class="form-control "
                                       placeholder="الوصف" value=""></div>
                        </div>
                    </div>
                </div>
                <div class="form-actions left">
                    <div class="row">
                        <div class=" col-md-9">
                            <button type="submit" class="btn green">حفظ</button>
                            <a href="{{url('/setting')}}" class="btn  grey-salsa btn-outline">عودة</a>
                        </div>
                    </div>
                </div>

            {{-- </form>--}}
            {{Form::close()}}
            <!-- END FORM-->
            </div>
        </div>

        <div class="portlet box yellow-casablanca">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-gift"></i>جدول القيم
                </div>
                <div class="tools">
                    <a href="javascript:;" class="collapse"> </a>

                </div>
            </div>
            <div class="portlet-body form">
                <div class="form-body">
                    <table class="table table-striped table-bordered table-hover  order-column" id="setting_tbl">
                        <thead>
                        <tr>

                            <th> كود </th>
                            <th> الوصف </th>
                            <th>تحكم</th>

                        </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>

    </div>
    @push('css')
        <link href="{{url('')}}/assets/global/plugins/select2/css/select2.min.css" rel="stylesheet" type="text/css"/>
        <link href="{{url('')}}/assets/global/plugins/select2/css/select2-bootstrap.min.css" rel="stylesheet"
              type="text/css"/>
    @endpush
    @push('js')
        <script src="{{url('')}}/assets/global/plugins/select2/js/select2.full.min.js" type="text/javascript"></script>
        <script src="{{url('')}}/assets/pages/scripts/components-select2.min.js" type="text/javascript"></script>
   <script>
       $(document).ready(function () {
           $('#setting_tbl').dataTable({

               'processing': true,
               'serverSide': true,
               'ajax': '{{url('/s2-data')}}',
               'columns': [

                   {data: 'id', name: 'id'},
                   {data: 'desc', name: 'desc'},
                   {data: 'action', name: 'action'}
               ],
               "language": {
                   "aria": {
                       "sortAscending": ": activate to sort column ascending",
                       "sortDescending": ": activate to sort column descending"
                   },
                   "emptyTable": "لايوجد بيانات في الجدول للعرض",
                   "info": "عرض _START_ الى  _END_ من _TOTAL_ سجلات",
                   "infoEmpty": "No records found",
                   "infoFiltered": "(filtered1 from _MAX_ total records)",
                   "lengthMenu": "عرض _MENU_",
                   "search": "بحث:",
                   "zeroRecords": "No matching records found",
                   "paginate": {
                       "previous":"Prev",
                       "next": "Next",
                       "last": "Last",
                       "first": "First"
                   }
               },

           })
       })
   </script>
    @endpush
@stop