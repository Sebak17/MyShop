@extends('admin.app')

@section('content_sub')

<div class="container-fluid">
	
	<div class="d-sm-flex align-items-center justify-content-between mb-4">
		<h1 class="h3 mb-0 text-gray-800">Kategorie</h1>
	</div>
	
	<div class="row">
		
		<div class="col-12">
			<div class="alert text-center d-none" id="alertMain"></div>
		</div>
		
		
		<div class="col-12 col-sm-6 mb-3">
			<div class="card card-body">
				<legend><i class="fas fa-list-alt"></i> Lista kategorii</legend>
				<hr />
				
				<div class="tree">
					<ul>
						<li><span data-categoryID="0"><strong>#</strong></span>
						<div>
							<ul id="categoriesList">
							</ul>
						</div>
					</li>
				</ul>
				
			</div>
			
		</div>
	</div>
	
	<div class="col-12 col-sm-6">
		<div class="card card-body mb-3">
			<legend><i class="fas fa-info"></i> Informacje o kategorii</legend>
			<hr />

			<div>

				<table class="w-75">
					<tbody>
						<tr>
							<td>ID:</td>
							<td><strong id="_currentCatID">?</strong></td>
						</tr>
						<tr>
							<td>Nazwa:</td>
							<td><strong id="_currentCatName">?</strong></td>
						</tr>
						<tr>
							<td>Widoczny:</td>
							<td><strong id="_currentCatVisible">?</strong></td>
						</tr>
						<tr>
							<td>Aktywny:</td>
							<td><strong id="_currentCatActive">?</strong></td>
						</tr>
					</tbody>
				</table>
			</div>

			<hr />
			<h6 class="text-center mb-3"><i class="fas fa-wrench"></i> Opcje kategorii</h6>

			<div class="alert d-none" id="alert03"></div>
			
			<div class="form-group text-center">
				<button type="button" id="btnChangeOrder" class="btn btn-info">Zmień kolejność <i class="fas fa-list"></i></button>
				<button type="button" id="btnEditModal" class="btn btn-primary">Edytuj <i class="fas fa-edit"></i></button>
				<button type="button" id="btnRemove" class="btn btn-danger">Usuń <i class="fas fa-times-circle"></i></button>
			</div>
			
		</div>
		<div class="card card-body mb-3">
			<legend><i class="fas fa-plus"></i> Dodaj kategorię</legend>
			<hr />
			
			<div class="alert d-none" id="alert01"></div>
			
			<div class="form-group">
				<label for="fmAddName">Podaj nazwę kategorii:</label>
				<input id="fmAddName" type="text" class="form-control">
			</div>
			
			<div class="form-group">
				<label for="fmAddIcon">Podaj nazwę ikony:</label>
				<input id="fmAddIcon" type="text" class="form-control">
			</div>
			
			<div class="form-group">
				<label for="fmAddList">Wybierz kategorie nadrzędną:</label>
				<select id="fmAddList" class="custom-select">
				</select>
			</div>
			
			<div class="form-group">
				<button type="button" id="btnAdd" class="btn btn-primary float-right">Dodaj <i class="fas fa-plus-circle"></i></button>
			</div>
			
		</div>
		
		<div class="card card-body mb-3">
			<legend><i class="fas fa-edit"></i> Edytuj kategorię</legend>
			<hr />
			
			<div class="alert d-none" id="alert02"></div>
			
			<div class="form-group">
				<label for="fmEditName">Nazwę kategorii:</label>
				<input id="fmEditName" type="text" class="form-control">
			</div>
			
			<div class="form-group">
				<label for="fmEditIcon">Ikona kategorii:</label>
				<input id="fmEditIcon" type="text" class="form-control">
			</div>
			
			
			<div class="form-group">
				<button type="button" id="btnEdit" class="btn btn-primary float-right">Edytuj <i class="fas fa-edit"></i></button>
			</div>
		</div>
	</div>
</div>

	<!-- Categories order modal -->
<div class="modal fade" id="modalChangeList">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title"></h4>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>
			<div class="modal-body"></div>
			<div class="modal-footer">
				<button type="button" class="btn btn-success" id="btnModalListSave"><i class="fas fa-save"></i> Zapisz</button>
				<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fas fa-times"></i> Zamknij</button>
			</div>
			
		</div>
	</div>
</div>

<!-- Category edit modal -->
<div class="modal fade" id="modalEdit">
  <div class="modal-dialog">
    <div class="modal-content">

      <div class="modal-header">
        <h4 class="modal-title">Edytowanie kategorii</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <div class="modal-body">
        Modal body..
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
      </div>

    </div>
  </div>
</div>

</div>

<link rel="stylesheet" href="{{ asset('css/tree.css') }}">

<script src="{{ asset('js/sortable.min.js') }}" charset="utf-8"></script>
<script src="{{ asset('js/_validation.js') }}" charset="utf-8"></script>
<script src="{{ asset('js/_admin.categories.js') }}" charset="utf-8"></script>

@endsection