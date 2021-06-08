@extends('layouts.app')

@section('content')

<!-- Categories -->
<div class="container pb-5 pt-5">
	<div class="row">
		@if(!empty($parent_category))
		<div class="col-1">
			<a href="/categories"><h1><</h1></a>
		</div>
		@endif
		<div class="col">
			<h1>CATEGORIES @if(!empty($parent_category)) - {{ \App\Models\Category::where('id', $parent_category)->first()->name }} @endif</h1>
		</div>
	</div>

	<hr />

	<div style="min-height: 200px;">
		<div class="inner-section">
			<div class="container" id="categories">
				@php
					$first = true;
				@endphp

				@foreach($categories as $category)
					<div class="row @if(!$first) pt-4 @endif" id="category-{{ $category->id }}">
						<div class="col-11">
							@if(empty($parent_category))
								<a href="/categories?id={{ $category->id }}"><h3>{{ $category->name }}</h3></a>
							@else
								<h3>{{ $category->name }}</h3>
							@endif
						</div>
						<div class="col-1">
							<a href="#" onclick="javascript:deleteCategory('{{ $category->id }}')"><h2><i class="fas fa-times"></i></h2></a>
						</div>
					</div>

					@if($first)
						@php
							$first = false;
						@endphp
					@endif
				@endforeach
			</div>
		</div>
	</div>

	<hr />
	<div class="row pt-3">
		<div class="col-8">
			<input style="vertical-align: text-bottom;" id="name" type="text" class="input-field w-100" name="name" autocomplete="name" placeholder="Name">
			<small style="color: red;" id="error"></small>
		</div>
		<div class="col-4">
			<button type="button" class="btn-clean" onclick="createCategory()">Create</button>
		</div>
	</div>

</div>
<!-- end Categories -->

@if(!empty($parent_category)) 
<!-- Sizes -->
<div class="container pb-5 pt-5">
	<div class="row">
		<div class="col">
			<h1>SIZES</h1>
		</div>
	</div>

	<hr />

	<div style="min-height: 200px;">
		<div class="inner-section">
			<div class="container" id="sizes">
				@php
					$first1 = true;
				@endphp

				@foreach(\App\Models\Size::where('category', $parent_category)->get() as $size)
					<div class="row @if(!$first1) pt-4 @endif" id="size-{{ $size->id }}">
						<div class="col-11">
							<h3>{{ $size->name }}</h3>
						</div>
						<div class="col-1">
							<a href="#" onclick="javascript:deleteSize('{{ $size->id }}')"><h2><i class="fas fa-times"></i></h2></a>
						</div>
					</div>

					@if($first1)
						@php
							$first1 = false;
						@endphp
					@endif
				@endforeach
			</div>
		</div>
	</div>

	<hr />

	<div class="row pt-3">
		<div class="col-8">
			<input style="vertical-align: text-bottom;" id="size" type="text" class="input-field w-100" name="size" autocomplete="size" placeholder="Size">
			<small style="color: red;" id="error-size"></small>
		</div>
		<div class="col-4">
			<button type="button" class="btn-clean" onclick="createSize()">Create</button>
		</div>
	</div>

</div>
<!-- end Sizes -->
@endif
@endsection

@section('foot')
<script type="text/javascript">
function createCategory() {
	var name = $('#name').val();
	var empty = '{{ sizeof($categories) < 1 }}';
	var has_parent = '{{ !empty($parent_category) }}';

	$.ajax({
		url: '/create-category',
		type: 'POST',
		data: {
			'name': name,
			'parent_category': '{{ $parent_category }}',
			_token: '{{ csrf_token() }}'
		},
	}).done(function (msg) {
		if (msg['success']) {
			$('#name').val('');

			var id = msg['msg'];
			var html = ``;

			if(empty) {
				html += `<div class="row" id="category-${id}"><div class="col-11">`;
			} else {
				html += `<div class="row pt-4" id="category-${id}"><div class="col-11">`;
			}

			if(has_parent) {
				html += `<h3>${name}</h3></div>`;
			} else {
				html += `<a href="/categories?id=${id}"><h3>${name}</h3></a></div>`;
			}

			html += `
				<div class="col-1">
					<a href="#" onclick="javascript:deleteCategory('${id}')"><h2><i class="fas fa-times"></i></h2></a>
				</div>
			`;

			$('#categories').append(html);
			$('#error').empty();
		} else {
			$('#error').text(msg['msg']);
		}
	});
}

function deleteCategory(id) {
	var has_parent = '{{ !empty($parent_category) }}';
	$.ajax({
		url: '/delete-category',
		type: 'POST',
		data: {
			'id': id,
			_token: '{{ csrf_token() }}'
		},
	}).done(function (msg) {
		if (msg['success']) {
			$('#category-' + id).remove();
		} else {
			$('#error').text(msg['msg']);
		}
	});
}

</script>

@if(!empty($parent_category))
<script type="text/javascript">
function createSize() {
	var name = $('#size').val();
	var empty = "{{ sizeof(\App\Models\Size::where('category', $parent_category)->get()) < 1 }}";

	$.ajax({
		url: '/create-size',
		type: 'POST',
		data: {
			'name': name,
			'category': '{{ $parent_category }}',
			_token: '{{ csrf_token() }}'
		},
	}).done(function (msg) {
		if (msg['success']) {
			$('#size').val('');

			var id = msg['msg'];
			var html = ``;

			if(empty) {
				html += `<div class="row" id="size-${id}"><div class="col-11">`;
			} else {
				html += `<div class="row pt-4" id="size-${id}"><div class="col-11">`;
			}

			html += `<h3>${name}</h3></div>`;

			html += `
				<div class="col-1">
					<a href="#" onclick="javascript:deleteSize('${id}')"><h2><i class="fas fa-times"></i></h2></a>
				</div>
			`;

			$('#sizes').append(html);
			$('#error-size').empty();
		} else {
			$('#error-size').text(msg['msg']);
		}
	});
}

function deleteSize(id) {
	$.ajax({
		url: '/delete-size',
		type: 'POST',
		data: {
			'id': id,
			_token: '{{ csrf_token() }}'
		},
	}).done(function (msg) {
		if (msg['success']) {
			$('#size-' + id).remove();
		} else {
			$('#error-size').text(msg['msg']);
		}
	});
}
</script>
@endif
@endsection
