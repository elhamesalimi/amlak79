 {{--* User: salimi--}}
 {{--* Date: 04/27/2019--}}
 {{--* Time: 10:45 AM--}}
 @extends('admin.master')
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
     </style>
 @endpush
 @section('content')
     <div id="content">

         @include('admin.cities.regions.index')
     </div>
     <div class="col-sm-4 col-xs-12 " id="create">
         <?php
         $array = array();
         $array['categories'] = $cities;
//         if(isset($category))
//             $array['category'] = $category;
//         ?>

         @include('admin.cities.regions.create',$cities);
     </div>
     <div class="container" style="margin-top: 20px;">


     </div>
     <div class="loading">
         <i class="fa fa-refresh fa-spin fa-2x fa-fw"></i><br/>
         {{--<span>Loading</span>--}}
     </div>
 @endsection
 @push('scripts')
     <script src="{{asset('/theme/admin/js/ajax-crud.js')}}"></script>
     <script>
         $(document).ready(function(){
             $('.search').click();
         });
     </script>
 @endpush