<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ __('Sale') }} _{{ $sale->reference }}</title>
    <link rel="stylesheet" href="{{ asset('/print/pdfStyle.css') }}" media="all" />
</head>

<body>
    <header class="clearfix">
        <div id="logo">
            <img src="{{ asset('images/logo.png') }}">
        </div>
        <div id="company">
            <div><strong> {{ __('Date') }}: </strong>{{ $sale->date }}</div>
            <div><strong> {{ __('Number') }}: </strong> {{ $sale->reference }}</div>
        </div>
        <div id="Title-heading">
            {{ __('Sale') }} : {{ $sale->reference }}
        </div>
        </div>
    </header>
    <main>
        <div id="details" class="clearfix">
            <div id="customer">
                <table class="table-sm">
                    <thead>
                        <tr>
                            <th class="desc">{{ __('Customer Info') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <div><strong>{{ __('Name') }}:</strong> {{ $sale->customer->name }}</div>
                                <div><strong>{{ __('Tax number') }}:</strong> {{ $sale->customer?->tax_number }}</div>
                                <div><strong>{{ __('Phone') }}:</strong> {{ $sale->customer->phone }}</div>
                                <div><strong>{{ __('Address') }}:</strong> {{ $sale->customer->address }}</div>
                                <div><strong>{{ __('Email') }}:</strong> {{ $sale->customer->email }}</div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div id="invoice">
                <table class="table-sm">
                    <thead>
                        <tr>
                            <th class="desc">{{ __('Company Info') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <div id="comp">{{ settings()->company_name }}</div>
                                <div><strong>{{ __('ICE') }}:</strong> {{ $setting['CompanyTaxNumber'] }}</div>
                                <div><strong>{{ __('Address') }}:</strong> {{ settings()->company_address }}</div>
                                <div><strong>{{ __('Phone') }}:</strong> {{ settings()->company_phone }}</div>
                                <div><strong>{{ __('Email') }}:</strong> {{ settings()->company_email }}</div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div id="details_inv">
            <table class="table-sm">
                <thead>
                    <tr>
                        <th>{{ __('EPRODUCT') }}</th>
                        <th>{{ __('Unit Price') }}</th>
                        <th>{{ __('QTY') }}</th>
                        <th>{{ __('TOTAL') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($sale->saleDetails as $detail)
                        <tr>
                            <td>
                                <span>{{ $detail->code }} ({{ $detail->name }})</span>
                            </td>
                            <td>{{ $detail->unit_price }} </td>
                            <td>{{ $detail->quantity }}
                                {{-- /{{ $detail['unitSale'] }} --}}
                            </td>
                            <td>{{ $detail->sub_total }} </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div id="total">
            <table>
                <tr>
                    <td>{{ __('Tax') }}</td>
                    <td>{{ $sale->tax_amount }} </td>
                </tr>
                <tr>
                    <td>{{ __('Discount') }}</td>
                    <td>{{ $sale->discount_amount }} </td>
                </tr>
                <tr>
                    <td>{{ __('Livraison') }}</td>
                    <td>{{ $sale->shipping_amount }} </td>
                </tr>
                <tr>
                    <td>{{ __('Total') }}</td>
                    <td>{{ $symbol }} {{ $sale->total_amount }} </td>
                </tr>

                <tr>
                    <td>{{__('Paid Amount')}}</td>
                    <td>{{ $symbol }} {{ $sale->paid_amount }} </td>
                </tr>

                <tr>
                    <td>{{__('Due Amount')}}</td>
                    <td>{{ $symbol }} {{ $sale->due_amount }} </td>
                </tr>
            </table>
        </div>
        <div id="signature">
            {{-- @if ($setting['is_invoice_footer'] && $setting['invoice_footer'] !== null)
                <p>{{ $setting['invoice_footer'] }}</p>
            @endif --}}
        </div>
    </main>
</body>

</html>
