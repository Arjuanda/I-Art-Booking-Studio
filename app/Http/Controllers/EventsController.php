<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;


class EventsController extends Controller
{
    public function index(){
        $data_event = Event::with('eventMaker')->latest()->get();
        return view('admin.events' , ['title' => 'Event','data' => $data_event]);
    }

    public function store(Request $request){
        $request->validate([
            'title'=>'required'/*|min:8|max:12*/,
            'date'=>'required',
            'description'=>'required',
            'poster'=>'required|image|mimes:jpg,png,jpeg|max:2000',
            'poster.image' => 'Poster harus berupa gambar',
        ]);
        $slug = Str::slug($request->title);
        $posterName = Str::random(10).'.'.$request->poster->extension();
        $request->poster->storeAs('images', $posterName, 'public');
        Event::create([
            'user_id'=>Auth::id(),
            'title'=>$request->title,
            'slug'=>$slug,
            'date'=>$request->date,
            'description'=>$request->description,
            'poster'=>$posterName,
        ]);
        return redirect('/events')->with('success','Data has been successfully added.');
    }

    public function edit($id){
        $data_event = Event::findOrFail($id);

        return response()->json($data_event);
    }

    public function update($id,Request $request){
        $event = Event::findOrFail($id);
        $validator = Validator::make($request->all(), [
            'title'=>'required'/*|min:8|max:12*/,
            'date'=>'required',
            'description'=>'required',
            'poster'=>'image|mimes:jpg,png,jpeg|max:2000',
        ]);

        $posterName = $event->poster; 
        if($request->hasFile('poster')){
            if ($event->poster && Storage::disk('public')->exists('images/'.$event->poster)) {
                Storage::disk('public')->delete('images/'.$event->poster);
            }
            $posterName = Str::random(10).'.'.$request->poster->extension();
            $request->poster->storeAs('images', $posterName, 'public');   
        }else{
            $old_data = Event::findOrFail($id);
            $posterName = $old_data->poster;
        }
        $slug = Str::slug($request->title);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput()
                ->with('modal', [
                    'type' => 'event',
                    'action' => 'edit',
                    'id' => $id
                ]);
        }

        Event::where('id',$id)->update([
            'user_id'=>Auth::id(),
            'title' => $request->title,
            'slug' => $slug,
            'date' => $request->date,
            'description' => $request->description,
            'poster' => $posterName
        ]);
        
        return redirect('events')->with('success','Data has been successfully updated');
    }

    public function destroy($id){
        $event = Event::findOrFail($id);
        if ($event->poster && Storage::disk('public')->exists('images/'.$event->poster)) {
            Storage::disk('public')->delete('images/'.$event->poster);
        }
        $event->delete();
        return redirect('/events')->with('success', 'Data has been successfully deleted');
    }
}
