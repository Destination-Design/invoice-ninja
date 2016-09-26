<html>
<head>
	<style>
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
		
		.items-table-wrapper tr td {
			border: 1px solid black;
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
<sethtmlpagefooter name="contactDetails" page="ALL" value="on"></sethtmlpagefooter>

<table width="100%;">
	<tr>
		<td colspan="3" style="text-align: right;">
			<img src="/invoice-ninja/public/{{$rawInvoice['account']->logo}}" />
		</td>
	</tr>
	<tr>
		<td width="66%">
			<span style="font-size: 0.7rem;">{{$invoiceData['account']->name}}, {{$invoiceData['account']->address1}}, {{$invoiceData['account']->postal_code}} {{$invoiceData['account']->city}}</span><br>
			<b>{{$invoiceData['client']->name}}</b><br>
			<b>z.H {{$invoiceData['client']->contacts[0]->first_name}} {{$invoiceData['client']->contacts[0]->last_name}}</b><br>
			{{$invoiceData['client']->address1}}<br>
			{{$invoiceData['client']->postal_code}}, {{$invoiceData['client']->city}}<br>
			invoiceData['client']->country()<br>
		</td>
		<td width="17%">
			<b>{{trans('texts.invoice')}}</b><br>
			{{trans('texts.invoice_number')}}<br>
			{{trans('texts.invoice_date')}}<br>
			Ihre Ust-ID-Nr<br>
			Unsere Ust-ID-Nr<br>
		</td>
		<td width="17%">
			<br>
			{{$invoiceData['invoice']['invoice_number']}}<br>
			{{$invoiceData['invoice']['invoice_date']}}<br>
			{{$invoiceData['client']->vat_number}}<br>
			{{$invoiceData['account']->vat_number}}<br>
		</td>
	</tr>
</table>
<br>
<table class="items-table-wrapper" width="100%" style="font-size: 9pt; border-collapse: collapse; " cellpadding="8">
	<thead>
		<tr >
			<td class="item-table-header" align="left" width="10%">Pos.</td>
			<td class="item-table-header" align="left" width="15%">Zeitraum</td>
			<td class="item-table-header" align="left" width="60%">Details</td>
			<td class="item-table-header" align="right" width="15%">Betrag</td>
		</tr>
	</thead>
	<tbody class="item-table-body">
		<?php $totalCounter = 0; $taxCounter = 0; $counter = 1;?>
		@foreach ($invoiceData['invoice']['invoice_items'] as $item)
		<?php $item->cost = is_numeric($item->cost) ? $item->cost : '0.00'; ?>
		<tr>
			<td align="left" class="item-name" align="">{{$counter}}</td>
			<td>{{$item->product_key}}</td>
			<td align="left" >{{$item->notes}}</td>
			<td align="right" class="cost">{{Utils::curForm($item->cost*$item->qty)}}</td>
		</tr>
		<?php $totalCounter += $item->cost * $item->qty; $counter++; ?>
		@endforeach
		
		<?php $taxCounter += ($totalCounter - $invoiceData['invoice']['discount']) * ($invoiceData['invoice']['tax_rate1'] / 100); ?>
		
	</tbody>
	<tfoot>
		<tr class="">
			<td colspan="2" rowspan="2"></td>
			<td align="left">
				{{trans('texts.subtotal')}}
			</td>
			<td align="right">
				{{Utils::curForm($totalCounter)}}
			</td>
		</tr>
		<tr>
			<td align="left">
				{{trans('texts.tax')}}: {{$invoiceData['invoice']['tax_name1']}} {{number_format($invoiceData['invoice']['tax_rate1'], 0)}}%
			</td>
			<td align="right">
				{{Utils::curForm($taxCounter)}}
			</td>
		</tr>
		<tr class="">
			<td colspan="2"></td>
			<td align="left">
				<b>{{trans('texts.balance_due')}}</b>
			</td>
			<td align="right">
				{{Utils::curForm($invoiceData['invoice']['amount'])}}
			</td>
		</tr>
	</tfoot>
</table>
<br>
<div>
	Bitte überweisen Sie den Betrag innerhalb von 14 Tagen auf unten stehende Bankverbindung.
	Wir danken Ihnen für Ihren Auftrag und freuen uns über eine weitere gute Zusammenarbeit.
	Mit freundlichen Grüßen,
	<br>
	<br>
	<br>
	<br>
	Alexander Koenig<br>
	Destination Design
</div>
<htmlpagefooter name="customFootertext" style="display: none;">
<table>
	<tr>
		<td colspan="3">
			Sofern nicht anders vermerkt sind alle Preise Nettopreise zzgl. der gesetzlichen MwSt. Die Leistungen und 
gelieferten Waren bleiben einschließlich aller Vervielfältigungs- und Nutzungsrechte bis zur vollständigen 
Bezahlung unser Eigentum. Es gelten unsere AGB.
		</td>
	</tr>
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
</table>
</htmlpagefooter>
<sethtmlpagefooter name="customFootertext" page="ALL" value="on"></sethtmlpagefooter>
</body>
</html>