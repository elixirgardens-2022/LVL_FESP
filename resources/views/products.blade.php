<!-- Uses layouts.blade.php -->
@extends('layout')

<!-- Send page title to layout -->
@section('title')
Products View
@endsection

@section('content')

<link rel="stylesheet" href="/css/form_style.css">
<link rel="stylesheet" href="/css/modal.css">

<!-- Display sku search, export /import CSV and Add Product button in layout -->
@section('header_extras')
<form action="{{ route('products') }}" method="post" class="fl">
    @CSRF
    <input type="text" name="sku_search" placeholder="sku" autocomplete="off">
    <input type="submit" name="submit" value="Search" class="btn h30">
</form>

<a href="{{ route('exportCsv') }}" class="btn h30 lh30 ml30 fl" onclick="">Export CSV</a>

<form action="{{ route('importCsv') }}" method="post" enctype="multipart/form-data" class="ml30 fl">
    @CSRF
    <label class="curs-p"><input type="file" name="csv" style="display: none;">Select CSV</label>
    <input type="submit" value="Import" class="btn h30 ml10">
</form>

<a href="" class="btn h30 lh30 mr40 fr" data-id="none" onclick="return false;">Add Product</a>
@endsection

@if ('' != session('msg_error'))
<div class="msg msg-error">ERROR: {{ session('msg_error') }}</div>
@endif

@if ('' != session('msg_success'))
<div class="msg msg-success">SUCCESS: {{ session('msg_success') }}</div>
@endif

<!-- Products Table -->
<table class='tbl1'>
    <thead>
        <tr>
            <th>sku</th>
            <th>title</th>
            <th>weight</th>
            <th>length</th>
            <th>actions</th>
        </tr>
    </thead>
    <tbody>
    @foreach ($tbl_body as $row)
        <tr data-id="{{ $row['id'] }}">
            <td class='sku'>{{ $row['sku'] }}</td>
            <td class='title'>{{ $row['title'] }}</td>
            <td class='weight'>{{ $row['weight'] }}</td>
            <td class='length'>{{ $row['length'] }}</td>
            <td>
                <a href="" class="btn plr10 lh1-3" data-id="{{ $row['id'] }}" onclick="return false;">edit</a>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>




<!-- Modal Popup -->
@include('includes/modal', [
    'width'  => '600px',
    'fields' => [
        'sku'    => 'pattern="\S+" title="no white space allowed"',
        'title'  => '',
        'weight' => '',
        'length' => '',
    ],
])


<!-- jQuery CDN location: layout.blade.php -->
<script>
let ajax_url = '{{ route('ajax.modifyDb') }}'; // Used by 'ajax.js'
let fld_names = ['sku','title','weight','length']; // Used by 'ajax.js' and 'modal_add_edit.js'
let clean_urls = ['update_limit','goto']; // Used by 'clean_url.js'
</script>
<script src="/js/ajax.js"></script>
<script src="/js/modal_add_edit.js"></script>
<script src="/js/update_tr_cells.js"></script>
<script src="/js/clean_url.js"></script>
<script src="/js/tbl_sort.js"></script>
@endsection
