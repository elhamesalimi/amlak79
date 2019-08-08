<div class="row">
    <div class="col-sm-12">
        <div class="panel panel-default card-view">
            <div class="panel-wrapper collapse in">
                <div class="panel-body">
                    <div class="row">

                        <div class="col-md-12">
                            <div class="panel panel-default border-panel card-view pa-0">
                                <div class="panel-wrapper collapse in">
                                    <div class="recent-chat-box-wrap">

                                        <div class="panel panel-info ">
                                            <div class="panel-heading">
                                                <div class="inline-message"> کاربر :
                                                    {{ $contact->name }}
                                                    <div class="pull-right">
                                                        {{ jdate($contact->created_at)->format('Y/m/d , H:i:s') }}
                                                    </div>
                                                </div>

                                            </div>
                                            <div class="panel-body">
                                                <div class=" col-md-1">
                                                    <img class="img-avatar" src="{{asset('public_data/asset/img/avatar1.png')}}" alt="">
                                                </div>
                                                <div class=" col-md-11">
                                                    <p class="hz-justify">
                                                        <strong> متن :</strong>
                                                    {{ $contact->body }}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="panel-group ticket">
                                            <div class="panel panel-default">
                                                <div class="panel-heading">
                                                    <div class="row">
                                                        <div class="col-md-12">

                                                            <a href="javascript:ajaxLoad('{{action('admin\ContactController@index')}}')" class="btn btn-block btn-danger push-10">
                                                                <i class="fa fa-close"></i>
                                                                بستن
                                                            </a>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>