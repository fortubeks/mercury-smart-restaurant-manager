<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <title>Print</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="all,follow">

    <style type="text/css">
        * {
            font-size: 10px;
            line-height: 11px;
            font-family: 'Ubuntu', sans-serif;
            text-__form: capitalize;
        }

        .btn {
            padding: 7px 10px;
            text-decoration: none;
            border: none;
            display: block;
            text-align: center;
            margin: 7px;
            cursor: pointer;
        }

        .btn-info {
            background-color: #999;
            color: #FFF;
        }

        .btn-primary {
            background-color: #6449e7;
            color: #FFF;
            width: 100%;
        }

        td,
        th,
        tr,
        table {
            border-collapse: collapse;
        }

        tr {
            border-bottom: 1px dotted #ddd;
        }

        td,
        th {
            padding: 7px 0;
        }

        table {
            width: 100%;
        }

        tfoot tr th:first-child {
            text-align: left;
        }

        .centered {
            text-align: center;
            align-content: center;
        }

        small {
            font-size: 10px;
        }

        @media print {
            * {
                font-size: 11px;
                line-height: 20px;
            }

            td,
            th {
                padding: 5px 0;
            }

            .hidden-print {
                display: none !important;
            }

            @page {
                margin: 1.0cm 0.5cm 0.5cm;
            }

        }
    </style>
</head>

<body>

    <div style="max-width:400px;margin:0 auto">
        @if(preg_match('~[0-9]~', url()->previous()))
        @php $url = url()->previous(); @endphp
        @else
        @php $url = url()->previous(); @endphp
        @endif
        <div class="hidden-print">
            <table>
                <tr>
                    <td><a href="{{$url}}" class="btn btn-info"><i class="fa fa-arrow-left"></i> {{__('Back')}}</a> </td>
                    <td><button onclick="window.print();" class="btn btn-primary"><i class="dripicons-print"></i> {{__('Print')}}</button></td>
                </tr>
            </table>
            <br>
        </div>

        <div id="receipt-data">
            <div class="centered">
                <h2>{{__(restaurant()->name)}}</h2>
            </div>
            <p>{{__('Date')}}: {{now()->format("d-M-Y h:i:s A")}}<br>
                {{ __('Customer') }}: {{$customerName}}<br>
                {{__('Table')}}:<br>
                {{ __('Docket') }}:
            </p>

            <div class="centered">
                <table class="table table-data">
                    <tbody>
                        @foreach($orderDetails['items'] as $item)

                        <tr>
                            <td style="text-align:left">{{__($item['name'])}}</td>
                            <td>{{$item['quantity']}}</td>
                            <td style="text-align:right;vertical-align:bottom"></td>
                        </tr>
                        @endforeach

                        <tr>
                            <td colspan="2"></td>
                            <td></td>
                        </tr>

                        <tr style="background-color:#ddd;">
                            <td class="centered" colspan="3">{{__('Served By')}}: {{__(auth()->user()->name)}}</td>
                        </tr>

                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        //localStorage.clear();
        function auto_print() {
            window.print()
        }
        setTimeout(auto_print, 1000);
    </script>

</body>

</html>