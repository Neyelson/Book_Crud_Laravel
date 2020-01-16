<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Livros;
use Illuminate\Support\Facades\Auth;
use DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\Rule;
use Validator;
use Session;

class LivrosController extends Controller
{

	public function __construct()
	{

		$this->middleware('auth');

	}

	public function mostrarFormulario () {

		return view('cadastrar')->with('maxid', Livros::max('id')+1);

	}

	public function ver () {

		$livro = new Livros;
		$livro = $livro->all()->sortByDesc('id');
		$livro = DB::table('livros')->orderBy('id', 'desc')->whereNull('deleted_at')->paginate(10);

		return view('verlivros', compact('livro'));

	}

	public function vereditar ($id) {

		$livro['nome']=DB::table('livros')->where('id', $id)->value('titulo');
		$livro['escritor']=DB::table('livros')->where('id', $id)->value('escritor');
		$livro['status']=DB::table('livros')->where('id', $id)->value('status');
		$livro['descricao']=DB::table('livros')->where('id', $id)->value('descricao');

		$livro['idcadastradopor']=DB::table('livros')->where('id', $id)->value('user_id');
		$livro['cadastradopor']=DB::table('users')->where('id', $livro['idcadastradopor'])->value('name');
		$livro['cadastradopor'] = implode(" ", array_slice(explode(' ', $livro['cadastradopor']), 0, 2) );


		return view('editar')->with('id', $id)->with('livro', $livro);

	}

	public function apagar ($id) {

		//para soft deletar o id do banco de dados
		$idmessage = $id;
		$id = Livros::find($id);

		if (!empty($id)){
			$id->delete();
		}
		
		return redirect()->route('verlivros')->with('mensagemDeletadoSucesso','O livro ID #'.$idmessage.' foi deletado com sucesso.');

	}

	public function cadastrar (Request $dados) {

		$validatedData = $dados->validate([

			'titulo' => 'required|unique:livros,titulo,NULL,id,deleted_at,NULL',
			'escritor' => 'required',
			'status' => 'required',
			'descricao' => 'required',
		]);

		$dados = $dados->all();

		$dados['user_id']=Auth::id();

		Livros::create($dados);

		return view('cadastrar')->with('mensagemSucesso','O livro "'.$dados['titulo'].'" foi registrado com sucesso.')->with('maxid', Livros::max('id')+1);

	}

	public function editar ($id, Request $dados) {

		//unique:table,column,except,idColumn
		//o primeiro é a tabela
		//o segundo é a coluna que você deseja que tenha o valor único
		//o terceiro é o id que você quer que seja ignorado
		//o quarto é para caso a sua coluna que guarda o ID não se chame 'ID', você pode informar qual é o outro nome

		$validatedData = $dados->validate([
			'titulo' => 'required|unique:livros,titulo,'.$id.',id,deleted_at,NULL',
			'escritor' => 'required',
			'status' => 'required',
			'descricao' => 'required',
		]);

		$dados = $dados->all();

		Livros::where('id', $id)->update(['titulo' => $dados['titulo']]);
		Livros::where('id', $id)->update(['escritor' => $dados['escritor']]);
		Livros::where('id', $id)->update(['status' => $dados['status']]);
		Livros::where('id', $id)->update(['descricao' => $dados['descricao']]);

		return back()->with('mensagemSucesso', 'O livro "'.$dados['titulo'].'" foi atualizado com sucesso.');

	}

}
