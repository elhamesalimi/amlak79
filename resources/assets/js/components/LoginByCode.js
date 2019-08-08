import React from 'react';
import Modal from "./Partials/Modal";
import {ACCESS_TOKEN, REFRESH_TOKEN} from "../api/strings";
import {loginAPI} from "../api/apiURLs";
import axios from "axios/index";
import {loginUser, logoutUser} from "../actions/authentication";
import {connect} from 'react-redux';

class LoginByCode  extends React.Component {
    constructor(props) {
        super(props);
        const {phone} = props;
        this.state = {
            phone,
            activationCode: '',
        }
    }

    handleInput = (e) => {
        const target = e.target;
        this.setState({
            activationCode: target.value,
        })
    };
    handelSubmitFormActive = (e) => {
        e.preventDefault();
        const {phone, activationCode} = this.state;
        console.log('activationCode', activationCode);
        if (activationCode !== null || activationCode !== '' || activationCode !== 'undefinded') {
            const data = {
                grant_type: "password",
                client_id: "2",
                client_secret: window.Laravel.client_secret,
                username: phone,
                password: activationCode,
                scope: "*",
                guard: 'api'
            };

            axios.post(loginAPI, data)
                .then((response) => {
                    window.localStorage.setItem(ACCESS_TOKEN, response.data.access_token);
                    window.localStorage.setItem(REFRESH_TOKEN, response.data.refresh_token);
                    this.props.dispatch(loginUser());
                    this.props.isAuth();
                    this.setState({
                        invalidCredentials: false,
                    });

                })
                .catch(() => {
                    this.setState({
                        invalidCredentials: true,
                    });
                    window.localStorage.removeItem(ACCESS_TOKEN);
                    this.props.dispatch(logoutUser());
                });
        }
    };

    render() {
        return (
            <form onSubmit={this.handelSubmitFormActive}>
                شماره تماس:
                <input type="hidden" name={"phone"} value={this.state.phone}/>
                <div className="panel-body">
                    <div className="alert alert-success text-center">کد تایید به
                        شماره {this.state.phone} پیامک شد.
                    </div>
                    <div className="form-group ">
                        {/*<p className="text-center">{this.state.counter}</p>*/}
                        <input type="number" onChange={this.handleInput}
                               id="activation_code"
                               required={true} name="activation_code" className="form-control"/>
                        {this.state.invalidCredentials && (
                            <span
                                className="bg-danger help-block">کد تایید وارد شده صحیح نمی باشد.</span>
                        )}
                    </div>
                    <button type="submit" className="btn btn-default pull-left ">تایید
                    </button>
                    {/*<a className={`btn btn-danger pull-left ${!this.state.counter ? '' : 'disabled'}`}*/}
                    {/*onClick={this.sendActivationCode}>ارسال مجدد کد*/}
                    {/*تایید</a>*/}
                    {this.props.children}
                </div>
            </form>
        );
    }
}

export default connect()(LoginByCode);