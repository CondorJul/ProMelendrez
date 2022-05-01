<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\auth\AddUserWithPersonRequest;
use App\Http\Requests\auth\ChangePassworWithAuthdRequest;
use App\Http\Requests\auth\ExistEmailRequest;
use App\Http\Requests\auth\SignInRequest;
use App\Http\Requests\auth\RegisterRequest;
use App\Http\Requests\auth\UpdUserWithPersonRequest;
use App\Models\Person;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
 
    /**Gestion de de usuario  */
    public function index(){
        return User::with('person')->with('roles')->get();
    }

    public function addUserWithPerson(AddUserWithPersonRequest $request)
    {
        $person=Person::create($request->person);
        $user = new User();
        $user->name = $person->perName;
        $user->email = $request->email;
        $user->perId=$person->perId;
        $user->password = bcrypt($request->password);
        $user->save();

        $r=array();
        foreach($request->roles as $t){
            array_push($r, $t['name']);
        }
        $user->syncRoles($r);
        
        return response()->json([
            'res' => true,
            'msg' => 'Usuario registrado con exito',
            'data'=>User::where('id', $user->id)->with('person')->with('roles')->get()
        ], 200);
    }

    public function updUserWithPerson(UpdUserWithPersonRequest $request){
  
        $person = Person::where('perId', $request->person['perId'])
            ->update($request->person);

    
        $user = User::where('id',$request->id)->first();
        $user->name = $request->person['perName'];
        $user->email = $request->email;
        $user->save();

        $r=array();
        foreach($request->roles as $t){
            array_push($r, $t['name']);
        }
        $user->syncRoles($r);

        return response()->json([
            'res' => true,
            'msg' => 'Usuario actualizado con exito',
            'data'=>User::where('id', $user->id)->with('person')->with('roles')->get()
        ], 200);
    }
    public function changePassword(Request $request, $id ){
        $user=User::where('id', $id)
        ->update(['password'=>bcrypt($request->newPassword)]);
        
        return response()->json([
            'res' => true,
            'msg' => 'Contraseña actualizada correctamente',
            "data"=> $user
        ],200);
    }

    public function find($id){
        $user=User::where('id', $id)->with('person')->with('roles')->get()->first();
        return response()->json([
            'res' => true,
            'msg' => 'Selección correcta',
            "data"=> $user
        ], ($user)?200:204);
    }

    public function existEmail(ExistEmailRequest $request){
        return $user = User::where('email', $request->email)->first();
    }

    /**Autenticacion */
    public function register(RegisterRequest $request)
    {
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->save();

        return response()->json([
            'res' => true,
            'msg' => 'Usuario registrado con exito'
        ], 200);
    }


    public function signIn(SignInRequest $request){
        $user = User::where('email', $request->email)->with('person')->with('tellers')->first();

 
        if (! $user || ! Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'msg' => ['Las credenciales proporcionados son incorrectos'],
            ]);
        }
     
        $token=$user->createToken($request->email)->plainTextToken;
        $permissions=[];
        foreach($user->getPermissionsViaRoles() as $p){
            $permissions[$p->name]=$p;
        }
        $user['permissions']=$permissions;

        return  response()->json([
            'res'=>true,
            'accessToken'=>$token,
            'user'=>$user
        ]);
    }

    public function signOut(Request $request){
        $request->user()->currentAccessToken()->delete();
        return response()->json([
            'res' => true,
            'msg' => 'Token Eliminado Correctamente.'
        ], 200);
    }

    public function changePasswordWithAuth(ChangePassworWithAuthdRequest $request){
        $user=User::where('id', $request->user()->id)->first();

        if (! $user || ! Hash::check($request->passwordOld, $user->password)) {
            throw ValidationException::withMessages([
                'msg' => ['La contraseña actual es incorrecta.'],
            ]);
        }        
        
        $user->password = bcrypt($request->passwordNew);
        $user->save();

        return response()->json([
            'res' => true,
            'msg' => 'Contraseña cambiado correctamente.'
        ], 200);
    }

    
}
