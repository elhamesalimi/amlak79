 {{--* User: salimi--}}
 {{--* Date: 02/27/2019--}}
 {{--* Time: 09:03 AM--}}
 @extends('admin.layouts.master')
 @push('styles')
     <style>
         .loading {
             background: lightgrey;
             padding: 15px;
             position: fixed;
             border-radius: 4px;
             left: 50%;
             top: 50%;
             text-align: center;
             margin: -40px 0 0 -50px;
             z-index: 2000;
             display: none;
         }

         /*a, a:hover {*/
         /*color: white;*/
         /*}*/

         .form-group.required label:after {
             content: " *";
             color: red;
             font-weight: bold;
         }
         .is-invalid{
             border-color: red;
         }
         .modal.fade.modal-sm.in{
             margin: auto;
         }
         .modal-open {
             overflow: auto;
         }
     </style>
 @endpush
 @section('content')
     <div class="col-sm-4 col-xs-12 " id="create">
         <?php
         $array = array();
         $array['categories'] = $vocats;
         if(isset($vocabulary))
             $array['category'] = $vocabulary;
         ?>
         <div >
          @include('admin.vocabularies.create',$array);
         </div>
     </div>
     <div id="content">
         @include('admin.vocabularies.index')
     </div>
     <div class="container" style="margin-top: 20px;">


     </div>
     <div class="loading">
         <i class="fa fa-refresh fa-spin fa-2x fa-fw"></i><br/>
         {{--<span>Loading</span>--}}
     </div>
 @endsection
 @push('scripts')
     <script src="{{asset('assets/admin/assets/js/ajax/ajax-crud.js')}}"></script>
     <script>
         $(document).ready(function(){
             $('.search').click();
         });
     </script>
 @endpush