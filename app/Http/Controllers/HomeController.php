<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\User;
use App\Categoria;
use App\Curso;
use App\Inscripcion;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
       // $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
        $users = User::where('type','=','author')->orderBy('name', 'asc')->get();
       
        return view('welcome',['users' => $users]);
    }

    public function getListTutoresArray() {
        $tutores = User::where('type','=','author')->select('id','name')->orderBy('name', 'asc')->get();
        $aux_tuts = array();
        foreach($tutores as $tutor){    $aux_tuts[$tutor->id] = $tutor->name; }
        return $aux_tuts;
    }

    public function getListCategorisArray() {
        $categorias = Categoria::select('id','categoria')->orderBy('categoria', 'asc')->get();
        $aux_cats = array();
        foreach($categorias as $categoria){    $aux_cats[$categoria->id] = $categoria->categoria; }
        return $aux_cats;
    }

    public function oferta()
    {               
        $cursos = Curso::where('estado','=',1)->where('tipo','=',1)->orderBy('nombre', 'asc')->get();

        return view('oferta',[ 'cursos' => $cursos, 'tutores' => $this->getListTutoresArray(), 'categorias' => $this->getListCategorisArray()]);
    }

    public function diplomados()
    {               
        $cursos = Curso::where('estado','=',1)->where('tipo','=',2)->orderBy('nombre', 'asc')->get();

        return view('diplomados',[ 'cursos' => $cursos, 'tutores' => $this->getListTutoresArray(), 'categorias' => $this->getListCategorisArray()]);
    }
    
    public function detalle(Request $request, $id)
    {
        $curso = array();
        $curso= Curso::where('id','=',$id)->orderBy('nombre', 'asc')->get();
        //var_dump($curso); exit; die;
        $user = auth()->user();
        //$data = $request->session()->all();
        
        return view('detalle', ['curso' => $curso[0], 'tutores' => $this->getListTutoresArray(), 'categorias' => $this->getListCategorisArray(), 'user' => $user]);
    }

    public function dashboard(){
        return view('home');
    }

    public function inscribirme($id){
        $user = auth()->user();
        $nd = getDate();
       
        $inscripcion = new Inscripcion();
        $inscripcion->id_curso = $id;
        $inscripcion->id_alumno = $user->id;
        $inscripcion->fecha = $nd['year'].'-'.$nd['mon'].'-'.$nd['mday'];
        $inscripcion->save();
        
        $curso = Curso::findOrFail($id);

       // $to = "coordinacion@surcoestudios.com";
        $to = "ccmonpan@hotmail.com";
        $subject = "Solicitud ingreso al curso";
        $message = " Nombre: ". $user->name."\r\n
         Telefonos: ".$user->telefonos."\r\n
         Correo: ". $user->email."\r\n
         Curso/Diplomado ".$curso->nombre;
        $headers = "From: respuesta@surcoestudio.com" . "\r\n";
        $headers = "Content-type:text/html;charset=UTF-8" . "\r\n";
        
        mail($to, $subject, $message);
        
        return view('registrado');
    }
}
