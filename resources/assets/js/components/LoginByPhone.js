import React from 'react'
import Modal from "./Partials/Modal";

class LoginByPhone extends React.Component {
    constructor(prop) {
        super(prop);
        this.state = {
            autentication: false,

    };
    }

    render() {
        return (
            <Modal show={!estate.owner.active} handleClose={this.hideModal}>
                <div className="panel panel-default ">
                    <div className="panel-heading">
                        تایید شماره تماس
                        <i className="fa fa-times pull-left" onClick={this.hideActiveModal}></i>
                    </div>
                    <form onSubmit={this.handelSubmitFormActive}>
                        <input type="hidden" name={"phone"} value={estate.owner.phone}/>
                        <div className="panel-body">
                            <div className="alert alert-success text-center">کد تایید به
                                شماره {estate.owner.phone} پیامک شد.
                            </div>
                            <div className="form-group ">
                                <p className="text-center">{this.state.counter}</p>
                                <input type="number" onChange={this.handleInput}
                                       id="activation_code"
                                       required={true} name="activation_code" className="form-control"/>
                                {this.state.invalidCredentials && (
                                    <span
                                        className="bg-danger help-block">کد تایید وارد شده صحیح نمی باشد.</span>
                                )}
                            </div>
                            <button type="submit" className="btn btn-default pull-right ">تایید
                            </button>
                            <a className={`btn btn-danger pull-left ${!this.state.counter ? '' : 'disabled'}`}
                               onClick={this.sendActivationCode}>ارسال مجدد کد
                                تایید</a>
                        </div>
                    </form>
                </div>
            </Modal>
        );
    }
}

export default LoginByPhone
