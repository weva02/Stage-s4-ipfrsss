<?php
  
  namespace App\Http\Livewire\ExampleLaravel;
    use Illuminate\Http\Request;
    use Livewire\Component;

    use App\Models\Typeymntprofs;
    
    class TypeymntprofsController extends Component
    {
        /**
         * Write code on Method
         *
         * @return response()
         */
        public function index()
        {
            $typeymntprofs = Typeymntprofs::all();
            return view('types',compact('typeymntprofs'));
        }
    }