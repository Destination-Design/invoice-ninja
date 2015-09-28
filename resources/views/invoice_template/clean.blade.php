<html>
<head>
	<style>
		@page {
			size: 8.3in 11.7in;
			margin: 17mm;
		}
		html {
			font-size: 16px;
			line-height: 21px;
		}
		
		td.nospacing {
			border: none;
			padding: 0;
		}
		
		td {
			vertical-align: top;
		}
		
		.item-table-header {
			font-weight: bold;
		}
		
		.item-table-body tr:nth-child(odd) td {
			background-color: #eeeeee;
		}
		
		.primaryColor, .item-table-body .item-name {
			color: #299CC2;
		}
		
		.item-table-summary-wrapper {
			text-align: right;
		}
		
		.item-table-footer-top-spacing td {
			padding-top: 32px;
		}
	</style>
</head>
<body style="width: 800px;">

<htmlpagefooter name="contactDetails" style="display: none;">
<table>
	<tr>
		<td width="33%">
			Destination Design<br>
			c/o Alexander Koenig<br>
			Tulbeckstraße 2<br>
			80339 München<br>
			Web: www.destinationdesign.de<br>
			E-Mail: info@destinationdesign.de<br>
			Tel.: +49 89 74835624
		</td>
		<td width="33%">
			Bankverbindung:<br>
			Alexander Koenig<br>
			Sparkasse Bad Tölz-Wolfratshausen<br>
			Konto: 55559090<br>
			BLZ: 70054306<br>
			IBAN: DE91700543060055559090<br>
			BIC: BYLADEM1WOR
		</td>
		<td width="33%" style="vertical-align: top;">
			Steuernummer: 145/136/70568<br>
			UST-ID: DE258631021
		</td>
	</tr>
</table>
</htmlpagefooter>
<sethtmlpagefooter name="contactDetails" page="ALÖ" value="-1"></sethtmlpagefooter>

<table width="100%;">
	<tr>
		<td width="33%">
			<img src="/invoice-ninja/public/{{$rawInvoice->account()->getResults()->getLogoPath()}}" />
		</td>
		<td width="26%">
			<span class="primaryColor">{{$invoiceData['account']->name}}</span><br>
			{{$invoiceData['account']->id_number}}<br>
			{{$invoiceData['account']->vat_number}}<br>
			{{$invoiceData['account']->work_email}}<br>
			{{$invoiceData['account']->work_phone}}
		</td>
		<td width="40%">
			{{$invoiceData['account']->address1}}{{!empty($invoiceData['account']->address2) ? ', '.$invoiceData['account']->address2 : ''}}<br>
			{{$invoiceData['account']->city}}, {{$invoiceData['account']->state}} {{$invoiceData['account']->postal_code}}<br>
			{{$invoiceData['account']->country->name}}<br>
			{{$invoiceData['account']->custom_label1}} {{$invoiceData['account']->custom_value1}}<br>
			{{$invoiceData['account']->custom_label2}} {{$invoiceData['account']->custom_value2}}<br>
		</td>
</tr>
</table>
<br>
{{trans('texts.invoice')}}
<hr>
<table width="100%">
	<tr>
		<td width="33%">
			<table>
				<tr>
					<td>
						{{trans('texts.invoice_number')}}<br>
						{{trans('texts.po_number')}}<br>
						{{trans('texts.invoice_date')}}<br>
						{{trans('texts.due_date')}}<br>
						{{trans('texts.total')}}<br>
						{{trans('texts.balance_due')}}
					</td>
					<td>
						<b>{{$invoiceData['invoice_number']}}</b><br>
						{{$invoiceData['po_number']}}<br>
						{{$invoiceData['invoice_date']}}<br>
						{{$invoiceData['due_date']}}<br>
						{{Utils::curForm($invoiceData['amount'])}}<br>
						{{Utils::curForm($invoiceData['amount'] - $invoiceData['client']->paid_to_date)}}
					</td>
				</tr>
			</table>
		</td>
		<td>
			@if ($invoiceData['client']->name)
			<b>{{$invoiceData['client']->name}}</b><br>
			@else
			<b>{{$invoiceData['client']->contacts[0]->first_name}} {{$invoiceData['client']->contacts[0]->last_name}}</b><br>
			@endif
			{{$invoiceData['client']->id_number}}<br>
			{{$invoiceData['client']->address1}}{{!empty($invoiceData['client']->address2) ? ', '.$invoiceData['client']->address2 : ''}}<br>
			{{$invoiceData['client']->city}}, {{$invoiceData['client']->state}} {{$invoiceData['client']->postal_code}}<br>
			{{$invoiceData['client']->country->name}}<br>
			{{$invoiceData['client']->contacts[0]->email}}<br>
			{{$invoiceData['account']->custom_client_label1}} {{$invoiceData['client']->custom_value1}} <br>
			{{$invoiceData['account']->custom_client_label2}} {{$invoiceData['client']->custom_value2}} <br>
		</td>
	</tr>
</table>

<table class="items-table-wrapper" width="100%" style="font-size: 9pt; border-collapse: collapse; " cellpadding="8">
	<thead>
		<tr >
			<td class="item-table-header" align="left" width="15%">{{trans('texts.item')}}</td>
			<td class="item-table-header" align="left" width="40%">{{trans('texts.description')}}</td>
			<td class="item-table-header" align="right" width="20%">{{trans('texts.unit_cost')}}</td>
			@if ($invoiceData['account']->invoice_item_taxes !== 1)
			<td class="item-table-header" align="right" width="10%">{{trans('texts.quantity')}}</td>
			@else
			<td class="item-table-header" align="right" width="5%">{{trans('texts.quantity')}}</td>
			<td class="item-table-header" align="right" width="5%">{{trans('texts.tax')}}</td>
			@endif
			<td class="item-table-header" align="right" width="15%">{{trans('texts.line_total')}}</td>
		</tr>
	</thead>
	<tbody class="item-table-body">
		<?php $totalCounter = 0; $taxCounter = 0; ?>
		@foreach ($invoiceData['invoice_items'] as $item)
		<?php 
			$item->cost = is_numeric($item->cost) ? $item->cost : '0.00';
			$itemPrice = ($item->cost * $item->qty) + ((float)$item->tax_rate > 0 ? $item->cost * $item->qty * (float)$item->tax_rate / 100 : 0);
			$totalCounter += $itemPrice;
		?>
		<tr>
			<td align="left" class="item-name" align="">{{$item->product_key}}</td>
			<td align="left" >{{$item->notes}}</td>
			<td align="right" class="cost">{{Utils::curForm($item->cost)}}</td>
			<td align="right">{{number_format($item->qty, 0)}}</td>
			@if ($invoiceData['account']->invoice_item_taxes === 1)
			<td align="right">{{$item->tax_rate !== 0 ? number_format($item->tax_rate, 0).'%' : ''}}</td>
			@endif
			<td align="right" class="cost">{{Utils::curForm($itemPrice)}}</td>
		</tr>
		@endforeach
		<?php 
			$taxCounter += ($totalCounter - $invoiceData['discount']) * ($invoiceData['tax_rate'] / 100);
			$tfootColspan = $invoiceData['account']->invoice_item_taxes !== 1 ? 2 : 3;
		?>
		<tr class="item-table-footer-top-spacing">
			<td colspan="2" rowspan="8">
				{{$invoiceData['public_notes']}}<br><br>
				<b>{{trans('texts.terms')}}</b><br>
				{{$invoiceData['terms']}}
			</td>
			<td align="right">
				{{trans('texts.subtotal')}}
			</td>
			<td align="right" colspan="{{$tfootColspan}}">
				{{Utils::curForm($totalCounter)}}
			</td>
		</tr>
		@if (isset($invoiceData['discount']) && is_numeric($invoiceData['discount']))
		<tr>
			<td align="right">
				{{trans('texts.discount')}}
			</td>
			<td align="right" colspan="{{$tfootColspan}}">
				{{Utils::curForm($invoiceData['discount'])}}
			</td>
		</tr>
		@endif
		<tr>
			<td align="right">
				{{trans('texts.tax')}}: {{$invoiceData['tax_name']}} {{number_format($invoiceData['tax_rate'], 0)}}%
			</td>
			<td align="right" colspan="{{$tfootColspan}}">
				{{Utils::curForm($taxCounter)}}
			</td>
		</tr>
		<tr>
			<td align="right">
				{{$invoiceData['account']->custom_invoice_label1}}
			</td>
			<td align="right" colspan="{{$tfootColspan}}">
				{{Utils::curForm($invoiceData['custom_value1'])}}
			</td>
		</tr>
		<tr>
			<td align="right">
				{{$invoiceData['account']->custom_invoice_label2}}
			</td>
			<td align="right" colspan="{{$tfootColspan}}">
				{{Utils::curForm($invoiceData['custom_value2'])}}
			</td>
		</tr>
		<tr>
			<td align="right">
				{{trans('texts.total')}}
			</td>
			<td align="right" colspan="{{$tfootColspan}}">
				{{Utils::curForm($invoiceData['amount'])}}
			</td>
		</tr>
		<tr>
			<td align="right">
				{{trans('texts.paid_to_date')}}
			</td>
			<td align="right" colspan="{{$tfootColspan}}">
				{{Utils::curForm($invoiceData['client']->paid_to_date)}}
			</td>
		</tr>
		<tr class="item-table-footer-top-spacing">
			<td align="right">
				{{trans('texts.balance_due')}}
			</td>
			<td align="right" colspan="{{$tfootColspan}}">
				{{Utils::curForm($invoiceData['amount'] - $invoiceData['client']->paid_to_date)}}
			</td>
		</tr>
	</tbody>
</table>

<htmlpagefooter name="customFootertext" style="display: none;">
<table>
	<tr>
		<td>
			{{$invoiceData['wrapped_footer']}}
		</td>
	</tr>
</table>
</htmlpagefooter>
<sethtmlpagefooter name="customFootertext" page="ALL" value="on"></sethtmlpagefooter>
</body>
</html>