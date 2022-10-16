@extends('theme.default')

@section('content')

<div class="row page-titles mx-0">
    <div class="col p-md-0">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{URL::to('/admin/home')}}">Dashboard</a></li>
            <li class="breadcrumb-item active"><a href="javascript:void(0)">Inquiries</a></li>
        </ol>
    </div>
</div>
<!-- row -->

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <span id="message"></span>
            <div class="card">
                <div class="card-body" id="tab">
                    <h4 class="card-title">All Inquiries: 
                        <input class="btn btn-success" type="button" value="Print" onclick="myApp.printTable()" />
                    </h4>
                    <div class="table-responsive" id="table-display">
                        <table class="table table-striped table-bordered zero-configuration">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Message</th>
                                    <th>Created at</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $i=1;
                                foreach ($getcontact as $contact) {
                                ?>
                                <tr id="dataid{{$contact->id}}">
                                    <td>{{$i}}</td>
                                    <td>{{$contact->firstname}} {{$contact->lastname}}</td>
                                    <td>{{$contact->email}}</td>
                                    <td>{{$contact->message}}</td>
                                    <td>{{$contact->created_at}}</td>
                                </tr>
                                <?php
                                $i++;
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- #/ container -->
@endsection
@section('script')

<script type="text/javascript">
    var myApp = new function () {
        var style = '<link href="../public/assets/css/style.css" rel="stylesheet">';
        var style = "<style>";
        style = style + "table {width: 100%; font: 17px Calibri;}";
        style = style + "table, th, td {border: solid 1px #DDD; border-collapse: collapse;";
        style = style + "padding: 2px 3px; text-align: center;}";
        style = style + ".dataTables_length {display: none;}";
        style = style + ".dataTables_filter {display: none;}";
        style = style + "input {display: none;}";
        style = style + ".pagination {display: none;}";
        style = style + ".card-title {font: 27px Calibri;}";
        style = style + "</style>";

        this.printTable = function () {
            var tab = document.getElementById('tab');
            var win = window.open('', '', 'height=700,width=900');
            win.document.write(style);
            win.document.write(tab.outerHTML);
            win.document.close();
            win.print();
        }
    }
</script>

<script type="text/javascript">
    $('.table').dataTable({
      aaSorting: [[0, 'DESC']]
    });
</script>
@endsection

