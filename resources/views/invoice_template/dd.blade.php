<html>
<head>
	<style>
		html, body {
			font-family: opensans;
			font-size: 16px;
			line-height: 21px;
		}
		
		.align-right {
			text-align: right;
		}
		
		.logo {
			height: auto;
			margin-bottom: 20px;
			max-width: 250px;
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
		
		.items-table-wrapper {
			page-break-inside: avoid;
		}
		
		.items-table-wrapper tr td {
			border: 1px solid black;
		}
		
		.items-table-tfoot-first-child {
			border: 1px solid black;
			border-top: 2px solid black;
		}
		
		.item-table-body {
		}
		
		.footnote {
			font-size: 12px;
		}
		
		.pagefooter {
			font-family: mono;
			font-size: 11px;
			width: 100%
		}
	</style>
</head>
<body style="width: 800px;">

<htmlpagefooter name="contactDetails" style="display: none;">
<table class="pagefooter">
	<tr>
		<td width="36%">
			Destination Design<br>
			c/o Alexander Koenig<br>
			Tulbeckstraße 2<br>
			80339 München<br>
			Web: www.destinationdesign.de<br>
			{{trans('texts.work_email')}}: info@destinationdesign.de<br>
			{{trans('texts.phone')}}: +49 89 74835624
		</td>
		<td width="36%">
			{{trans('texts.bank_account')}}:<br>
			Alexander Koenig<br>
			Sparkasse Bad Tölz-Wolfratshausen<br>
			{{trans('dd.account')}}: 55559090<br>
			{{trans('dd.routing_number')}}: 70054306<br>
			IBAN: DE91700543060055559090<br>
			BIC: BYLADEM1WOR
		</td>
		<td width="28%" style="vertical-align: top;">
			{{trans('dd.tax_id')}}: 145/136/70568<br>
			{{trans('texts.vat_number')}}: DE258631021<br>
			<br>
			<br>
			<br>
			<br>
			{{trans('dd.page')}}: {PAGENO} / {nbpg}
		</td>
	</tr>
</table>
</htmlpagefooter>
<sethtmlpagefooter name="contactDetails" page="ALL" value="on"></sethtmlpagefooter>

<table width="100%;">
	<tr>
		<td colspan="3" style="text-align: right;">
			<img class="logo" src="{{$rawInvoice['account']->getLogoURL()}}" />
		</td>
	</tr>
	<tr>
		<td width="62%">
			<span style="font-size: 0.7rem;">{{$invoiceData['account']->name}}, {{$invoiceData['account']->address1}}, {{$invoiceData['account']->postal_code}} {{$invoiceData['account']->city}}</span><br>
			<b>{{$invoiceData['client']->name}}</b><br>
			<b>z.H {{$invoiceData['client']->contacts[0]->first_name}} {{$invoiceData['client']->contacts[0]->last_name}}</b><br>
			{{$invoiceData['client']->address1}}<br>
			{{$invoiceData['client']->postal_code}} {{$invoiceData['client']->city}}<br>
			{{$invoiceData['client']->country()->getResults() ? $invoiceData['client']->country()->getResults()->name : ''}}<br>
		</td>
		<td width="19%">
			<b>{{trans('texts.invoice')}}</b><br>
			{{trans('texts.invoice_number')}}<br>
			{{trans('texts.invoice_date')}}<br>
			<?php echo !empty($invoiceData['invoice']['po_number']) ? trans('dd.order_number').'<br>' : '' ?>
			<?php echo !empty($invoiceData['client']->vat_number) ? trans('dd.your').' '.trans('texts.vat_number').'<br>' : '' ?>
			{{trans('dd.our')}} {{trans('texts.vat_number')}}<br>
			<?php echo !empty($invoiceData['invoice']->custom_text_value2) ? trans('dd.performance_period').'<br>' : '' ?>
		</td>
		<td class="align-right" width="19%">
			<br>
			{{$invoiceData['invoice']['invoice_number']}}<br>
			{{$invoiceData['invoice']['invoice_date']}}<br>
			<?php echo !empty($invoiceData['invoice']['po_number']) ? $invoiceData['invoice']['po_number'].'<br>' : '' ?>
			<?php echo !empty($invoiceData['client']->vat_number) ? $invoiceData['client']->vat_number.'<br>' : '' ?>
			{{$invoiceData['account']->vat_number}}<br>
			<?php echo !empty($invoiceData['invoice']->custom_text_value2) ? $invoiceData['invoice']->custom_text_value2.'<br>' : '' ?>
		</td>
	</tr>
</table>
<br>
<table class="items-table-wrapper" width="100%" style="font-size: 9pt; border-collapse: collapse; " cellpadding="8">
	<thead>
		<tr >
			<td class="item-table-header" align="left" width="10%">{{trans('dd.posistion_abbreviated')}}</td>
			<td class="item-table-header" align="left" width="15%">{{trans('dd.timeframe')}}</td>
			<td class="item-table-header" align="left" width="60%">{{trans('texts.details')}}</td>
			<td class="item-table-header" align="right" width="15%">{{trans('texts.amount')}}</td>
		</tr>
	</thead>
	<tbody class="item-table-body">
		<?php $totalAmount = 0; $taxAmount = 0; $discountAmount = 0; $counter = 1;?>
		@foreach ($invoiceData['invoice']['invoice_items'] as $item)
		<?php $item->cost = is_numeric($item->cost) ? $item->cost : '0.00'; ?>
		<tr>
			<td align="left" class="item-name" align="">{{$counter}}</td>
			<td>{{$item->custom_value1}}</td>
			<td align="left" >{{$item->notes}}</td>
			<td align="right" class="cost">{{Utils::curForm($item->cost*$item->qty)}}</td>
		</tr>
		<?php $totalAmount += $item->cost * $item->qty; $counter++; ?>
		@endforeach
		
		<?php
			if ($rawInvoice['invoice']->is_amount_discount) {
				$discountAmount = $invoiceData['invoice']['discount'];
				
				$totalAmount = $totalAmount - $invoiceData['invoice']['discount'];
			} else {
				$discountAmount = $totalAmount * (100 - $invoiceData['invoice']['discount']) / 100;
				$totalAmount = round($totalAmount - $discountAmount, 2);
			}
			
			$taxAmount = $totalAmount * ($invoiceData['invoice']['tax_rate1'] / 100);
		?>
		
	</tbody>
	<tfoot>
		@if ($invoiceData['invoice']['discount'] > 0.00)
		<tr class="items-table-tfoot-first-child">
			<td colspan="2" rowspan="3"></td>
			<td align="left">
				{{trans('texts.discount')}}
			</td>
			<td align="right">
				{{Utils::curForm($discountAmount)}}
			</td>
		</tr>
		@endif
		<tr @unless ($invoiceData['invoice']['discount'])class="items-table-tfoot-first-child"@endunless>
			@unless ($invoiceData['invoice']['discount'])<td colspan="2" rowspan="2"></td>@endunless
			<td align="left">
				{{trans('texts.subtotal')}}
			</td>
			<td align="right">
				{{Utils::curForm($totalAmount)}}
			</td>
		</tr>
		<tr>
			<td align="left">
				{{trans('texts.tax')}}: {{number_format($invoiceData['invoice']['tax_rate1'], 0)}}% <b>{{$invoiceData['invoice']['tax_name1']}}</b>
			</td>
			<td align="right">
				{{Utils::curForm($taxAmount)}}
			</td>
		</tr>
		<tr class="">
			<td colspan="2"></td>
			<td align="left">
				<b>{{trans('texts.balance_due')}}</b>
			</td>
			<td align="right">
				<b>{{Utils::curForm($invoiceData['invoice']['amount'])}}</b>
			</td>
		</tr>
	</tfoot>
</table>
<br>
<div>
	@if($invoiceData['invoice']['terms']) {{$invoiceData['invoice']['terms']}}<br>@endif
	{{trans('dd.invoice_payment_timeframe_note', array('date' => $invoiceData['invoice']['due_date']))}}<br>
	{{trans('dd.invoice_ending_note')}}<br>
	@if($invoiceData['invoice']['public_notes']) {{$invoiceData['invoice']['public_notes']}}<br>@endif
	<br>
	<br>
	{{trans('dd.kind_regards')}}<br>
	<br>
	Alexander Koenig<br>
	Destination Design
</div>

<htmlpagefooter name="customFootertext" style="display: none;">
<table class="pagefooter">
	@if (!empty($invoiceData['invoice']['invoice_footer']))
	<tr>
		<td colspan="3" class="footnote">
			{{$invoiceData['invoice']['invoice_footer']}}<br>&nbsp;<br>
		</td>
	</tr>
	@endif
	<tr>
		<td colspan="3" class="footnote">
			{{trans('dd.default_footer_note')}}<br>&nbsp;<br>
		</td>
	</tr>
	<tr>
		<td width="36%">
			Destination Design<br>
			c/o Alexander Koenig<br>
			Tulbeckstraße 2<br>
			80339 München<br>
			Web: www.destinationdesign.de<br>
			{{trans('texts.work_email')}}: info@destinationdesign.de<br>
			{{trans('texts.phone')}}: +49 89 74835624
		</td>
		<td width="36%">
			{{trans('texts.bank_account')}}:<br>
			Alexander Koenig<br>
			Sparkasse Bad Tölz-Wolfratshausen<br>
			{{trans('dd.account')}}: 55559090<br>
			{{trans('dd.routing_number')}}: 70054306<br>
			IBAN: DE91700543060055559090<br>
			BIC: BYLADEM1WOR
		</td>
		<td width="28%" style="vertical-align: top;">
			{{trans('dd.tax_id')}}: 145/136/70568<br>
			{{trans('texts.vat_number')}}: DE258631021<br>
			<br>
			<br>
			<br>
			<br>
			{{trans('dd.page')}}: {PAGENO} / {nbpg}
		</td>
	</tr>
</table>
</table>
</htmlpagefooter>
<sethtmlpagefooter name="customFootertext" page="ALL" value="on"></sethtmlpagefooter>
</body>
</html>