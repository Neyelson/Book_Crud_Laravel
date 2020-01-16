@extends('layouts.app')

@section('content')

<div class="card" style="border-top:0px;">
	<div class="card-body">

		@if (session('mensagemDeletadoSucesso'))
		<div class="alert alert-warning">
			{{ session('mensagemDeletadoSucesso') }}
		</div>
		@endif

		<label for="tabela"><h5>Todos os livros cadastrados:</h5></label>
		
		{{ $livro->links() }}

		<table name="tabela" class="table table-sm" style="margin-bottom: 0px;">
			<thead>
				<tr>
					<th scope="col">ID</th>
					<th scope="col">Título</th>
					<th scope="col">Escritor</th>
					<th scope="col">Status</th>
					<th scope="col">Editar/Deletar</th>
				</tr>
			</thead>
			<tbody>

				@foreach($livro as $livros)
				<tr>
					<th scope="row">#{{$livros->id}}</th>
					<td>{{$livros->titulo}}</td>
					<td>{{$livros->escritor}}</td>
					<td>{{$livros->status}}</td>
					<td>
						<a class="btn btn-primary btn-sm mr-1" href="{{ url('livros/editar/'.$livros->id) }}" ><i class="fa fa-pencil" aria-hidden="true"></i></button>
							<a class="btn btn-danger btn-sm" href="{{ url('livros/apagar/'.$livros->id) }}" onclick="return confirm('Tem certeza de que deseja deletar o livro &quot;{{$livros->titulo}}&quot;?');"><i class="fa fa-trash" aria-hidden="true"></i></button>
							</td>
						</tr>
						@endforeach

					</tbody>

				</table>
			</div>
		</div>

		@endsection