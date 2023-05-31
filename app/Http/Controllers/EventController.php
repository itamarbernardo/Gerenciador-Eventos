<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class EventController extends Controller
{
    public function index(){
        
        $search = request('search');

        if($search){
            #Se tiver algo retornado na busca
            $events = Event::where([
                ['title', 'like', '%'.$search.'%']
            ])->get();
        }else{
            $events = Event::all(); #pega todos os eventos do banco
        }
        
        return view('welcome', ['events' => $events, 'search' => $search]);
    }

    public function create(){
        return view('events.create');
    }

    public function store(Request $request){

        $event = new Event();

        $event->title = $request->title;
        $event->description = $request->description;
        $event->city = $request->city;
        $event->private = $request->private;
        $event->items = $request->items;
        $event->date = $request->date;
        
        //Image Upload
        if($request->hasFile('image') && $request->file('image')->isValid()) {

            $requestImage = $request->image;

            $extension = $requestImage->extension();

            //Vamos criar um novo nome para a imagem, gerando um hash md5 com o nome da imagem e a data-hora do upload
            $imageName = md5($requestImage->getClientOriginalName() . strtotime("now")) . "." . $extension;

            //Colocamos essas imagens dos eventos na pasta public/img/events
            $requestImage->move(public_path('img/events'), $imageName);

            //salvamos o nome da imagem no Model event para salvar no banco
            //para recuperar a imagem, basta acessar o /public/img/NOME_IMAGEM
            $event->image = $imageName;

        }

        //Vamos pegar o ID do usuário logado
        $user = Auth::user();
        $event->user_id = $user->id;

        $event->save();

        return redirect('/')->with('msg', 'Evento Criado com Sucesso!');
    }

    public function show($id){
        $event = Event::findOrFail($id);

        #first -> ele pega o primeiro que achar -> como o ID é único, só vai ter um usuário 
        #e ele nao precisa mais procurar no restante do banco
        #Poderiamos mandar o objeto também, sem transformar em Array
        $eventOwner = User::where('id', $event->user_id)->first()->toArray(); 

        #para Pegar o nome do user, poderiamos fazer assim também:
        #$user = User::findOrFail($event->user_id);
        #$name_user = $user->name;
        return view('events.show', ['event' => $event, 'eventOwner' => $eventOwner]);
    }

    public function dashboard(){
        $user = auth()->user();
        
        #outra forma de pegar o usuario logado:
        #$user = Auth::user();
        
        $events = $user->events;

        return view('events.dashboard', ['events' => $events]);
    }

    public function destroy($id){
        
        Event::findOrFail($id)->delete();

        return redirect('/dashboard')->with('msg', 'Evento Excluído com Sucesso!');
    }
}
