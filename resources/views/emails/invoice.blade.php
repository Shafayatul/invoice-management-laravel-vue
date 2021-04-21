@component('mail::message')
# Introduction

The body of your message.

Name: {{ $invoice->client->name }}
Email: {{ $invoice->client->email }}
Phone: {{ $invoice->client->phone }}
Company: {{ $invoice->client->company->name }}

Invoice No:{{ $invoice->id }}
Amount: {{ '$'.$invoice->invoiceHistory->amount }}
Paid Status: {{ ($invoice->invoiceHistory->is_paid == 0) ? 'Non Paid' : 'Paid' }}

Thanks,<br>
{{ config('app.name') }}
@endcomponent
